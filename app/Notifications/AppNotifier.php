<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppNotifier extends Notification
{
//    use Queueable;
    private $notification;

    /**
     * Create a new notification instance.
     *
     * @param string $message
     * @param string $action
     * @param string $action_text
     * @param string $subject
     * @param string $text
     * @param bool $save
     */
    public function __construct(
        string $message = '',
        string $action = '',
        string $action_text = 'View Now',
        string $subject = 'New Notification',
        string $text = 'Update on Event',
        $save = false
    )
    {
        $notification = [
            'message' => $message,
            'text' => $text,
            'action_text' => $action_text,
            'action' => $action,
            'subject' => $subject,
            'save' => $save
        ];
        $this->notification = $notification;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $not = $this->notification;
        if (!$not['save']) {
            return ['mail'];
        }
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $not = $this->notification;

        $data = [
            'name' => $notifiable->name,
            'action' => $not['action_text'],
            'goto' => $not['action'],
            'message' => $not['message'],
            'subject' => $not['subject']
        ];
        return (new MailMessage)->subject($not['subject'])->view('emails.mailer', compact('data'));
    }

    public function toDatabase($notifiable)
    {
        $not = $this->notification;
        return [
            'data' => $not['message'],
            'message' => $not['text'],
            'subject' => $not['subject'],
            'action' => $not['action']
        ];
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
}
