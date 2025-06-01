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
        // Đặt lại ID tự tăng nếu cần
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1');

        // Xóa dữ liệu cũ (dùng delete thay vì truncate nếu có ràng buộc khóa ngoại)
        Category::query()->delete();

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
                'slug' => Str::slug($name),
            ]);
        }
    }
}
