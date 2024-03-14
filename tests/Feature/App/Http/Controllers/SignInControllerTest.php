<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\Auth\SignInController;
use App\Http\Requests\SignInFormRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SignInControllerTest extends TestCase
{
    use RefreshDatabase;

    protected array $requestData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->requestData = SignInFormRequest::factory()->create([
            'email' => 'sam@german.com',
            'password' => '123456789'
        ]);
    }

    private function sendRequest(): TestResponse
    {
        return $this->post(action([SignInController::class, 'handle']), $this->requestData);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'email' => $this->requestData['email'],
            'password' => bcrypt($this->requestData['password'])
        ]);
    }

    public function test_login_page_is_ok(): void
    {
        $this->get(action([SignInController::class, 'page']))
            ->assertOk()
            ->assertViewIs('auth.index');
    }

    public function test_it_validate_request(): void
    {
        $this->createUser();
        $this->sendRequest()->assertValid();
    }

    public function test_it_send_error_if_user_not_exists(): void
    {
        $this->createUser();

        $this->requestData = [
            'email' => "not@existed.user",
            'password' => str()->random(10)
        ];

        $this->sendRequest()->assertInvalid('email');
    }

    public function test_it_logged_in_success_and_redirected_on_main_page(): void
    {
        $user = $this->createUser();

        $this->sendRequest()->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_logout_user_is_ok(): void
    {
        $user = User::factory()->create([
            'email' => 'sam@german.com',
        ]);

        $this->actingAs($user)->delete(action([SignInController::class, 'logout']));

        $this->assertGuest();
    }

}
