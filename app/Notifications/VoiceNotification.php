<?php

namespace App\Notifications;

use App\Channels\OtpChannel;
use App\Channels\VoiceChannel;
use App\Services\SmartSmsService;
use Illuminate\Notifications\Notification;

class VoiceNotification extends Notification
{
//    use Queueable;
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param $message
     */
    public function __construct()
    {
        $this->token = rand(22222, 99999);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [VoiceChannel::class, OtpChannel::class];
    }

    public function toVoice($notifiable)
    {
        $log = (new SmartSmsService())->sendVoiceOTP($notifiable->phone, $this->token);
        logs()->info('Send Voice Sms', (array) $log);
        return $log;
    }

    public function toOtp($notifiable)
    {
        $notifiable->otp = $this->token;
        return $notifiable->save();
    }


}
