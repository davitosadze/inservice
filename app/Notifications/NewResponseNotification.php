<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Report;
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

        if ($this->response?->status == 9) {
            $mail = (new MailMessage())
                ->from('noreply@inservice.ge', 'Inservice')
                ->cc('gordogordel@gmail.com')
                ->subject('შეკვეთა - QR' . $id)
                ->view('emails.response-notification', [
                    'response' => $this->response,
                    'user' => $this->user,
                    'id' => $id,
                    'status' => 'new'
                ]);

            // Attach invoice PDF if available
            $this->attachInvoicePdf($mail);
            
            // Attach report PDF if available
            $this->attachReportPdf($mail);
            
            return $mail;
        }

        if ($this->response?->status == 3) {
            $mail = (new MailMessage())
                ->from('noreply@inservice.ge', 'Inservice')
                ->cc('gordogordel@gmail.com')
                ->subject('შეკვეთა - QR' . $id)
                ->view('emails.response-notification', [
                    'response' => $this->response,
                    'user' => $this->user,
                    'id' => $id,
                    'status' => 'completed'
                ]);

            // Attach invoice PDF if available
            $this->attachInvoicePdf($mail);
            
            // Attach report PDF if available
            $this->attachReportPdf($mail);
            
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

    public function routeNotificationForMail($notifiable)
    {
        // Route to noreply as main recipient
        return 'noreply@inservice.ge';
    }
}
