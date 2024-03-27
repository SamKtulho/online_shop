<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Requests\SignUpFormRequest;
use Domains\Auth\Contracts\RegisterNewUserContract;
use Domains\Auth\DTOs\NewUserDTO;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory as FactoryAlias;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application as ApplicationAlias;
use Illuminate\Http\RedirectResponse;

class SignUpController
{
    public function page(): View|ApplicationAlias|FactoryAlias|Application
    {
        return view('auth.signup');
    }

    public function handle(SignUpFormRequest $request, RegisterNewUserContract $action): RedirectResponse
    {
        $action(NewUserDTO::fromRequest($request));

        return redirect()->intended(route('home'));
    }
}
