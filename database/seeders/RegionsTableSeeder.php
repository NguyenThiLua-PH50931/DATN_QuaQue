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
    // Vô hiệu hóa kiểm tra khóa ngoại
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    
    // Xóa sạch bảng và reset ID
    DB::table('regions')->truncate();
    
    // Bật lại kiểm tra khóa ngoại
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $regions = ['Miền Bắc', 'Miền Trung', 'Miền Nam'];

    foreach ($regions as $name) {
        Region::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]);
    }
}
}
