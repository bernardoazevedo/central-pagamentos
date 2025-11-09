<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_user_request(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );

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

    public function test_get_user_request(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );

        $userValues = [
            'name' => 'New User Test',
            'email' => 'user@user.com',
            'password' => '@123User',
            'role' => Role::FINANCE,
        ];
        $user = User::factory()->create($userValues);

        $response = $this->json('get', "api/user/{$user->id}", [])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $user->id)
                    ->where('name', $userValues['name'])
                    ->where('email', $userValues['email'])
                    ->where('role', $userValues['role'])
        );
    }

    public function test_get_not_created_user_request(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json('delete', "api/user/100", [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_list_users_request(): void
    {
        $userValues = [
            'name' => 'New User Test',
            'email' => 'user@user.com',
            'password' => '@123User',
            'role' => Role::FINANCE,
        ];
        $user = User::factory()->create($userValues);

        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json('get', 'api/user', [])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(2)
                    ->first(fn (AssertableJson $json) =>
                        $json->where('id', $user->id)
                            ->where('name', $userValues['name'])
                            ->where('email', $userValues['email'])
                            ->where('role', $userValues['role'])
                    )
            );
    }

    public function test_update_user_request(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );

        $userValues = [
            'name' => 'New User Test',
            'email' => 'user@user.com',
            'password' => '@123User',
            'role' => Role::FINANCE,
        ];
        $user = User::factory()->create($userValues);

        $userNewValues = [
            'name' => 'Name Updated',
            'email' => 'email@updated.com',
            'password' => '@newPass321',
            'password_confirmation' => '@newPass321',
            'role' => Role::USER,
        ];
        $response = $this->json('patch', "api/user/{$user->id}", $userNewValues)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $user->id)
                    ->where('name', $userNewValues['name'])
                    ->where('email', $userNewValues['email'])
                    ->where('role', $userNewValues['role'])
        );
    }

    public function test_update_not_created_user_request(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json('patch', "api/user/100", [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete_user_request(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );

        $user = User::factory()->create();

        $response = $this->json('delete', "api/user/{$user->id}", [])
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_delete_not_created_user_request(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json('delete', "api/user/100", [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
