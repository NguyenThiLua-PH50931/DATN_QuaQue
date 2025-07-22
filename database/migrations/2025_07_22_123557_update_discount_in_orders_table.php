<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        // Thêm cột discount_code
        $table->string('discount_code')->nullable()->after('free_shipping_code_id');
        // Nếu muốn bỏ discount_code_id
        $table->dropForeign(['discount_code_id']); // Nếu có FK
        $table->dropColumn('discount_code_id');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        // Quay lại như cũ nếu rollback
        $table->unsignedBigInteger('discount_code_id')->nullable()->after('free_shipping_code_id');
        $table->dropColumn('discount_code');
    });
}

};
