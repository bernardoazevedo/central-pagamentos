<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_list_products_request(): void
    {
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
}
