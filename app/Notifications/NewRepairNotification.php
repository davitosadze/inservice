<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRepairNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $repair;

    public function __construct($user, $repair)
    {
        $this->user = $user;
        $this->repair = $repair;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = new MailMessage();
        $id = $this->repair?->id;

        if ($this->repair?->status == 10) {
            return $mail
                ->from('noreply@inservice.ge', 'Inservice')
                ->cc('gordogordel@gmail.com')
                ->subject('შეკვეთა - PR' . $id)
                ->line('შეკვეთა #PR' . $id . ' მიღებულია')
                ->line('სახელი: ' . $this->user->name)
                ->line('კომპანიის სახელი: ' . $this->user->getClient()?->client_name)
                ->line('დამატებითი სახელი: ' . $this->repair?->subject_name)
                ->line('მისამართი: ' . $this->repair?->subject_address)
                ->line('დეტალები:')
                ->line($this->repair?->content)
                ->line('დეტალების გასაცნობად ეწვიეთ შეკვეთების გვერდს.')
                ->action('ნახეთ შეკვეთა', url('/repairs/' . $id));
        }

        if ($this->repair?->status == 3) {
            return $mail
                ->from('noreply@inservice.ge', 'Inservice')
                ->cc('gordogordel@gmail.com')
                ->subject('შეკვეთა - PR' . $id)
                ->line('თქვენი შეკვეთა #PR' . $id . ' დასრულებულია')
                ->line('სახელი: ' . $this->user->name)
                ->line('კომპანიის სახელი: ' . $this->user->getClient()?->client_name)
                ->line('დამატებითი სახელი: ' . $this->repair?->subject_name)
                ->line('მისამართი: ' . $this->repair?->subject_address)
                ->line('შეკვეთის გაფორმების დრო: ' . $this->repair?->created_at)
                ->line('ადგილზე მისვლის დრო ფაქტიური: ' . $this->repair?->time)
                ->line('რეაგირების დასრულების დრო: ' . $this->repair?->end_time)
                ->line('სამუშაოს აღწერა: ' . $this->repair?->job_description)
                ->line('შინაარსი:')
                ->line($this->repair?->content)
                ->line('დეტალების გასაცნობად ეწვიეთ შეკვეთების გვერდს.')
                ->action('ნახეთ შეკვეთა', url('/repairs/' . $id));
        }

        return null;  
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    /**
     * Override the recipient email to always send to noreply@inservice.ge
     *
     * @param  mixed  $notifiable
     * @return string
     */
    public function routeNotificationForMail($notifiable)
    {
        return 'noreply@inservice.ge';
    }
}
