<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientShoppingTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_dashboard_shows_shop_search_and_cart_actions(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::create([
            'name' => 'Air Test Runner',
            'description' => 'Client dashboard product',
            'price' => 25000,
        ]);

        $product->sizes()->create([
            'size' => '42',
            'stock' => 4,
        ]);

        $response = $this->actingAs($client)->get(route('client.dashboard'));

        $response
            ->assertOk()
            ->assertSee('Rechercher une sneaker')
            ->assertSee('Voir la boutique')
            ->assertSee('Voir le panier')
            ->assertSee('Air Test Runner')
            ->assertSee('Ajouter au panier');
    }

    public function test_client_can_add_available_product_to_cart(): void
    {
        $client = User::factory()->create(['role' => 'client']);
        $product = Product::create([
            'name' => 'Cart Test Sneaker',
            'description' => 'Cart product',
            'price' => 30000,
        ]);

        $size = $product->sizes()->create([
            'size' => '41',
            'stock' => 3,
        ]);

        $response = $this->actingAs($client)->post(route('cart.add'), [
            'product_id' => $product->id,
            'size_id' => $size->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $this->assertArrayHasKey($product->id.'-'.$size->id, session('cart'));
    }
}
