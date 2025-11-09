<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_products_request(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $product = Product::factory()->create([
            'name' => 'Test Product',
            'amount' => 1999,
        ]);

        $response = $this->json('get', 'api/product', [])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has(1)
                    ->first(fn (AssertableJson $json) =>
                        $json->where('id', $product->id)
                            ->where('name', 'Test Product')
                            ->where('amount', 1999)
                    )
            );
    }

    public function test_list_empty_products_request(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json('get', "api/product", [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_create_product_request(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $productValues = [
            'name' => 'New Product Test',
            'amount' => 5599,
        ];

        $response = $this->json('post', 'api/product', $productValues)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('id')
                    ->where('name', $productValues['name'])
                    ->where('amount', $productValues['amount'])
        );
    }

    public function test_get_product_request(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $productValues = [
            'name' => 'New Product Test',
            'amount' => 5599,
        ];
        $product = Product::factory()->create($productValues);

        $response = $this->json('get', "api/product/{$product->id}", [])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $product->id)
                    ->where('name', $productValues['name'])
                    ->where('amount', $productValues['amount'])
        );
    }

    public function test_get_not_created_product_request(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json('get', "api/product/100", [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_update_product_request(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $productValues = [
            'name' => 'New Product Test',
            'amount' => 5599,
        ];
        $product = Product::factory()->create($productValues);

        $productNewValues = [
            'name' => 'Name Updated',
            'amount' => 12050,
        ];
        $response = $this->json('patch', "api/product/{$product->id}", $productNewValues)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('id', $product->id)
                    ->where('name', $productNewValues['name'])
                    ->where('amount', $productNewValues['amount'])
        );
    }

    public function test_update_not_created_product_request(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json('patch', "api/product/100", [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_delete_product_request(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $product = Product::factory()->create();
        $response = $this->json('delete', "api/product/{$product->id}", [])
            ->assertStatus(Response::HTTP_OK);
    }

    public function test_delete_not_created_product_request(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $response = $this->json('delete', "api/product/100", [])
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
