<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cannot_create_product_in_inactive_category(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $hiddenCategory = Category::create([
            'name' => 'Hidden Category',
            'slug' => 'hidden-category',
            'status' => false,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'category_id' => $hiddenCategory->getKey(),
            'name' => 'Hidden Product',
            'sku' => 'HP-001',
            'price' => '1200000',
            'sale_price' => '1100000',
            'stock' => 10,
            'description' => 'This should fail',
            'status' => true,
            'is_featured' => false,
        ]);

        $response->assertSessionHasErrors('category_id');
        $this->assertDatabaseMissing('products', [
            'name' => 'Hidden Product',
        ]);
    }
}
