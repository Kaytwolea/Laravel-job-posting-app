<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmNotification extends Notification
{
    use Queueable;

    private $user;

    private $confirmation_code;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $confirmation_code)
    {
        $this->user = $user;
        $this->confirmation_code = $confirmation_code;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to Jobspace')
            ->view('confirm', [
                'name' => 'Dear ' . $this->user->first_name,
                'confirmation_code' => $this->confirmation_code,
                'content' => "...",
            ]);
    }

    public function toSms($notifiable)
    {

    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
//
//    public function toMySms($notifiable)
//    {
//        sendSms($this->user->phone_number, 'Your confirmation code is ' . $this->confirmation_code . 'It will expire soon');
//    }
}
