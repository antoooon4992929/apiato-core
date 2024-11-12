<?php

namespace Apiato\Core\Abstracts\Notifications;

use Illuminate\Notifications\Notification as LaravelNotification;

class Notification extends LaravelNotification
{
    public function via($notifiable): array
    {
        return config('notification.channels');
    }
}
