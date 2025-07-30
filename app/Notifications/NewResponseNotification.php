<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

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

        $mail = new MailMessage();
        $id = $this->response?->id;

        if ($this->response?->status == 9) {
            return $mail
                ->from('noreply@inservice.ge', 'Inservice')
                ->cc('gordogordel@gmail.com')
                ->subject('შეკვეთა - QR' . $id)
                ->view('emails.response-notification', [
                    'response' => $this->response,
                    'user' => $this->user,
                    'id' => $id,
                    'status' => 'new'
                ]);
        }

        if ($this->response?->status == 3) {
            return $mail
                ->from('noreply@inservice.ge', 'Inservice')
                ->cc('gordogordel@gmail.com')
                ->subject('შეკვეთა - QR' . $id)
                ->view('emails.response-notification', [
                    'response' => $this->response,
                    'user' => $this->user,
                    'id' => $id,
                    'status' => 'completed'
                ]);
        }

        return null;  
    }

    public function routeNotificationForMail($notifiable)
    {
        // Route to noreply as main recipient
        return 'noreply@inservice.ge';
    }
}
