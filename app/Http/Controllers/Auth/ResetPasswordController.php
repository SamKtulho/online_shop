<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\PasswordUpdateFormRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as FactoryAlias;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as ApplicationAlias;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ResetPasswordController
{
    public function page(string $token): View|ApplicationAlias|FactoryAlias|Application
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function handle(PasswordUpdateFormRequest $request): RedirectResponse
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => bcrypt($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            flash()->info(__($status));
            return redirect()->route('login.page');
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
