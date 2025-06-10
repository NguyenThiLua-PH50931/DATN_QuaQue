<?php

namespace Database\Seeders;

use App\Models\admin\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
   public function run()
{
    // Vô hiệu hóa kiểm tra khóa ngoại
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // Xoá sạch bảng categories và reset auto_increment
    DB::table('categories')->truncate();

    // Bật lại kiểm tra khóa ngoại
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    // Seed dữ liệu
    $categories = [
        'Đặc sản vùng miền',
        'Thực phẩm khô',
        'Đồ uống truyền thống',
        'Mứt và kẹo',
        'Gia vị',
        'Thủ công mỹ nghệ',
        'Quà biếu và tặng',
        'Hàng lưu niệm',
    ];

    foreach ($categories as $name) {
        Category::create([
            'name' => $name,
            'slug' => \Illuminate\Support\Str::slug($name),
        ]);
    }
}

}
