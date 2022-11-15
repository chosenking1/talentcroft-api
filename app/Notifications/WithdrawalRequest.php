<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Models\Withdrawal;
use App\Services\SmartSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WithdrawalRequest extends Notification
{
    use Queueable;

    private $withdrawal;
    private $message;

    /**
     * Create a new notification instance.
     *
     * @param Withdrawal $withdrawal
     */
    public function __construct(Withdrawal $withdrawal)
    {
        $this->withdrawal = $withdrawal;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data = [
            'name' => $notifiable->name,
            'message' => "Confirm your withdrawal with the OTP below," .
                "<p>You can copy this OTP and paste</p>" .
                "<h2>" . $this->withdrawal->otp . "</h2>" .
                "<br/>",
            'caveat' => "If you didn't make the request <a href='https://getgrid.ng/reset' target='_blank'>reset your password</a> immediately ",
            'subject' => "Confirm your withdrawal - getgrid",
        ];
        return (new MailMessage)->subject($data['subject'])->view('mail.mailer', compact('data'));

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

    public function toSms($notifiable)
    {

        $this->message = "Your OTP is " .  $this->withdrawal->otp;
        return (new SmartSmsService())->sendSMS($notifiable->phone, $this->message);
    }
}
