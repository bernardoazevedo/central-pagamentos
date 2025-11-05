<?php

namespace Database\Seeders;

use App\Models\Gateway;
use Illuminate\Database\Seeder;

class GatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gateway::factory()->create([
            'name' => 'Pagamentos Corp',
            'priority' => 1,
        ]);

        Gateway::factory()->create([
            'name' => 'Payments Enterprise',
            'priority' => 2,
        ]);
    }
}
