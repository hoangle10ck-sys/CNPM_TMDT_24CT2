<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_confirm_pending_order(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $order = $this->createOrder();

        $response = $this->actingAs($admin)->patch(
            route('admin.orders.update', $order),
            ['status' => Order::STATUS_CONFIRMED]
        );

        $response->assertSessionHas('success', 'Cập nhật trạng thái đơn hàng thành công.');
        $this->assertDatabaseHas('orders', [
            'id' => $order->getKey(),
            'status' => Order::STATUS_CONFIRMED,
        ]);
    }

    public function test_admin_cannot_skip_status_transitions(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $order = $this->createOrder();

        $response = $this->actingAs($admin)->patch(
            route('admin.orders.update', $order),
            ['status' => Order::STATUS_COMPLETED]
        );

        $response->assertSessionHas('error', 'Không thể chuyển sang trạng thái đã chọn.');
        $this->assertDatabaseHas('orders', [
            'id' => $order->getKey(),
            'status' => Order::STATUS_PENDING,
        ]);
    }

    private function createOrder(): Order
    {
        $user = User::factory()->create();
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
            'sale_price' => '90.00',
            'stock' => 10,
            'status' => true,
        ]);

        $order = Order::create([
            'user_id' => $user->getKey(),
            'order_code' => 'DH-TEST-001',
            'customer_name' => 'Customer Name',
            'email' => 'customer@example.com',
            'phone' => '0901234567',
            'shipping_address' => 'Some address',
            'subtotal' => '90.00',
            'shipping_fee' => '0.00',
            'total' => '90.00',
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
            'status' => Order::STATUS_PENDING,
        ]);

        OrderItem::create([
            'order_id' => $order->getKey(),
            'product_id' => $product->getKey(),
            'product_name' => $product->name,
            'product_sku' => $product->sku,
            'price' => '90.00',
            'quantity' => 1,
            'subtotal' => '90.00',
        ]);

        return $order;
    }
}
