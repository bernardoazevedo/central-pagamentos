<?php

namespace Tests\Feature;

use App\Models\Gateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class GatewayTest extends TestCase
{
    use RefreshDatabase;

    public function test_update_gateway_request(): void
    {
        $gateway = Gateway::factory()->create([
            'name' => 'Valid Gateway Test',
            'is_active' => true,
            'priority' => 1,
        ]);

        $response = $this->patch(
            "/api/gateway/{$gateway->id}",
            [
                'is_active' => false,
                'priority' => 3,
            ]
        );
        $response->assertStatus(Response::HTTP_OK);

        $gateway->refresh();

        $this->assertEquals(false, $gateway->is_active);
        $this->assertEquals(3, $gateway->priority);
    }
}
