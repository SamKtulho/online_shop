<?php

declare(strict_types=1);

namespace App\Logging\Telegram;

use App\Services\Telegram\TelegramBotApi;
use JetBrains\PhpStorm\NoReturn;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

class TelegramLoggerHandler extends AbstractProcessingHandler
{
    private int $chatId;

    private string $token;

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);

        parent::__construct($level);

        $this->chatId = $config['chat_id'];
        $this->token = $config['token'];
    }

    #[NoReturn] protected function write(\Monolog\LogRecord $record): void
    {
        $result = TelegramBotApi::sendMessage(
            $this->token,
            $this->chatId,
            $record->formatted,
        );
    }
}
