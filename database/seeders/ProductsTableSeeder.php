<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\admin\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

=======
use App\Models\BE\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use DB;
>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
use Carbon\Carbon;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
<<<<<<< HEAD
        DB::statement('ALTER TABLE products AUTO_INCREMENT = 1');

=======
>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
        $now = Carbon::now();

        $products = [
            [
<<<<<<< HEAD
=======

>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
                'category_id' => 2,
                'region_id' => 1,
                'name' => 'Đặc sản mắm tôm Hạ Long',
                'slug' => Str::slug('Đặc sản mắm tôm Hạ Long'),
                'description' => 'Mắm tôm truyền thống ngon tuyệt hảo của vùng biển Hạ Long.',
<<<<<<< HEAD
                //'price' => 120000,
               // 'stock' => 50,
=======
>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
                'image' => 'products/mam-tom-ha-long.jpg',
                'origin' => 'Quảng Ninh',
                'view_total' => 200,
                'view_day' => 15,
                'view_week' => 60,
                'view_month' => 180,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
<<<<<<< HEAD
=======


>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
                'category_id' => 3,
                'region_id' => 3,
                'name' => 'Trà Shan Tuyết cổ thụ Tuyên Quang',
                'slug' => Str::slug('Trà Shan Tuyết cổ thụ Tuyên Quang'),
                'description' => 'Trà Shan Tuyết được hái từ những cây trà cổ thụ hàng trăm tuổi.',
<<<<<<< HEAD
                //'price' => 350000,
                //'stock' => 30,
=======
>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
                'image' => 'products/tra-shan-tuyet.jpg',
                'origin' => 'Tuyên Quang',
                'view_total' => 200,
                'view_day' => 15,
                'view_week' => 60,
                'view_month' => 180,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
<<<<<<< HEAD
=======

>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
                'category_id' => 1,
                'region_id' => 2,
                'name' => 'Bánh đậu xanh Hải Dương',
                'slug' => Str::slug('Bánh đậu xanh Hải Dương'),
                'description' => 'Bánh đậu xanh mềm mịn, thơm ngon đặc trưng Hải Dương.',
<<<<<<< HEAD
               // 'price' => 90000,
                //'stock' => 100,
=======
>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
                'image' => 'products/banh-dau-xanh-hai-duong.jpg',
                'origin' => 'Hải Dương',
                'view_total' => 200,
                'view_day' => 15,
                'view_week' => 60,
                'view_month' => 180,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
