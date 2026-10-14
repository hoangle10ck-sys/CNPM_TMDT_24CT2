<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    public function test_admin_can_create_a_product_with_server_generated_slug(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'category_id' => $category->getKey(),
            'name' => 'Sample Laptop Pro',
            'sku' => 'LAPTOP-001',
            'price' => '25000000.00',
            'sale_price' => '23900000.00',
            'stock' => 4,
            'status' => true,
            'is_featured' => true,
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'category_id' => $category->getKey(),
            'name' => 'Sample Laptop Pro',
            'slug' => 'sample-laptop-pro',
            'sku' => 'LAPTOP-001',
            'stock' => 4,
            'status' => true,
            'is_featured' => true,
        ]);
    }

    public function test_customer_cannot_create_an_admin_product(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->post(route('admin.products.store'), []);

        $response->assertForbidden();
        $this->assertDatabaseCount('products', 0);
    }

    public function test_admin_upload_rejects_svg_files(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.products.store'), [
            'category_id' => $category->getKey(),
            'name' => 'SVG Upload Attempt',
            'price' => '100000',
            'stock' => 1,
            'thumbnail' => UploadedFile::fake()->create(
                'payload.svg',
                1,
                'image/svg+xml'
            ),
        ]);

        $response->assertSessionHasErrors('thumbnail');
        $this->assertDatabaseMissing('products', [
            'name' => 'SVG Upload Attempt',
        ]);
        $this->assertSame([], Storage::disk('public')->allFiles('products'));
    }
}
