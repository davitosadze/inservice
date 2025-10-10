<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Report;
use App\Models\Option;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class NewResponseNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $response;

    public function __construct($user, $response)
    {
        $this->user = $user;
        $this->response = $response;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Only send email if response has by_client set to true
        if (!$this->response?->by_client) {
            return null;
        }

        $id = $this->response?->id;

        // Get settings email for CC
        $option = Option::first();
        $settingsEmail = $option?->email;

        if ($this->response?->status == 9) {
            $mail = (new MailMessage())
                ->from('noreply@inservice.ge', 'Inservice')
                ->subject('შეკვეთა - QR' . $id)
                ->view('emails.response-notification', [
                    'response' => $this->response,
                    'user' => $this->user,
                    'id' => $id,
                    'status' => 'new'
                ]);

            // Add CC if settings email exists
            if ($settingsEmail) {
                $mail->cc($settingsEmail);
            }

            // Attach invoice PDF if available
            $this->attachInvoicePdf($mail);

            // Attach report PDF if available
            $this->attachReportPdf($mail);

            // Attach chat PDF if available
            $this->attachChatPdf($mail);

            return $mail;
        }

        if ($this->response?->status == 3) {
            $mail = (new MailMessage())
                ->from('noreply@inservice.ge', 'Inservice')
                ->subject('შეკვეთა - QR' . $id)
                ->view('emails.response-notification', [
                    'response' => $this->response,
                    'user' => $this->user,
                    'id' => $id,
                    'status' => 'completed'
                ]);

            // Add CC if settings email exists
            if ($settingsEmail) {
                $mail->cc($settingsEmail);
            }

            // Attach invoice PDF if available
            $this->attachInvoicePdf($mail);

            // Attach report PDF if available
            $this->attachReportPdf($mail);

            // Attach chat PDF if available
            $this->attachChatPdf($mail);

            return $mail;
        }

        return null;
    }

    /**
     * Attach invoice PDF to the mail message if invoice exists
     */
    private function attachInvoicePdf(MailMessage $mail)
    {
        try {
            $invoice = $this->response->invoice();
            
            if ($invoice) {
                // Load invoice with necessary relationships
                $model = Invoice::with(['purchaser', 'category_attributes.category', 'user', 'parent'])
                    ->find($invoice->id);
                
                if ($model) {
                    $modelArray = $model->toArray();
                    $user = User::find($modelArray["user"]["id"]);
                    $filename = "ინვოისი " . $modelArray['uuid'] . '.pdf';

                    // Generate PDF
                    $pdf = PDF::setOptions([
                        'isRemoteEnabled' => true, 
                        'dpi' => 150, 
                        'defaultFont' => 'sans-serif'
                    ])->loadView('invoices.pdf', [
                        'model' => $modelArray, 
                        'user' => $user
                    ]);

                    // Attach PDF to email
                    $mail->attachData($pdf->output(), $filename, [
                        'mime' => 'application/pdf',
                    ]);
                    
                    Log::info('Invoice PDF attached to email for response: ' . $this->response->id);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't fail the email sending
            Log::error('Failed to attach invoice PDF to email: ' . $e->getMessage(), [
                'response_id' => $this->response->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Attach report PDF to the mail message if report exists
     */
    private function attachReportPdf(MailMessage $mail)
    {
        try {
            $report = $this->response->report();
            
            Log::info('Checking report for response: ' . $this->response->id, [
                'report_exists' => $report ? 'yes' : 'no',
                'report_id' => $report->id ?? 'null'
            ]);
            
            if ($report) {
                // Load report with necessary relationships (exactly like ReportController)
                $model = Report::with(['items'])
                    ->firstOrNew(['id' => $report->id]);
                
                Log::info('Report model loaded', [
                    'model_exists' => $model->exists,
                    'has_uuid' => isset($model->uuid),
                    'uuid' => $model->uuid ?? 'null'
                ]);
                
                if ($model->exists && !empty($model->uuid)) {
                    $filename = "დეფექტური " . $model->uuid . '.pdf';

                    // Generate PDF using the exact same logic as ReportController
                    $pdf = PDF::setOptions([
                        "isPhpEnabled" => true, 
                        'isRemoteEnabled' => true, 
                        'dpi' => 150, 
                        'defaultFont' => 'sans-serif'
                    ])->loadView('reports.show', compact('model'));

                    // Attach PDF to email
                    $mail->attachData($pdf->output(), $filename, [
                        'mime' => 'application/pdf',
                    ]);
                    
                    Log::info('Report PDF attached to email for response: ' . $this->response->id);
                } else {
                    Log::warning('Report model empty or missing UUID for response: ' . $this->response->id);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't fail the email sending
            Log::error('Failed to attach report PDF to email: ' . $e->getMessage(), [
                'response_id' => $this->response->id,
                'report_id' => $report->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Attach chat PDF to the mail message if chat exists
     */
    private function attachChatPdf(MailMessage $mail)
    {
        try {
            $chat = $this->response->chat();
            
            Log::info('Checking chat for response: ' . $this->response->id, [
                'chat_exists' => $chat ? 'yes' : 'no',
                'chat_id' => $chat->id ?? 'null'
            ]);
            
            if ($chat) {
                // Load chat with messages and related models (exactly like ChatController)
                $chatModel = \App\Models\Chat::with(['messages.user', 'response', 'repair'])
                    ->find($chat->id);
                
                if ($chatModel && $chatModel->messages->count() > 0) {
                    // Get the related item (response or repair) info - same logic as ChatController
                    $relatedItem = null;
                    $relatedItemType = '';
                    
                    if ($chatModel->type === 'response' && $chatModel->response) {
                        $relatedItem = $chatModel->response;
                        $relatedItemType = 'რეაგირება';
                    } elseif ($chatModel->type === 'repair' && $chatModel->repair) {
                        $relatedItem = $chatModel->repair;
                        $relatedItemType = 'რემონტი';
                    }
                    
                    $filename = "ჩატის ისტორია " . $chatModel->id . '.pdf';

                    // Generate PDF using the exact same logic as ChatController
                    $pdf = PDF::setOptions([
                        'isRemoteEnabled' => true, 
                        'dpi' => 150, 
                        'defaultFont' => 'sans-serif'
                    ])->loadView('chats.pdf', ['chat' => $chatModel, 'relatedItem' => $relatedItem, 'relatedItemType' => $relatedItemType]);

                    // Attach PDF to email
                    $mail->attachData($pdf->output(), $filename, [
                        'mime' => 'application/pdf',
                    ]);
                    
                    Log::info('Chat PDF attached to response email for response: ' . $this->response->id);
                } else {
                    Log::info('Chat exists but has no messages for response: ' . $this->response->id);
                }
            }
        } catch (\Exception $e) {
            // Log error but don't fail the email sending
            Log::error('Failed to attach chat PDF to response email: ' . $e->getMessage(), [
                'response_id' => $this->response->id,
                'chat_id' => $chat->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function routeNotificationForMail($notifiable)
    {
        // Send to the client's email (user who created the response)
        return $this->user->email ?? 'noreply@inservice.ge';
    }
}
