<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameFreeShippingCodeIdToCodeInOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Nếu có ràng buộc khoá ngoại thì drop trước
            if (Schema::hasColumn('orders', 'free_shipping_code_id')) {
                // $table->dropForeign(['free_shipping_code_id']); // Nếu có FK
                $table->dropColumn('free_shipping_code_id');
            }
            $table->string('free_shipping_code')->nullable()->after('shipping_cost');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('free_shipping_code_id')->nullable()->after('shipping_cost');
            $table->dropColumn('free_shipping_code');
        });
    }
}
