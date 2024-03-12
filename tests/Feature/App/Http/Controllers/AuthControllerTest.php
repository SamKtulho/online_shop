<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Requests\PasswordResetFormRequest;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendNewUserEmailListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;


class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_signup_page_is_ok()
    {
        $this->get(action([AuthController::class, 'signup']))
            ->assertOk()
            ->assertViewIs('auth.signup');
    }

    public function test_index_page_is_ok()
    {
        $this->get(action([AuthController::class, 'index']))
            ->assertOk()
            ->assertViewIs('auth.index');
    }

    public function test_signin_user_is_ok()
    {
        $password = '123456789';
        $user = User::factory()->create([
            'email' => 'sam@german.com',
            'password' => bcrypt($password)
        ]);

        $request = SignUpFormRequest::factory()->create([
            'email' => $user->email,
            'password' => $password
        ]);

        $this->post(action([AuthController::class, 'signin']), $request)
            ->assertValid()
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);

    }

    /**
     * @return void
     */
    public function test_it_store_user_ok(): void
    {
        Notification::fake();
        Event::fake();

        $data = SignUpFormRequest::factory()->create(['email' => 'test@sam.com']);

        $this->assertDatabaseMissing('users', ['email' => $data['email']]);

        $response = $this
            ->post(action([AuthController::class, 'store']), $data);

        $response->assertValid();

        $this->assertDatabaseHas('users', ['email' => $data['email']]);

        $user = User::query()->where(['email' => $data['email']])->first();

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendNewUserEmailListener::class);

        $event = new Registered($user);
        $listener = new SendNewUserEmailListener();
        $listener->handle($event);

        Notification::assertSentTo($user, NewUserNotification::class);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(route('home'));
    }

    public function test_logout_user_is_ok()
    {
        $user = User::factory()->create([
            'email' => 'sam@german.com',
        ]);

        $this->actingAs($user)->delete(action([AuthController::class, 'logout']));

        $this->assertGuest();
    }

    public function test_forgot_password_page_is_ok()
    {
        $this->get(action([AuthController::class, 'forgotPassword']))
            ->assertOk()
            ->assertViewIs('auth.forgot-password');
    }

    public function test_password_reset_email_is_ok()
    {
        Notification::fake();
        Event::fake();

        $user = User::factory()->create([
            'email' => 'sam@german.com',
        ]);

        $request = PasswordResetFormRequest::factory()->create([
            'email' => $user->email
        ]);

        $response = $this->post(action([AuthController::class, 'forgotPassword']), $request);

        $response->assertValid();
    }
}
