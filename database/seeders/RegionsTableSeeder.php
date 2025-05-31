<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\admin\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

=======
use App\Models\BE\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391

class RegionsTableSeeder extends Seeder
{
   public function run()
    {
<<<<<<< HEAD
        DB::statement('ALTER TABLE regions AUTO_INCREMENT = 1');
=======
>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
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
