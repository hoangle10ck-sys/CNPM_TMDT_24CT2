<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laptop',
                'slug' => 'laptop',
                'description' => 'Laptop học tập, văn phòng và chơi game.',
                'status' => true,
            ],
            [
                'name' => 'Điện thoại',
                'slug' => 'dien-thoai',
                'description' => 'Điện thoại thông minh chính hãng.',
                'status' => true,
            ],
            [
                'name' => 'Tai nghe',
                'slug' => 'tai-nghe',
                'description' => 'Tai nghe có dây và tai nghe không dây.',
                'status' => true,
            ],
            [
                'name' => 'Phụ kiện',
                'slug' => 'phu-kien',
                'description' => 'Phụ kiện công nghệ và thiết bị điện tử.',
                'status' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}