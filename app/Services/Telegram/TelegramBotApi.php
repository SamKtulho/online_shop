<?php

declare(strict_types=1);

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;

class TelegramBotApi
{
    const TELEGRAM_API_HOST = 'https://api.telegram.org/bot';

    public static function sendMessage(string $token, int $chatId, string $message): bool {

        $telegramResponse = Http::withoutVerifying()->get(
            self::TELEGRAM_API_HOST . $token . '/sendMessage',
            ['chat_id' => $chatId, 'text' => $message]
        );

        return ($json = json_decode($telegramResponse->body())) && isset($json->ok) && $json->ok === true;
    }
}
