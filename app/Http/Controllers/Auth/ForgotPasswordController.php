<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\PasswordResetFormRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as FactoryAlias;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as ApplicationAlias;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController
{
    public function page(): View|ApplicationAlias|FactoryAlias|Application
    {
        return view('auth.forgot-password');
    }

    public function handle(PasswordResetFormRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            flash()->info(__($status));
            return back();
        }

        return back()->withErrors(['email' => __($status)]);
    }
}
