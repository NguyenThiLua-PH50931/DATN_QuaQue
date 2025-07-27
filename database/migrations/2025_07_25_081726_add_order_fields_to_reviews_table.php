<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderFieldsToReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Nếu đã có các trường này thì bỏ dòng tương ứng!
            $table->unsignedBigInteger('order_id')->nullable()->after('user_id');
            $table->unsignedBigInteger('order_item_id')->nullable()->after('order_id');

            // Nếu muốn ràng buộc khoá ngoại (khuyên dùng)
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Xoá khoá ngoại trước
            $table->dropForeign(['order_id']);
            $table->dropForeign(['order_item_id']);
            // Sau đó xoá trường
            $table->dropColumn('order_id');
            $table->dropColumn('order_item_id');
        });
    }
}
