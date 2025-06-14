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
        // ⚠ Tuyệt đối không dùng truncate ở đây!
        DB::table('regions')->delete(); // An toàn, không lỗi khóa ngoại

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
