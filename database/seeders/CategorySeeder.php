<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Đặc sản miền Bắc', 'slug' => Str::slug('Đặc sản miền Bắc'), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Đặc sản miền Trung', 'slug' => Str::slug('Đặc sản miền Trung'), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Đặc sản miền Nam', 'slug' => Str::slug('Đặc sản miền Nam'), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
