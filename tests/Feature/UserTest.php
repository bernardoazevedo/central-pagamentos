<?php

namespace Tests\Feature;

use App\Enums\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_request(): void
    {
        $userValues = [
            'name' => 'New User Test',
            'email' => 'user@user.com',
            'password' => '@123User',
            'password_confirmation' => '@123User',
            'role' => Role::FINANCE,
        ];

        $response = $this->json('post', 'api/user', $userValues)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('id')
                    ->where('name', $userValues['name'])
                    ->where('email', $userValues['email'])
                    ->where('role', $userValues['role'])
        );
    }
}
