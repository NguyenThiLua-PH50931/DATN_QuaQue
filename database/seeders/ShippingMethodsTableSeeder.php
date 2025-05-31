<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShippingMethodsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('shipping_methods')->insert([
            [
                'name' => 'Giao hàng nhanh',
                'description' => 'Giao trong 1-2 ngày',
                'cost' => 20000,
                'estimated_days' => 2, // 👈 thêm dòng này
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Giao hàng tiết kiệm',
                'description' => 'Giao trong 3-5 ngày',
                'cost' => 15000,
                'estimated_days' => 5, // 👈 thêm dòng này
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
