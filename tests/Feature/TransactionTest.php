<?php

namespace Tests\Feature;

use App\Enums\TransactionStatus;
use App\Models\Client;
use App\Models\Gateway;
use App\Models\Product;
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
            'class_name' => 'PagamentosCorp',
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
            'class_name' => 'PagamentosCorp',
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

    public function test_create_transaction_request(): void
    {
        $gatewayValues = [
            [
                'class_name' => 'PagamentosCorp',
                'is_active' => true,
                'priority' => 2,
            ],
            [
                'class_name' => 'PaymentsEnterprise',
                'is_active' => true,
                'priority' => 1,
            ]
        ];
        foreach($gatewayValues as $eachGateway) {
            $gateway = Gateway::factory()->create($eachGateway);
            $product1 = Product::factory()->create([
                'name' => 'First Product',
                'amount' => 2000,
            ]);
            $product2 = Product::factory()->create([
                'name' => 'Second Product',
                'amount' => 5500,
            ]);

            $transactionValues = [
                'client' => [
                    'name' => 'A new client',
                    'email' => "new@new.com",
                ],
                'payment_info' => [
                    'card_numbers' => '1111222233334444',
                    'cvv' => '123',
                ],
                'products' => [
                    $product1->id,
                    $product2->id,
                ],
            ];

            $response = $this->json('post', 'api/transaction', $transactionValues)
                ->assertStatus(Response::HTTP_CREATED)
                ->assertJson(fn (AssertableJson $json) =>
                    $json->has('id')
                        ->where('status', TransactionStatus::PAID)
                        ->where('amount', $product1->amount + $product2->amount)
                );
        }
    }

    public function test_create_and_chargeback_transaction_request(): void
    {
        $gatewayValues = [
            [
                'class_name' => 'PagamentosCorp',
                'is_active' => true,
                'priority' => 2,
            ],
            [
                'class_name' => 'PaymentsEnterprise',
                'is_active' => true,
                'priority' => 1,
            ]
        ];
        foreach($gatewayValues as $eachGateway) {
            $gateway = Gateway::factory()->create($eachGateway);
            $product1 = Product::factory()->create([
                'name' => 'First Product',
                'amount' => 2000,
            ]);
            $product2 = Product::factory()->create([
                'name' => 'Second Product',
                'amount' => 5500,
            ]);

            $transactionValues = [
                'client' => [
                    'name' => 'A new client',
                    'email' => "new@new.com",
                ],
                'payment_info' => [
                    'card_numbers' => '1111222233334444',
                    'cvv' => '123',
                ],
                'products' => [
                    $product1->id,
                    $product2->id,
                ],
            ];

            // create transaction
            $response = $this->json('post', 'api/transaction', $transactionValues)
                ->assertStatus(Response::HTTP_CREATED)
                ->assertJson(fn (AssertableJson $json) =>
                    $json->has('id')
                        ->where('status', TransactionStatus::PAID)
                        ->where('amount', $product1->amount + $product2->amount)
                );

            // chargeback
            $response = $this->json('post', "api/transaction/{$response->original['id']}/chargeback", [])
                ->assertStatus(Response::HTTP_CREATED)
                ->assertJson(fn (AssertableJson $json) =>
                    $json->has('id')
                        ->where('status', TransactionStatus::CHARGED_BACK)
                        ->where('amount', $product1->amount + $product2->amount)
                );
        }
    }
}
