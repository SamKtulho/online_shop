<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as RedirectResponseAlias;

class SocialiteController
{
    public function redirect(string $driver): RedirectResponseAlias|RedirectResponse
    {
        return Socialite::driver($driver)->redirect();
    }

    public function callback(string $driver): RedirectResponse
    {
        $socialiteUser = Socialite::driver($driver)->user();

        $user = User::query()->updateOrCreate([
            $driver. '_id' => $socialiteUser->getId(),
        ], [
            'name' => $socialiteUser->getName(),
            'email' => $socialiteUser->getEmail(),
            'password' => bcrypt(str()->random(25)),
        ]);

        auth()->login($user);

        return redirect()->intended(route('home'));
    }
}
