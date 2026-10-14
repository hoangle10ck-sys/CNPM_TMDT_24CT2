<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_their_cart_item(): void
    {
        $user = User::factory()->create();
        $cartItem = $this->createCartItem($user, 3, 1);

        $response = $this->actingAs($user)
            ->from(route('cart.index'))
            ->patch(route('cart.update', $cartItem), [
                'quantity' => 2,
            ]);

        $response
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('success', 'Đã cập nhật số lượng sản phẩm.');
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->getKey(),
            'quantity' => 2,
        ]);
    }

    public function test_user_cannot_update_another_users_cart_item(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $cartItem = $this->createCartItem($owner, 3, 1);

        $response = $this->actingAs($otherUser)
            ->patch(route('cart.update', $cartItem), [
                'quantity' => 2,
            ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->getKey(),
            'quantity' => 1,
        ]);
    }

    public function test_user_cannot_delete_another_users_cart_item(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $cartItem = $this->createCartItem($owner, 3, 1);

        $response = $this->actingAs($otherUser)
            ->delete(route('cart.destroy', $cartItem));

        $response->assertForbidden();
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->getKey(),
            'quantity' => 1,
        ]);
    }

    public function test_user_cannot_set_cart_quantity_above_available_stock(): void
    {
        $user = User::factory()->create();
        $cartItem = $this->createCartItem($user, 3, 1);

        $response = $this->actingAs($user)
            ->from(route('cart.index'))
            ->patch(route('cart.update', $cartItem), [
                'quantity' => 4,
            ]);

        $response->assertSessionHasErrors([
            'quantity' => 'Chỉ còn 3 sản phẩm trong kho.',
        ]);
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->getKey(),
            'quantity' => 1,
        ]);
    }

    private function createCartItem(
        User $user,
        int $stock,
        int $quantity
    ): CartItem {
        $category = Category::create([
            'name' => 'Phones',
            'slug' => 'phones',
            'status' => true,
        ]);

        $product = Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Test Phone',
            'slug' => 'test-phone',
            'sku' => 'TP-001',
            'price' => '100.00',
            'stock' => $stock,
            'status' => true,
        ]);

        $cart = Cart::create([
            'user_id' => $user->getKey(),
        ]);

        return CartItem::create([
            'cart_id' => $cart->getKey(),
            'product_id' => $product->getKey(),
            'quantity' => $quantity,
        ]);
    }
}
