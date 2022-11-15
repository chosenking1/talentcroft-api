<?php

namespace App\Notifications;

use App\Channels\SmsChannel;
use App\Services\SmartSmsService;
use Illuminate\Notifications\Notification;

class SmsNotifications extends Notification
{
//    use Queueable;
    /**
     * @var
     */
    private $message;

    /**
     * Create a new notification instance.
     *
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [SmsChannel::class];
    }

    public function toSms($notifiable)
    {
        return (new SmartSmsService())->sendSMS($notifiable->phone, $this->message);
    }
}
