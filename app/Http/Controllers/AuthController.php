<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordResetFormRequest;
use App\Http\Requests\PasswordUpdateFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Http\Requests\SignInFormRequest;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse as RedirectResponseAlias;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function signup()
    {
        return view('auth.signup');
    }

    public function signin(SignInFormRequest $request): RedirectResponse
    {
        if (!auth()->attempt($request->validated())) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function store(SignUpFormRequest $request): RedirectResponse
    {
        $user = User::query()->create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password'))
        ]);

        event(new Registered($user));
        auth()->login($user);

        return redirect()->intended(route('home'));
    }

    /**
     * Log the user out of the application.
     */
    public function logout(): RedirectResponse
    {
        auth()->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect(route('home'));
    }

    /**
     * @return View|Application|Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function forgotPassword(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('auth.forgot-password');
    }

    public function passwordEmail(PasswordResetFormRequest $request): RedirectResponse
    {
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['message' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function passwordReset(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function passwordUpdate(PasswordUpdateFormRequest $request)
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

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('message', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function githubRedirect(): RedirectResponseAlias|RedirectResponse
    {
        return Socialite::driver('github')->redirect();
    }

    public function githubCallback(): RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::query()->updateOrCreate([
            'github_id' => $githubUser->id,
        ], [
            'name' => $githubUser->name,
            'email' => $githubUser->email,
            'password' => bcrypt(str()->random(25)),
        ]);

        auth()->login($user);

        return redirect()->intended(route('home'));
    }
}
