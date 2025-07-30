<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearDiscountCodesSeeder extends Seeder
{
public function run()
{
    // Tắt ràng buộc foreign key tạm thời
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    DB::table('coupon_product')->truncate();
    DB::table('discount_code_usages')->truncate();
    DB::table('discount_codes')->truncate();
    DB::table('discount_codes')->truncate();
    DB::table('users')->truncate();
    DB::table('addresses')->truncate();
    DB::table('orders')->truncate();
    DB::table('order_items')->truncate();
    DB::table('order_status_logs')->truncate();
    DB::table('discount_codes')->truncate();
    // Bật lại
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');
}

}
