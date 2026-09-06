<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $laptop = Category::where('slug', 'laptop')->firstOrFail();
        $phone = Category::where('slug', 'dien-thoai')->firstOrFail();
        $headphone = Category::where('slug', 'tai-nghe')->firstOrFail();
        $accessory = Category::where('slug', 'phu-kien')->firstOrFail();

        $products = [
            [
                'category_id' => $laptop->id,
                'name' => 'Laptop ASUS Vivobook 15',
                'slug' => 'laptop-asus-vivobook-15',
                'sku' => 'LAP-ASUS-001',
                'price' => 15990000,
                'sale_price' => 14990000,
                'stock' => 15,
                'thumbnail' => null,
                'description' => 'Laptop phù hợp cho học tập và làm việc văn phòng.',
                'is_featured' => true,
                'status' => true,
            ],
            [
                'category_id' => $laptop->id,
                'name' => 'Laptop Acer Aspire 5',
                'slug' => 'laptop-acer-aspire-5',
                'sku' => 'LAP-ACER-001',
                'price' => 16990000,
                'sale_price' => 15490000,
                'stock' => 10,
                'thumbnail' => null,
                'description' => 'Laptop hiệu năng tốt, thiết kế mỏng nhẹ.',
                'is_featured' => true,
                'status' => true,
            ],
            [
                'category_id' => $phone->id,
                'name' => 'Samsung Galaxy A55',
                'slug' => 'samsung-galaxy-a55',
                'sku' => 'PHONE-SS-001',
                'price' => 9990000,
                'sale_price' => 9490000,
                'stock' => 20,
                'thumbnail' => null,
                'description' => 'Điện thoại màn hình đẹp, camera sắc nét.',
                'is_featured' => true,
                'status' => true,
            ],
            [
                'category_id' => $phone->id,
                'name' => 'Xiaomi Redmi Note 13',
                'slug' => 'xiaomi-redmi-note-13',
                'sku' => 'PHONE-XM-001',
                'price' => 5290000,
                'sale_price' => 4990000,
                'stock' => 25,
                'thumbnail' => null,
                'description' => 'Điện thoại pin lâu, hiệu năng ổn định.',
                'is_featured' => false,
                'status' => true,
            ],
            [
                'category_id' => $headphone->id,
                'name' => 'Tai nghe Bluetooth Sound Pro',
                'slug' => 'tai-nghe-bluetooth-sound-pro',
                'sku' => 'HEADPHONE-001',
                'price' => 790000,
                'sale_price' => 649000,
                'stock' => 30,
                'thumbnail' => null,
                'description' => 'Tai nghe Bluetooth có micro và pin 40 giờ.',
                'is_featured' => true,
                'status' => true,
            ],
            [
                'category_id' => $headphone->id,
                'name' => 'Tai nghe Gaming G5',
                'slug' => 'tai-nghe-gaming-g5',
                'sku' => 'HEADPHONE-002',
                'price' => 590000,
                'sale_price' => null,
                'stock' => 18,
                'thumbnail' => null,
                'description' => 'Tai nghe gaming âm thanh sống động và micro rõ.',
                'is_featured' => false,
                'status' => true,
            ],
            [
                'category_id' => $accessory->id,
                'name' => 'Chuột không dây M220',
                'slug' => 'chuot-khong-day-m220',
                'sku' => 'ACCESSORY-001',
                'price' => 350000,
                'sale_price' => 299000,
                'stock' => 40,
                'thumbnail' => null,
                'description' => 'Chuột không dây nhỏ gọn, hoạt động êm.',
                'is_featured' => false,
                'status' => true,
            ],
            [
                'category_id' => $accessory->id,
                'name' => 'Bàn phím cơ K87',
                'slug' => 'ban-phim-co-k87',
                'sku' => 'ACCESSORY-002',
                'price' => 890000,
                'sale_price' => 790000,
                'stock' => 16,
                'thumbnail' => null,
                'description' => 'Bàn phím cơ nhỏ gọn với hệ thống đèn LED.',
                'is_featured' => true,
                'status' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }
    }
}