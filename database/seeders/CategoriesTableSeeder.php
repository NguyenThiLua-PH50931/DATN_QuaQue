<?php

namespace Database\Seeders;

use App\Models\BE\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        // Xóa dữ liệu cũ, dùng delete() thay vì truncate() nếu có foreign key
        Category::query()->delete();

        $categories = [
            'Đặc sản',
            'Thực phẩm',
            'Đồ uống',
            'Thời trang',
            'Điện tử',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
