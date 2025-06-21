<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderItemsWithVariantRelation extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Đổi tên cột product_name -> product_variant_value_name
            $table->renameColumn('product_name', 'product_variant_value_name');

            // Thêm cột product_variant_value_id
            $table->unsignedBigInteger('product_variant_value_id')->nullable()->after('product_id');

            // Thiết lập foreign key cho product_variant_value_id
            $table->foreign('product_variant_value_id')
                  ->references('id')
                  ->on('product_variants')
                  ->onDelete('set null');

            // (Tuỳ chọn) Tạo foreign key cho product_variant_value_name -> name
            // Laravel không hỗ trợ trực tiếp foreign key đến cột không phải 'id',
            // nên cần thêm bằng cách thủ công nếu CSDL hỗ trợ
            // Hoặc xử lý logic ở ứng dụng thay vì ở DB
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Huỷ foreign key và xoá cột
            $table->dropForeign(['product_variant_value_id']);
            $table->dropColumn('product_variant_value_id');

            // Đổi lại tên cột
            $table->renameColumn('product_variant_value_name', 'product_name');
        });
    }
}
