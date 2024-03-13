<?php
declare(strict_types=1);

namespace Domains\Auth\Contracts;

interface RegisterNewUserContract
{
    public function __invoke(string $name, string $email, string $password);
}
