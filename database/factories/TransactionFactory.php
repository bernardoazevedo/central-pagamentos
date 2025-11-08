<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clients_id' => 1,
            'gateways_id' => 1,
            'external_id' => fake()->uuid(),
            'status' => 'paid',
            'amount' => fake()->numberBetween(100, 100000),
            'card_last_numbers' => fake()->numberBetween(1000, 9999),
        ];
    }
}
