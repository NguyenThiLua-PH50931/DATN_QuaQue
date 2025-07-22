<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveShippingMethodIdFromOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // DROP foreign key trước (tên mặc định của Laravel sẽ là orders_shipping_method_id_foreign)
            $table->dropForeign(['shipping_method_id']);
            // Sau đó mới drop cột
            $table->dropColumn('shipping_method_id');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedBigInteger('shipping_method_id')->nullable()->after('full_address');
            // Nếu muốn add lại foreign key
            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('set null');
        });
    }
}
