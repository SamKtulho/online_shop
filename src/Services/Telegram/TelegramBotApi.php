<?php

declare(strict_types=1);

namespace Services\Telegram;

use App\Exceptions\TelegramLoggerApiException;
use Illuminate\Support\Facades\Http;

class TelegramBotApi
{
    const TELEGRAM_API_HOST = 'https://api.telegram.org/bot7';

    public static function sendMessage(string $token, int $chatId, string $message): bool
    {
        try {
            $telegramResponse = Http::withoutVerifying()->get(
                self::TELEGRAM_API_HOST . $token . '/sendMessage',
                ['chat_id' => $chatId, 'text' => $message]
            )->json();

            return $telegramResponse['ok'] ?? false;
        } catch (\Throwable $e) {
            report(new TelegramLoggerApiException($e->getMessage()));

            return false;
        }
    }
}
