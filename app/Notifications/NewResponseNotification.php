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
        $mail = new MailMessage();
        $id = $this->response?->id;

        if ($this->response?->status == 9) {
            return $mail
                ->from('noreply@inservice.ge', 'InService')
                ->cc('service@inservice.ge')
                ->subject('შეკვეთა - QR' . $id)
                ->line('შეკვეთა #QR' . $id . ' შემოვიდა')
                ->line('სახელი: ' . $this->user->name)
                ->line('კომპანიის სახელი: ' . $this->user->getClient()?->client_name)
                ->line('დამატებითი სახელი: ' . $this->response?->subject_name)
                ->line('მისამართი: ' . $this->response?->subject_address)
                ->line('დეტალები:')
                ->line($this->response?->content)
                ->line('დეტალების გასაცნობად ეწვიეთ შეკვეთების გვერდს.')
                ->action('ნახეთ შეკვეთა', url('/responses/' . $id));
        }

        if ($this->response?->status == 3) {
            return $mail
                ->from('noreply@inservice.ge', 'InService')
                ->cc('service@inservice.ge')
                ->subject('შეკვეთა - QR' . $id)
                ->line('თქვენი შეკვეთა #QR' . $id . ' დასრულებულია')
                ->line('სახელი: ' . $this->user->name)
                ->line('კომპანიის სახელი: ' . $this->user->getClient()?->client_name)
                ->line('დამატებითი სახელი: ' . $this->response?->subject_name)
                ->line('მისამართი: ' . $this->response?->subject_address)
                ->line('შეკვეთის გაფორმების დრო: ' . $this->response?->created_at)
                ->line('ადგილზე მისვლის დრო ფაქტიური: ' . $this->response?->time)
                ->line('რეაგირების დასრულების დრო: ' . $this->response?->end_time)
                ->line('სამუშაოს აღწერა: ' . $this->response?->job_description)
                ->line('დეტალები:')
                ->line($this->response?->content)
                ->line('დეტალების გასაცნობად ეწვიეთ შეკვეთების გვერდს.')
                ->action('ნახეთ შეკვეთა', url('/responses/' . $id));
        }

        return null;  
    }

    public function routeNotificationForMail($notifiable)
    {
        // Route to noreply as main recipient
        return 'noreply@inservice.ge';
    }
}
