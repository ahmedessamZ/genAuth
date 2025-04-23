<?php

namespace App\Broadcasting;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

class WhatsappChannel
{
    public function send($notifiable,Notification $notification): int
    {
        $messageData = $notification->toWhatsapp($notifiable);

        $response = (new Client())->post(env('TEXT_ME_BOT_API_URL') . '/send.php', [
            'query' => [
                'apikey' => env('TEXT_ME_BOT_API_KEY'),
                'recipient' => '+' . $notifiable->country_code . $notifiable->phone,
                'text' => $messageData['message'],
            ],
        ]);


        Log::info('TextMeBot Response:', [
            'status_code' => $response->getStatusCode(),
            'body' => $response->getBody()->getContents(),
        ]);

        return $response->getStatusCode();
    }
}
