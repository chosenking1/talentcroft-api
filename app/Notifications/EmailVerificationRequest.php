<?php

namespace App\Notifications;

use App\Models\UserVerification;
use Illuminate\Bus\Queueable;
use Log;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailVerificationRequest extends Notification
{
//    use Queueable;
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->token = rand(777777, 999999);;
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
        UserVerification::create(['user_id' => $notifiable->id, 'token' => $this->token]);
        $data = [
            'name' => $notifiable->name,
//            'action' => 'Welcome to getgrid',
//            'goto' =>'https://getgrid.ng/reset-password/' . $this->token,
            'message' => "To get started, kindly use this token." .
                "<p>You can copy this OTP and paste</p>" .
                "<h2>$this->token</h2>" .
                "<br/>" .
                "Once this email address has been confirmed, you’ll be logged into Grid dashboard with your new account.",
            "<br/>",
            'subject' => "Welcome to Grid",
        ];

        // 'action' => "<a href='https://getgrid.xyx/email/$otp' style='padding: 8px 20px; background-color: purple'>Confirm Email Address</a>",
        // 'subject' => 'Welcome to Grid',
        // 'title' => 'Thank you for signing up',
        // 'message' => 'To get started, kindly click the button below to confirm your email address ',
        // 'caveat' => 'Once this email address has been confirmed, you’ll be logged into Grid dashboard with your new account.',
    

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
