<?php

namespace Database\Seeders;

use App\Models\admin\Product; // Hoặc App\Models\BE\Product nếu đúng với project của bạn
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductsTableSeeder extends Seeder
{
   public function run()
{
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    DB::table('products')->truncate(); // Xóa toàn bộ dữ liệu và reset AUTO_INCREMENT
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $now = Carbon::now();

    $products = [
        [
            'category_id' => 2,
            'region_id' => 1,
            'name' => 'Đặc sản mắm tôm Hạ Long',
            'slug' => Str::slug('Đặc sản mắm tôm Hạ Long'),
            'description' => 'Mắm tôm truyền thống ngon tuyệt hảo của vùng biển Hạ Long.',
            'image' => 'products/mam-tom-ha-long.jpg',
            'origin' => 'Quảng Ninh',
            'view_total' => 200,
            'view_day' => 15,
            'view_week' => 60,
            'view_month' => 180,
            'created_at' => $now,
            'updated_at' => $now,
        ],
        // ... các sản phẩm khác
    ];

    foreach ($products as $product) {
        Product::create($product);
    }
}

}
