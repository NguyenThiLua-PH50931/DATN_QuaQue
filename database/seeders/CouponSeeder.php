<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\admin\Coupons;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('discount_codes')->delete(); // Xóa an toàn không ảnh hưởng ràng buộc

        // Tạo 5 mã giảm giá mẫu
        for ($i = 1; $i <= 5; $i++) {
            Coupons::create([
                'code' => strtoupper(Str::random(8)),
                'description' => 'Giảm giá ' . $i * 5 . '% cho đơn hàng',
                'discount_type' => 'percent', // hoặc 'fixed'
                'discount_value' => $i * 5,   // 5%, 10%, ...
                'min_order_amount' => 100000,
                'max_discount_amount' => 50000,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(30),
                'usage_limit' => 100,
                'used_count' => 0,
                'active' => 1,
            ]);
        }
    }
}

