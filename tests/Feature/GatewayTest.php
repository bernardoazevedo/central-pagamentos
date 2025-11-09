<?php

namespace Tests\Feature;

use App\Models\Gateway;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Tests\TestCase;

class GatewayTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_gateway_request(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );

        $gatewayValues = [
            'name' => 'Gateway Test',
            'class_name' => 'PagamentosCorp',
            'is_active' => true,
            'priority' => 1,
        ];
        $gateway = Gateway::factory()->create($gatewayValues);

        $gatewayNewValues = [
            'is_active' => false,
            'priority' => 3,
        ];

        $response = $this->json('patch', "api/gateway/{$gateway->id}", $gatewayNewValues)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $gateway->id)
                    ->where('name', $gatewayValues['name'])
                    ->where('is_active', $gatewayNewValues['is_active'])
                    ->where('priority', $gatewayNewValues['priority'])
            );
    }

    public function test_update_gateway_status_request(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );

        $gatewayValues = [
            'name' => 'Gateway Test',
            'class_name' => 'PagamentosCorp',
            'is_active' => true,
            'priority' => 1,
        ];
        $gateway = Gateway::factory()->create($gatewayValues);

        $gatewayNewValues = [
            'is_active' => false,
        ];

        $response = $this->json('patch', "api/gateway/{$gateway->id}", $gatewayNewValues)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $gateway->id)
                    ->where('name', $gatewayValues['name'])
                    ->where('is_active', $gatewayNewValues['is_active'])
                    ->where('priority', $gatewayValues['priority'])
            );
    }

    public function test_update_gateway_priority_request(): void
    {
        Passport::actingAs(
            User::factory()->create(),
            ['*']
        );

        $gatewayValues = [
            'name' => 'Gateway Test',
            'class_name' => 'PagamentosCorp',
            'is_active' => true,
            'priority' => 1,
        ];
        $gateway = Gateway::factory()->create($gatewayValues);

        $gatewayNewValues = [
            'priority' => 3,
        ];

        $response = $this->json('patch', "api/gateway/{$gateway->id}", $gatewayNewValues)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $gateway->id)
                    ->where('name', $gatewayValues['name'])
                    ->where('is_active', $gatewayValues['is_active'])
                    ->where('priority', $gatewayNewValues['priority'])
            );
    }
}
