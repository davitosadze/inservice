<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class ServiceNotifiable
{
    use Notifiable;

    public function routeNotificationForMail($notification)
    {
        return 'noreply@inservice.ge';
    }
}
