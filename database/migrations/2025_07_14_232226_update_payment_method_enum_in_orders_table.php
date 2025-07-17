<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdatePaymentMethodEnumInOrdersTable extends Migration
{
    public function up()
    {
        // Update dữ liệu cũ từ 'wallet' về 'momo'
        DB::statement("UPDATE orders SET payment_method = 'momo' WHERE payment_method = 'wallet'");

        // Đổi enum value từ 'wallet' => 'momo'
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('cod','bank','momo') NOT NULL DEFAULT 'cod'");
    }

    public function down()
    {
        // Nếu rollback muốn trả về lại 'wallet'
        DB::statement("UPDATE orders SET payment_method = 'wallet' WHERE payment_method = 'momo'");
        DB::statement("ALTER TABLE orders MODIFY payment_method ENUM('cod','bank','wallet') NOT NULL DEFAULT 'cod'");
    }
}
