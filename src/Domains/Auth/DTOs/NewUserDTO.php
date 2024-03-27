<?php

declare(strict_types=1);

namespace Domains\Auth\DTOs;

use Illuminate\Http\Request;
use Support\Traits\Makeable;

final readonly class NewUserDTO
{
    use Makeable;

    public function __construct(
        public string $name,
        public string $email,
        public string $password
    )
    {
    }

    public static function fromRequest(Request $request): self
    {
        return self::make(...$request->only(['name', 'email', 'password']));
    }
}
