<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_hidden_category_products_are_not_listed(): void
    {
        $hiddenCategory = Category::create([
            'name' => 'Hidden Category',
            'slug' => 'hidden-category',
            'status' => false,
        ]);

        $visibleCategory = Category::create([
            'name' => 'Visible Category',
            'slug' => 'visible-category',
            'status' => true,
        ]);

        Product::create([
            'category_id' => $hiddenCategory->getKey(),
            'name' => 'Hidden Product',
            'slug' => 'hidden-product',
            'sku' => 'HP-001',
            'price' => '990000',
            'stock' => 10,
            'status' => true,
        ]);

        Product::create([
            'category_id' => $visibleCategory->getKey(),
            'name' => 'Visible Product',
            'slug' => 'visible-product',
            'sku' => 'VP-001',
            'price' => '1200000',
            'stock' => 5,
            'status' => true,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertDontSee('Hidden Product');
        $response->assertSee('Visible Product');
    }

    public function test_products_in_hidden_categories_are_not_accessible(): void
    {
        $hiddenCategory = Category::create([
            'name' => 'Hidden Category',
            'slug' => 'hidden-category',
            'status' => false,
        ]);

        $product = Product::create([
            'category_id' => $hiddenCategory->getKey(),
            'name' => 'Hidden Product',
            'slug' => 'hidden-product',
            'sku' => 'HP-001',
            'price' => '990000',
            'stock' => 10,
            'status' => true,
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertNotFound();
    }

    public function test_product_detail_renders_primary_and_ordered_gallery_images(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Gallery Product',
            'slug' => 'gallery-product',
            'sku' => 'GP-001',
            'price' => '100000',
            'stock' => 5,
            'thumbnail' => 'products/primary.jpg',
            'status' => true,
        ]);
        ProductImage::create([
            'product_id' => $product->getKey(),
            'image_path' => 'products/second.jpg',
            'sort_order' => 1,
        ]);
        ProductImage::create([
            'product_id' => $product->getKey(),
            'image_path' => 'products/third.jpg',
            'sort_order' => 2,
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('products/primary.jpg');
        $response->assertSee('products/second.jpg');
        $response->assertSee('products/third.jpg');
        $response->assertSee('aria-pressed');
        $response->assertSee('Xem ảnh 3 của Gallery Product');
    }

    public function test_product_detail_shows_a_fallback_when_no_images_exist(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);
        $product = Product::create([
            'category_id' => $category->getKey(),
            'name' => 'No Image Product',
            'slug' => 'no-image-product',
            'sku' => 'NIP-001',
            'price' => '100000',
            'stock' => 5,
            'status' => true,
        ]);

        $response = $this->get(route('products.show', $product));

        $response->assertOk();
        $response->assertSee('Ảnh sản phẩm chưa có hoặc không tải được');
        $response->assertSee('role="img"', false);
    }

    public function test_related_products_exclude_hidden_and_out_of_stock_items(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);
        $hiddenCategory = Category::create([
            'name' => 'Hidden',
            'slug' => 'hidden',
            'status' => false,
        ]);

        $currentProduct = Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Current Product',
            'slug' => 'current-product',
            'sku' => 'CP-001',
            'price' => '100000',
            'stock' => 2,
            'status' => true,
        ]);
        Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Available Related Product',
            'slug' => 'available-related-product',
            'sku' => 'ARP-001',
            'price' => '120000',
            'stock' => 1,
            'status' => true,
        ]);
        Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Out Of Stock Related Product',
            'slug' => 'out-of-stock-related-product',
            'sku' => 'OOS-001',
            'price' => '130000',
            'stock' => 0,
            'status' => true,
        ]);
        Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Hidden Related Product',
            'slug' => 'hidden-related-product',
            'sku' => 'HRP-001',
            'price' => '140000',
            'stock' => 5,
            'status' => false,
        ]);
        Product::create([
            'category_id' => $hiddenCategory->getKey(),
            'name' => 'Hidden Category Product',
            'slug' => 'hidden-category-product',
            'sku' => 'HCP-001',
            'price' => '150000',
            'stock' => 5,
            'status' => true,
        ]);

        $response = $this->get(route('products.show', $currentProduct));

        $response->assertOk();
        $response->assertSee('Available Related Product');
        $response->assertDontSee('Out Of Stock Related Product');
        $response->assertDontSee('Hidden Related Product');
        $response->assertDontSee('Hidden Category Product');
    }

    public function test_product_cards_label_out_of_stock_products_clearly(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);
        Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Out Of Stock Product',
            'slug' => 'out-of-stock-product',
            'sku' => 'OOS-002',
            'price' => '100000',
            'stock' => 0,
            'status' => true,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertOk();
        $response->assertSee('Hết hàng');
        $response->assertDontSee('Còn 0 sản phẩm');
    }

    public function test_category_filter_only_returns_products_in_the_selected_active_category(): void
    {
        $phones = Category::create([
            'name' => 'Phones',
            'slug' => 'phones',
            'status' => true,
        ]);
        $audio = Category::create([
            'name' => 'Audio',
            'slug' => 'audio',
            'status' => true,
        ]);
        $hiddenCategory = Category::create([
            'name' => 'Hidden',
            'slug' => 'hidden',
            'status' => false,
        ]);

        foreach ([
            [$phones, 'Phone Product'],
            [$audio, 'Audio Product'],
            [$hiddenCategory, 'Hidden Product'],
        ] as [$category, $name]) {
            Product::create([
                'category_id' => $category->getKey(),
                'name' => $name,
                'slug' => str($name)->slug()->toString(),
                'sku' => str($name)->upper()->replace(' ', '-')->toString(),
                'price' => '100000',
                'stock' => 5,
                'status' => true,
            ]);
        }

        $response = $this->get(route('products.index', ['category' => 'audio']));

        $response->assertOk();
        $response->assertSee('Audio Product');
        $response->assertSee('100.000₫');
        $response->assertSee('Còn 5 sản phẩm');
        $response->assertSee('Chưa có ảnh');
        $response->assertDontSee('Phone Product');
        $response->assertDontSee('Hidden Product');
        $this->assertSame(
            1,
            $response->viewData('products')->total()
        );
    }

    public function test_product_pagination_returns_each_product_once_across_pages(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);

        $createdAt = now()->subDay();
        $productIds = [];

        for ($index = 1; $index <= 13; $index++) {
            $productIds[] = Product::create([
                'category_id' => $category->getKey(),
                'name' => 'Page Product '.$index,
                'slug' => 'page-product-'.$index,
                'sku' => 'PP-'.$index,
                'price' => '100000',
                'stock' => 5,
                'status' => true,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ])->getKey();
        }

        $firstPage = $this->get(route('products.index', ['page' => 1]));
        $secondPage = $this->get(route('products.index', ['page' => 2]));
        $expectedOrder = array_reverse($productIds);

        $firstPage->assertOk();
        $secondPage->assertOk();
        $this->assertSame(
            array_slice($expectedOrder, 0, 12),
            $firstPage->viewData('products')->getCollection()->modelKeys()
        );
        $this->assertSame(
            array_slice($expectedOrder, 12),
            $secondPage->viewData('products')->getCollection()->modelKeys()
        );
    }

    public function test_product_search_matches_names_and_skus(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);

        Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Wireless Mouse',
            'slug' => 'wireless-mouse',
            'sku' => 'WM-001',
            'price' => '100000',
            'stock' => 5,
            'status' => true,
        ]);
        Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Laptop Pro',
            'slug' => 'laptop-pro',
            'sku' => 'LP-002',
            'price' => '200000',
            'stock' => 3,
            'status' => true,
        ]);

        $nameResponse = $this->get(route('products.index', ['keyword' => 'Wireless']));
        $skuResponse = $this->get(route('products.index', ['keyword' => 'LP-002']));

        $nameResponse->assertOk();
        $nameResponse->assertSee('Wireless Mouse');
        $nameResponse->assertDontSee('Laptop Pro');
        $skuResponse->assertOk();
        $skuResponse->assertSee('Laptop Pro');
        $skuResponse->assertDontSee('Wireless Mouse');
    }

    public function test_search_special_input_does_not_change_the_query_structure(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);

        Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Sensitive Product',
            'slug' => 'sensitive-product',
            'sku' => 'SP-001',
            'price' => '100000',
            'stock' => 5,
            'status' => true,
        ]);

        $response = $this->get(route('products.index', [
            'keyword' => "' OR 1=1 --",
        ]));

        $response->assertOk();
        $response->assertDontSee('Sensitive Product');
        $response->assertSee('Không tìm thấy sản phẩm');
        $this->assertSame(0, $response->viewData('products')->total());
    }

    public function test_price_and_category_filters_combine_and_survive_pagination(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);

        $createdAt = now()->subDay();
        $matchingProductIds = [];

        for ($index = 1; $index <= 13; $index++) {
            $matchingProductIds[] = Product::create([
                'category_id' => $category->getKey(),
                'name' => 'Discount Product '.$index,
                'slug' => 'discount-product-'.$index,
                'sku' => 'DP-'.$index,
                'price' => '100000',
                'sale_price' => '50000',
                'stock' => 5,
                'status' => true,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ])->getKey();
        }

        $aboveRange = Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Above Range Product',
            'slug' => 'above-range-product',
            'sku' => 'ARP-001',
            'price' => '70000',
            'stock' => 5,
            'status' => true,
        ]);
        $belowRange = Product::create([
            'category_id' => $category->getKey(),
            'name' => 'Below Range Product',
            'slug' => 'below-range-product',
            'sku' => 'BRP-001',
            'price' => '30000',
            'stock' => 5,
            'status' => true,
        ]);

        $filters = [
            'category' => 'electronics',
            'min_price' => 40000,
            'max_price' => 60000,
            'sort' => 'price_asc',
        ];
        $firstPage = $this->get(route('products.index', $filters));

        $firstPage->assertOk();
        $this->assertSame(13, $firstPage->viewData('products')->total());
        $this->assertSame(
            array_slice(array_reverse($matchingProductIds), 0, 12),
            $firstPage->viewData('products')->getCollection()->modelKeys()
        );
        $firstPage->assertDontSee($aboveRange->name);
        $firstPage->assertDontSee($belowRange->name);

        $nextPageUrl = $firstPage->viewData('products')->nextPageUrl();
        parse_str((string) parse_url($nextPageUrl, PHP_URL_QUERY), $pageQuery);

        $this->assertSame('electronics', $pageQuery['category']);
        $this->assertSame('40000', $pageQuery['min_price']);
        $this->assertSame('60000', $pageQuery['max_price']);
        $this->assertSame('price_asc', $pageQuery['sort']);
        $this->assertSame('2', $pageQuery['page']);

        $secondPage = $this->get($nextPageUrl);

        $secondPage->assertOk();
        $this->assertSame(
            array_slice(array_reverse($matchingProductIds), 12),
            $secondPage->viewData('products')->getCollection()->modelKeys()
        );
    }

    public function test_price_sorting_uses_a_stable_newest_first_tiebreaker(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);

        $createdAt = now()->subDay();
        $productIds = [];

        foreach (['First Product', 'Second Product', 'Third Product'] as $name) {
            $productIds[] = Product::create([
                'category_id' => $category->getKey(),
                'name' => $name,
                'slug' => str($name)->slug()->toString(),
                'sku' => str($name)->upper()->replace(' ', '-')->toString(),
                'price' => '100000',
                'stock' => 5,
                'status' => true,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ])->getKey();
        }

        $ascendingResponse = $this->get(route('products.index', ['sort' => 'price_asc']));
        $descendingResponse = $this->get(route('products.index', ['sort' => 'price_desc']));
        $latestResponse = $this->get(route('products.index'));

        $ascendingResponse->assertOk();
        $descendingResponse->assertOk();
        $latestResponse->assertOk();
        $this->assertSame(
            array_reverse($productIds),
            $ascendingResponse->viewData('products')->getCollection()->modelKeys()
        );
        $this->assertSame(
            array_reverse($productIds),
            $descendingResponse->viewData('products')->getCollection()->modelKeys()
        );
        $this->assertSame(
            array_reverse($productIds),
            $latestResponse->viewData('products')->getCollection()->modelKeys()
        );
    }

    public function test_best_selling_sort_counts_only_completed_order_quantities(): void
    {
        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'status' => true,
        ]);

        $createdAt = now()->subDay();
        $productIds = [];

        foreach (['Top Seller', 'Runner Up', 'Cancelled Sale', 'Pending Sale'] as $name) {
            $productIds[] = Product::create([
                'category_id' => $category->getKey(),
                'name' => $name,
                'slug' => str($name)->slug()->toString(),
                'sku' => str($name)->upper()->replace(' ', '-')->toString(),
                'price' => '100000',
                'stock' => 5,
                'status' => true,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ])->getKey();
        }

        foreach ([
            [Order::STATUS_COMPLETED, 0, 5],
            [Order::STATUS_COMPLETED, 1, 3],
            [Order::STATUS_CANCELLED, 2, 20],
            [Order::STATUS_PENDING, 3, 30],
        ] as $index => [$status, $productIndex, $quantity]) {
            $order = Order::create([
                'order_code' => 'DH-SORT-'.$index,
                'customer_name' => 'Customer',
                'email' => 'customer@example.com',
                'phone' => '0901234567',
                'shipping_address' => 'Test address',
                'subtotal' => '100.00',
                'shipping_fee' => '0.00',
                'total' => '100.00',
                'payment_method' => 'cod',
                'payment_status' => $status === Order::STATUS_COMPLETED ? 'paid' : 'unpaid',
                'status' => $status,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            OrderItem::create([
                'order_id' => $order->getKey(),
                'product_id' => $productIds[$productIndex],
                'product_name' => 'Product '.$productIndex,
                'price' => '100.00',
                'quantity' => $quantity,
                'subtotal' => '100.00',
            ]);
        }

        $response = $this->get(route('products.index', ['sort' => 'best_selling']));

        $response->assertOk();
        $response->assertSee('Bán chạy');
        $this->assertSame(
            [$productIds[0], $productIds[1], $productIds[3], $productIds[2]],
            $response->viewData('products')->getCollection()->modelKeys()
        );
    }
}
