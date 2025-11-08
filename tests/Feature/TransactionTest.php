<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Gateway;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_transaction_request(): void
    {
        $client = Client::factory()->create([
            'name' => 'A new client',
            'email' => "new@new.com",
        ]);
        $gateway = Gateway::factory()->create([
            'name' => 'Valid Gateway Test',
            'is_active' => true,
            'priority' => 1,
        ]);

        $transactionValues = [
            'clients_id' => $client->id,
            'gateways_id' => $gateway->id,
            'external_id' => '6714h94e-1fe6-7f38-b9e3-cf5413159519',
            'status' => 'paid',
            'amount' => 21000,
            'card_last_numbers' => '8888',
        ];
        $transaction = Transaction::factory()->create($transactionValues);

        $response = $this->json('get', "api/transaction/{$transaction->id}", [])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $transaction->id)
                    ->where('external_id', $transactionValues['external_id'])
                    ->where('status', $transactionValues['status'])
                    ->where('amount', $transactionValues['amount'])
                    ->where('card_last_numbers', $transactionValues['card_last_numbers'])
                    ->has('client')
                    ->has('gateway')
                    ->has('products')
            );
    }

    public function test_list_transactions_request(): void
    {
        $client = Client::factory()->create([
            'name' => 'A new client',
            'email' => "new@new.com",
        ]);
        $gateway = Gateway::factory()->create([
            'name' => 'Valid Gateway Test',
            'is_active' => true,
            'priority' => 1,
        ]);

        $transactionValues = [
            'clients_id' => $client->id,
            'gateways_id' => $gateway->id,
            'external_id' => '6714h94e-1fe6-7f38-b9e3-cf5413159519',
            'status' => 'paid',
            'amount' => 21000,
            'card_last_numbers' => '8888',
        ];
        $transaction = Transaction::factory()->create($transactionValues);

        $response = $this->json('get', 'api/transaction', [])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(1)
                    ->first(fn (AssertableJson $json) =>
                        $json->where('id', $transaction->id)
                            ->where('external_id', $transactionValues['external_id'])
                            ->where('status', $transactionValues['status'])
                            ->where('amount', $transactionValues['amount'])
                            ->where('card_last_numbers', $transactionValues['card_last_numbers'])
                            ->has('client')
                            ->has('gateway')
                            ->has('products')
                    )
            );
    }
}
