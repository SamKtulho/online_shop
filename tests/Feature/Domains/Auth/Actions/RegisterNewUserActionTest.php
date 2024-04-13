<?php

namespace Tests\Feature\Domains\Auth\Actions;

use App\Http\Requests\SignUpFormRequest;
use Domains\Auth\Contracts\RegisterNewUserContract;
use Domains\Auth\DTOs\NewUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class RegisterNewUserActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_created_new_user()
    {
        $email = 'sam@testmail.org';

        $this->assertDatabaseMissing('users', ['email' => $email]);

        $action = app(RegisterNewUserContract::class);

        $action(NewUserDTO::make('sam', $email, '123456789'));

        $this->assertDatabaseHas('users', ['email' => $email]);
    }

}
