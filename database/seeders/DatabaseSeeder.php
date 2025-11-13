<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => 'admin123',
            'role' => Role::ADMIN,
        ]);

        Gateway::factory()->create([
            'name' => 'Pagamentos Corp',
            'class_name' => 'PagamentosCorp',
            'priority' => 1,
        ]);

        Gateway::factory()->create([
            'name' => 'Payments Enterprise',
            'class_name' => 'PaymentsEnterprise',
            'priority' => 2,
        ]);

        // Client::factory(10)->create();

        Product::factory(5)->sequence(
            ['name' => 'Ball'],
            ['name' => 'Doll'],
            ['name' => 'Gaming Console'],
            ['name' => 'Hat'],
            ['name' => 'Christmas Tree'],
        )->create();
    }
}
