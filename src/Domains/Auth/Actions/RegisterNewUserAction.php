<?php

declare(strict_types=1);

namespace Domains\Auth\Actions;

use App\Models\User;
use Domains\Auth\Contracts\RegisterNewUserContract;
use Domains\Auth\DTOs\NewUserDTO;
use Illuminate\Auth\Events\Registered;

class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(NewUserDTO $data)
    {
        $user = User::query()->create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => bcrypt($data->password)
        ]);

        event(new Registered($user));
        auth()->login($user);
    }
}
