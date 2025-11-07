<?php

namespace Tests\Feature;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_client_request(): void
    {
        $client = Client::factory()->create([
            'name' => 'A new client',
            'email' => "new@new.com",
        ]);

        $response = $this->json('get', "api/client/{$client->id}", [])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $client->id)
                    ->where('name', 'A new client')
                    ->where('email', "new@new.com")
                    ->has('transactions')
            );
    }

    public function test_list_clients_request(): void
    {
        $client = Client::factory()->create([
            'name' => 'Mr Test',
            'email' => "test@test.com",
        ]);

        $response = $this->json('get', 'api/client', [])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(1)
                    ->first(fn (AssertableJson $json) =>
                        $json->where('id', $client->id)
                            ->where('name', 'Mr Test')
                            ->where('email', "test@test.com")
                    )
            );
    }
}
