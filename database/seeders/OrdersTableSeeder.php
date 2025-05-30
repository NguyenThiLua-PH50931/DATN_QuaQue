<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\admin\Order;


class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $userId = DB::table('users')->inRandomOrder()->value('id');
        $addressId = DB::table('addresses')->inRandomOrder()->value('id');
        $shippingMethodId = DB::table('shipping_methods')->inRandomOrder()->value('id');
        $discountCodeId = DB::table('discount_codes')->inRandomOrder()->value('id');

        Order::create([
            'user_id' => $userId,
            'address_id' => $addressId,
            'shipping_method_id' => $shippingMethodId,
            'discount_code_id' => $discountCodeId,
            'total_amount' => 150000,
            'shipping_cost' => 20000,
            'status' => 'pending',
            'payment_method' => 'cod',
            'receiver_name' => null,
            'receiver_phone' => null,
        ]);

        Order::create([
            'user_id' => $userId,
            'address_id' => $addressId,
            'shipping_method_id' => $shippingMethodId,
            'discount_code_id' => null,
            'total_amount' => 300000,
            'shipping_cost' => 30000,
            'status' => 'shipping',
            'payment_method' => 'bank',
            'receiver_name' => 'Nguyen Van B',
            'receiver_phone' => '0987654321',
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now(),
        ]);
    }
}