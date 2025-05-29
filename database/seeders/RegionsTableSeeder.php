<?php

namespace Database\Seeders;

use App\Models\BE\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegionsTableSeeder extends Seeder
{
   public function run()
    {
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
