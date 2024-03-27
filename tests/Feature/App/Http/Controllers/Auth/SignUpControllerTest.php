<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\SignUpController;
use App\Http\Requests\SignUpFormRequest;
use App\Listeners\SendNewUserEmailListener;
use App\Models\User;
use App\Notifications\NewUserNotification;
use Database\Factories\UserFactory;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    protected array $requestData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->requestData = SignUpFormRequest::factory()->create([
            'email' => 'test@sam.com'
        ]);
    }

    private function sendRequest(): TestResponse
    {
        return $this->post(action([SignUpController::class, 'handle']), $this->requestData);
    }

    /**
     * @test
     * @return void
     */
    public function test_signup_page_is_ok(): void
    {
        $this->get(action([SignUpController::class, 'page']))
            ->assertOk()
            ->assertViewIs('auth.signup');
    }

    /**
     * @return void
     */
    public function test_it_returns_ok_when_request_valid(): void
    {
        $this->sendRequest()->assertValid();
    }

    /**
     * @return void
     */
    public function test_it_returns_error_when_bad_request_password_data(): void
    {
        $this->requestData['password_'] = '123';
        $this->requestData['password_confirmation'] = 'abc';

        $this->sendRequest()->assertInvalid(['password']);
    }

    /**
     * @return void
     */
    public function test_it_creates_new_user(): void
    {
        $this->assertDatabaseMissing('users', ['email' => $this->requestData['email']]);
        $this->sendRequest();
        $this->assertDatabaseHas('users', ['email' => $this->requestData['email']]);
    }

    /**
     * @return void
     */
    public function test_it_returns_error_for_not_unique_email(): void
    {
        UserFactory::new()->create(['email' => $this->requestData['email']]);
        $this->assertDatabaseHas('users', ['email' => $this->requestData['email']]);
        $this->sendRequest()->assertInvalid(['email']);
    }

    /**
     * @return void
     */
    public function test_it_dispatched_event_and_listener(): void
    {
        Event::fake();
        $this->sendRequest();

        Event::assertDispatched(Registered::class);
        Event::assertListening(Registered::class, SendNewUserEmailListener::class);
    }

    /**
     * @return void
     */
    public function test_it_sent_notification(): void
    {
        // Notification faked in parent setUp

        $this->sendRequest();

        $user = User::query()->where(['email' => $this->requestData['email']])->first();
        Notification::assertSentTo($user, NewUserNotification::class);
    }

    /**
     * @return void
     */
    public function test_it_redirected_authenticated_user_on_main_page(): void
    {
        $this->sendRequest()->assertRedirect(route('home'));;
        $user = User::query()->where(['email' => $this->requestData['email']])->first();
        $this->assertAuthenticatedAs($user);
    }

}
