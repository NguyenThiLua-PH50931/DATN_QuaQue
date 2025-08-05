<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Xóa dữ liệu trong bảng order_items trước
        DB::table('order_items')->delete();
        // Sau đó xóa dữ liệu trong bảng orders
        DB::table('orders')->delete();
        // Cuối cùng xóa dữ liệu trong bảng pending_payments
        DB::table('pending_payments')->delete();
    }
}