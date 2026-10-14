<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_creates_order_and_decrements_stock(): void
    {
        $user = User::factory()->create();
        $cartItem = $this->createCartItem($user, 5, 2);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'customer_name' => 'Nguyen Van A',
            'email' => 'customer@example.com',
            'phone' => '0901234567',
            'shipping_address' => '1 Nguyen Hue, Ho Chi Minh City',
            'payment_method' => 'cod',
        ]);

        $order = Order::query()->sole();

        $response->assertRedirectToRoute('checkout.success', [
            'order' => $order->getKey(),
        ]);
        $response->assertSessionHas('success', 'Đặt hàng thành công.');
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->getKey(),
            'subtotal' => '160.00',
            'total' => '160.00',
            'status' => Order::STATUS_PENDING,
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->getKey(),
            'product_id' => $cartItem->product_id,
            'price' => '80.00',
            'quantity' => 2,
            'subtotal' => '160.00',
        ]);
        $this->assertDatabaseHas('products', [
            'id' => $cartItem->getAttribute('product_id'),
            'stock' => 3,
        ]);
        $this->assertDatabaseMissing('cart_items', [
            'id' => $cartItem->id,
        ]);
    }

    public function test_repeated_checkout_submission_does_not_create_a_duplicate_order(): void
    {
        $user = User::factory()->create();
        $cartItem = $this->createCartItem($user, 5, 2);
        $checkout = [
            'customer_name' => 'Nguyen Van A',
            'email' => 'customer@example.com',
            'phone' => '0901234567',
            'shipping_address' => '1 Nguyen Hue, Ho Chi Minh City',
            'payment_method' => 'cod',
        ];

        $this->actingAs($user)
            ->post(route('checkout.store'), $checkout)
            ->assertRedirect();

        $this->post(route('checkout.store'), $checkout)
            ->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);
        $this->assertDatabaseHas('products', [
            'id' => $cartItem->getAttribute('product_id'),
            'stock' => 3,
        ]);
    }

    public function test_checkout_rejects_order_total_above_decimal_column_limit(): void
    {
        $user = User::factory()->create();
        $cartItem = $this->createCartItem(
            $user,
            2,
            2,
            '5000000000000.00',
            null
        );

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'customer_name' => 'Nguyen Van A',
            'email' => 'customer@example.com',
            'phone' => '0901234567',
            'shipping_address' => '1 Nguyen Hue, Ho Chi Minh City',
            'payment_method' => 'cod',
        ]);

        $response->assertSessionHasErrors([
            'cart' => 'Tổng tiền đơn hàng vượt giới hạn cho phép.',
        ]);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('products', [
            'id' => $cartItem->getAttribute('product_id'),
            'stock' => 2,
        ]);
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->getKey(),
            'quantity' => 2,
        ]);
    }

    public function test_insufficient_stock_keeps_order_stock_and_cart_unchanged(): void
    {
        $user = User::factory()->create();
        $cartItem = $this->createCartItem($user, 1, 2);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'customer_name' => 'Nguyen Van A',
            'email' => 'customer@example.com',
            'phone' => '0901234567',
            'shipping_address' => '1 Nguyen Hue, Ho Chi Minh City',
            'payment_method' => 'cod',
        ]);

        $response->assertSessionHasErrors([
            'cart' => 'Sản phẩm Test Phone chỉ còn 1 sản phẩm.',
        ]);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);
        $this->assertDatabaseHas('products', [
            'id' => $cartItem->getAttribute('product_id'),
            'stock' => 1,
        ]);
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->getKey(),
            'quantity' => 2,
        ]);
    }

    public function test_checkout_rejects_products_from_hidden_categories(): void
    {
        $user = User::factory()->create();
        $cartItem = $this->createCartItem($user, 5, 1, '100.00', '80.00', false);

        $response = $this->actingAs($user)->post(route('checkout.store'), [
            'customer_name' => 'Nguyen Van A',
            'email' => 'customer@example.com',
            'phone' => '0901234567',
            'shipping_address' => '1 Nguyen Hue, Ho Chi Minh City',
            'payment_method' => 'cod',
        ]);

        $response->assertSessionHasErrors([
            'cart' => 'Có sản phẩm không còn được bán.',
        ]);
        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseHas('cart_items', [
            'id' => $cartItem->getKey(),
            'quantity' => 1,
        ]);
    }

    public function test_checkout_calculates_decimal_prices_exactly(): void
    {
        $user = User::factory()->create();
        $this->createCartItem($user, 5, 3, '19.99', null);

        $this->actingAs($user)->post(route('checkout.store'), [
            'customer_name' => 'Nguyen Van A',
            'email' => 'customer@example.com',
            'phone' => '0901234567',
            'shipping_address' => '1 Nguyen Hue, Ho Chi Minh City',
            'payment_method' => 'cod',
        ]);

        $order = Order::query()->sole();

        $this->assertSame('59.97', (string) $order->getAttribute('subtotal'));
        $this->assertSame('59.97', (string) $order->getAttribute('total'));
        $this->assertSame('19.99', $order->items()->sole()->price);
        $this->assertSame('59.97', $order->items()->sole()->subtotal);
    }

    public function test_checkout_ignores_client_supplied_prices_and_totals(): void
    {
        $user = User::factory()->create();
        $this->createCartItem($user, 2, 2, '25.50', null);

        $this->actingAs($user)->post(route('checkout.store'), [
            'customer_name' => 'Nguyen Van A',
            'email' => 'customer@example.com',
            'phone' => '0901234567',
            'shipping_address' => '1 Nguyen Hue, Ho Chi Minh City',
            'payment_method' => 'cod',
            'price' => '0.01',
            'subtotal' => '0.01',
            'total' => '0.01',
        ]);

        $order = Order::query()->sole();

        $this->assertSame('51.00', (string) $order->getAttribute('subtotal'));
        $this->assertSame('51.00', (string) $order->getAttribute('total'));
        $this->assertSame('25.50', $order->items()->sole()->price);
    }

    private function createCartItem(
        User $user,
        int $stock,
        int $quantity,
        string $price = '100.00',
        ?string $salePrice = '80.00',
        bool $categoryActive = true
    ): CartItem {
        $category = Category::create([
            'name' => 'Phones',
            'slug' => 'phones',
            'status' => $categoryActive,
        ]);

        $product = Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Test Phone',
            'slug' => 'test-phone',
            'sku' => 'TP-001',
            'price' => $price,
            'sale_price' => $salePrice,
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
