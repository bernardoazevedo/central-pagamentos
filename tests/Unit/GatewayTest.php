<?php

namespace Tests\Unit;

use App\Models\Gateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GatewayTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_create_gateway(): void
    {
        $validGateway = Gateway::factory()->create([
            'name' => 'Valid Gateway Test',
            'is_active' => true,
            'priority' => 1,
        ]);

        $this->assertEquals($validGateway['name'], 'Valid Gateway Test');
    }
}
