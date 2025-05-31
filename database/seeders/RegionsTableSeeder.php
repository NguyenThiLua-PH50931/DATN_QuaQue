<?php

namespace Database\Seeders;

use App\Models\admin\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class RegionsTableSeeder extends Seeder
{
   public function run()
    {
        DB::statement('ALTER TABLE regions AUTO_INCREMENT = 1');
        // Xóa dữ liệu cũ (dùng delete() tránh lỗi khóa ngoại)
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
