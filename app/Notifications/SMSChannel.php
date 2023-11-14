<?php
 
namespace App\Notifications;
 
use Illuminate\Notifications\Notification;
 
class SMSChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $message = $notification->toMySms($notifiable);
 
        // Send notification to the $notifiable instance...
    }
}