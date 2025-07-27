<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsReviewedToOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->boolean('is_reviewed')->default(false)->after('quantity');
            // Chỗ 'quantity' là cột hiện có trong bảng order_items để đặt cột mới sau nó
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('is_reviewed');
        });
    }
}
