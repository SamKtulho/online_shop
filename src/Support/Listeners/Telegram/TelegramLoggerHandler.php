<?php

declare(strict_types=1);

namespace Services\Logging\Telegram;

use JetBrains\PhpStorm\NoReturn;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Services\Telegram\TelegramBotApi;

class TelegramLoggerHandler extends AbstractProcessingHandler
{
    private int $chatId;

    private string $token;

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);

        parent::__construct($level);

        $this->chatId = (int) $config['chat_id'];
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
