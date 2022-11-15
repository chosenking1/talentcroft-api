<?php

namespace App\Traits;

use App\Listeners\NotifyUser;
use App\Mail\MailSender;
use App\Notifications\AppNotifier;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

trait UseNotifier
{

    /**
     * @param array $mail
     */
    public function sendMail(array $mail)
    {
        $mail['name'] = $mail['name'] ?? $this->name;
        Mail::to($this->email)->send(new MailSender($mail));
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject = ''): UseNotifier
    {
        $this->mail_subject = $subject;
        return $this;
    }

    /**
     * @param string $text
     * @return $this
     */
    public function setText($text = ''): UseNotifier
    {
        $this->mail_text = $text;
        return $this;
    }

    /**
     * @param array $mail
     * @return UseNotifier
     */
    public function saveLog(array $mail = []): UseNotifier
    {
        $mail['text'] = $mail['text'] ?? $this->mail_text ?? '';
        $mail['subject'] = $mail['subject'] ?? $this->mail_subject ?? '';
        $mail['db-only'] = true;
        Notification::send($this, new AppNotifier($mail));
        return $this;
    }

}
