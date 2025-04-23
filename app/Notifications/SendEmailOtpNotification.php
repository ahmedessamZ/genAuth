<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailOtpNotification extends notification
{
    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via()
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Email Verifications')
            ->greeting('Hello, User!')
            ->line("Your code is : $this->code");
    }

}
