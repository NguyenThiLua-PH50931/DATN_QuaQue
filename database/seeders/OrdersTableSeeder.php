<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\admin\Order;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        DB::statement('ALTER TABLE orders AUTO_INCREMENT = 1');

        // Lấy dữ liệu có điều kiện fallback để tránh NULL
        $userId = DB::table('users')->inRandomOrder()->value('id') ?? 1;
        $addressId = DB::table('addresses')->inRandomOrder()->value('id') ?? 1;
        $shippingMethodId = DB::table('shipping_methods')->inRandomOrder()->value('id') ?? 1;
        $discountCodeId = DB::table('discount_codes')->inRandomOrder()->value('id'); // có thể null

        // Kiểm tra xem các ID quan trọng có tồn tại không
        if (!$userId || !$addressId || !$shippingMethodId) {
            $this->command->error('Missing user, address, or shipping method data. Please seed those tables first!');
            return;
        }

        Order::create([
            'user_id' => $userId,
            'address_id' => $addressId,
            'shipping_method_id' => $shippingMethodId,
            'discount_code_id' => $discountCodeId,
            'total_amount' => 150000,
            'shipping_cost' => 20000,
            'status' => 'pending',
            'payment_method' => 'cod',
            'receiver_name' => 'Nguyen Van A',   // Nên có tên người nhận
            'receiver_phone' => '0912345678',    // Nên có số điện thoại
            'order_code' => 'QQ' . now()->format('Ymd-His'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
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
            'order_code' => 'QQ' . now()->subDays(2)->format('Ymd-His'),
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now(),
        ]);
    }
}
