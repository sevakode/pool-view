<?php


namespace App\Http;


use Illuminate\Support\Facades\Http;

class TelegramSender
{
    const BASE_URL = "https://api.telegram.org/bot";

    function __construct () {
        $this->website = self::BASE_URL.env('telegram_token');
    }

    public function sendMessage($chatId, $message)
    {
        $params=[
            'chat_id' => $chatId,
            'text' => $message,
        ];

        return Http::post($this->website . '/sendMessage', $params)->json();
    }
}
