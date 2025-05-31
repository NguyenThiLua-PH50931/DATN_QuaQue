<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\admin\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
=======
use App\Models\BE\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
<<<<<<< HEAD
        DB::statement('ALTER TABLE categories AUTO_INCREMENT = 1');

=======
>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
        // Xóa dữ liệu cũ, dùng delete() thay vì truncate() nếu có foreign key
        Category::query()->delete();

        $categories = [
<<<<<<< HEAD
            'Đặc sản vùng miền',
            'Thực phẩm khô',
            'Đồ uống truyền thống',
            'Mứt và kẹo',
            'Gia vị',
            'Thủ công mỹ nghệ',
            'Quà biếu và tặng',
            'Hàng lưu niệm',
=======
            'Đặc sản',
            'Thực phẩm',
            'Đồ uống',
            'Thời trang',
            'Điện tử',
>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
        ];

        foreach ($categories as $name) {
            Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
