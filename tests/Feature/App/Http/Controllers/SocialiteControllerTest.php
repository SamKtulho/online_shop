<?php

declare(strict_types=1);

namespace Tests\Feature\App\Http\Controllers;

use App\Http\Controllers\Auth\SocialiteController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;
use Tests\TestCase;

class SocialiteControllerTest extends TestCase
{
    use RefreshDatabase;


    private function mockSocialiteCallback(string|int $externalUserId): MockInterface
    {
        $user = $this->mock(\Laravel\Socialite\Contracts\User::class, function (MockInterface $m) use ($externalUserId) {
            $m->shouldReceive('getId')->once()->andReturn($externalUserId);
            $m->shouldReceive('getName')->once()->andReturn(str()->random(10));
            $m->shouldReceive('getEmail')->once()->andReturn('alex@german.sam');
        });

        Socialite::shouldReceive('driver->user')->once()->andReturn($user);

        return $user;
    }

    private function sendRequest(string $driver): TestResponse
    {
        return $this->get(action([SocialiteController::class, 'callback'], ['driver' => $driver]));
    }

    public function test_it_creates_github_user_ok(): void
    {
        $githubUserId = str()->random(10);

        $this->assertDatabaseMissing('users',['github_id' => $githubUserId]);

        $this->mockSocialiteCallback($githubUserId);

        $this->sendRequest('github')->assertRedirect(route('home'));

        $this->assertAuthenticated();

        $this->assertDatabaseHas('users',['github_id' => $githubUserId]);
    }

}
