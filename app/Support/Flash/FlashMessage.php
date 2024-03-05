<?php

declare(strict_types=1);

namespace App\Support\Flash;

class FlashMessage
{
    public function __construct(protected string $message, protected string $class)
    {
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function class(): string
    {
        return $this->class;
    }
}
