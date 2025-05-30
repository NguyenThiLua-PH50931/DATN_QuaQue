<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            [
                'seller_id' => 1,
                'category_id' => 1,
                'region_id' => 1,
                'name' => 'Phở Hà Nội',
                'slug' => Str::slug('Phở Hà Nội'),
                'description' => 'Món phở truyền thống nổi tiếng của miền Bắc.',
                'price' => 50000,
                'stock' => 100,
                'image' => 'products/pho-ha-noi.jpg',
                'origin' => 'Hà Nội',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'category_id' => 2,
                'region_id' => 2,
                'name' => 'Bún bò Huế',
                'slug' => Str::slug('Bún bò Huế'),
                'description' => 'Món bún bò nổi tiếng miền Trung, đậm đà và cay nồng.',
                'price' => 60000,
                'stock' => 80,
                'image' => 'products/bun-bo-hue.jpg',
                'origin' => 'Huế',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'seller_id' => 1,
                'category_id' => 3,
                'region_id' => 3,
                'name' => 'Cà phê sữa đá',
                'slug' => Str::slug('Cà phê sữa đá'),
                'description' => 'Thức uống đặc trưng miền Nam, cà phê pha với sữa đặc.',
                'price' => 30000,
                'stock' => 150,
                'image' => 'products/ca-phe-sua-da.jpg',
                'origin' => 'Sài Gòn',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
