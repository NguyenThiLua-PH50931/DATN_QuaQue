<?php

namespace Database\Seeders;

use App\Models\admin\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RegionsTableSeeder extends Seeder
{
    public function run()
    {
        // Xóa dữ liệu trong bảng
        DB::table('regions')->delete(); // An toàn, không lỗi khóa ngoại
        DB::statement('ALTER TABLE regions AUTO_INCREMENT = 1;'); // Reset ID về 1

        $regions = [
            'Miền Bắc',
            'Miền Trung',
            'Miền Nam',
            'Miền Tây',
        ];

        foreach ($regions as $name) {
            Region::updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}