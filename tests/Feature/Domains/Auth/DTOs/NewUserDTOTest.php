<?php

namespace Tests\Feature\Domains\Auth\DTOs;

use App\Http\Requests\SignUpFormRequest;
use Domains\Auth\DTOs\NewUserDTO;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class NewUserDTOTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_created_DTO_from_request()
    {
        $dto = NewUserDTO::fromRequest(new SignUpFormRequest([
            'name' => 'Sam',
            'email' => 'sam@mail.ru',
            'password' => 'password'
        ]));

        $this->assertInstanceOf(NewUserDTO::class, $dto);

    }

}
