<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegionSeeder extends Seeder
{
    public function run()
    {
        DB::table('regions')->insert([
            ['name' => 'Miền Bắc', 'slug' => Str::slug('Miền Bắc'), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Miền Trung', 'slug' => Str::slug('Miền Trung'), 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Miền Nam', 'slug' => Str::slug('Miền Nam'), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
