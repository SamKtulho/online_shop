<?php

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Requests\PasswordResetFormRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;


class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_page_is_ok()
    {
        $this->get(action([ForgotPasswordController::class, 'page']))
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

        $response = $this->post(action([ForgotPasswordController::class, 'handle']), $request);

        $response->assertValid();
    }
}
