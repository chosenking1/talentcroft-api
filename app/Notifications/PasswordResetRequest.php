<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetRequest extends Notification
{
    use Queueable;
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $token = $this->token;

        $data = [
            'action' => "<h1>$this->token</h1>",// "<a href='https://getgrid.xyx/email/$token' style='padding: 8px 20px; background-color: purple'>Reset Password</a>",
            'subject' => 'Reset Password Notification',
            'title' => 'Hello',
            'message' =>"You are receiving this email because we received a password reset request for your account." .
                "<p>You can copy this OTP and paste</p>",
            'caveat' => 'Once this email address has been confirmed, you’ll be logged into Grid dashboard with your new account.',
            'name' => $notifiable->name,

        ];
        return (new MailMessage)->subject($data['subject'])->view('emails.mailer', compact('data'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
