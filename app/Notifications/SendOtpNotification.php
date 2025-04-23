<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class SendOtpNotification extends notification
{
    private $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function via()
    {
        return ['whatsapp'];
    }

    public function toWhatsapp($notifiable)
    {
        return [
            'message' => "Your code is : $this->code"
        ];
    }

}
