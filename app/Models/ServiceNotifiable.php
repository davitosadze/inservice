<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;

class ServiceNotifiable
{
    use Notifiable;

    public function routeNotificationForMail($notification)
    {
        // Let the notification class handle the routing
        if (method_exists($notification, 'routeNotificationForMail')) {
            return $notification->routeNotificationForMail($this);
        }

        return 'noreply@inservice.ge';
    }
}
