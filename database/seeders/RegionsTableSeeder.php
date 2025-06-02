<?php

namespace Database\Seeders;

use App\Models\admin\Region; // Hoặc đổi lại BE nếu đúng với project bạn
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RegionsTableSeeder extends Seeder
{
    public function run()
    {
        // Đặt lại AUTO_INCREMENT nếu cần
        DB::statement('ALTER TABLE regions AUTO_INCREMENT = 1');

        // Xóa dữ liệu cũ (dùng delete thay vì truncate để tránh lỗi khóa ngoại)
        Region::query()->delete();

        $regions = [
            'Miền Bắc',
            'Miền Trung',
            'Miền Nam',
            'Miền Tây',
        ];

        foreach ($regions as $name) {
            Region::create([
                'name' => $name,
                'slug' => Str::slug($name),
            ]);
        }
    }
}
