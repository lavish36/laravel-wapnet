<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_products()
    {
        Product::factory(5)->create();

        $response = $this->get('/api/products');
        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'description',
                'price',
                'image_url',
                'created_at',
                'updated_at',
            ],
        ]);
        
    }

    public function test_can_get_single_product()
    {
        $product = Product::factory()->create();

        $response = $this->get('/api/products/' . $product->id);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'id',
            'name',
            'description',
            'price',
            'image_url',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_can_add_product_to_cart()
    {
        $user = User::factory()->create();
       
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->post('/api/carts', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'is_checked_out',
            'total_price',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_can_checkout_cart()
    {
        $user = User::factory()->create();
        $cart = $user->carts()->create(['is_checked_out' => false]);

        $response = $this->actingAs($user)->post('/api/carts/' . $cart->id . '/checkout');

        $response->assertStatus(200);
    }


}
