<?php

declare(strict_types=1);

namespace Domains\Auth\Actions;

use App\Models\User;
use Domains\Auth\Contracts\RegisterNewUserContract;
use Illuminate\Auth\Events\Registered;

class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(string $name, string $email, string $password)
    {
        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);

        event(new Registered($user));
        auth()->login($user);
    }
}