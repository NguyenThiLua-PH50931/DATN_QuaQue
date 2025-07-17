<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanOrderTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tắt kiểm tra khóa ngoại để xóa nhanh
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Xóa dữ liệu và reset ID về 1
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('pending_payments')->truncate();
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Đã xoá hết dữ liệu và reset ID trong các bảng orders, order_items, pending_payments!');
    }
}