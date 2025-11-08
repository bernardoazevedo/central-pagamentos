<?php

namespace Tests\Unit;

use App\Models\Gateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GatewayTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_gateway(): void
    {
        $validGateway = Gateway::factory()->create([
            'name' => 'Valid Gateway Test',
            'class_name' => 'PagamentosCorp',
            'is_active' => true,
            'priority' => 1,
        ]);

        $this->assertEquals('Valid Gateway Test', $validGateway['name']);
    }

    public function test_update_gateway(): void
    {
        $gateway = Gateway::factory()->create([
            'name' => 'Valid Gateway Test',
            'class_name' => 'PagamentosCorp',
            'is_active' => true,
            'priority' => 1,
        ]);

        $gateway->is_active = false;
        $gateway->priority  = 6;

        $gateway->save();

        $this->assertEquals(false, $gateway->is_active);
        $this->assertEquals(6, $gateway->priority);
    }
}
