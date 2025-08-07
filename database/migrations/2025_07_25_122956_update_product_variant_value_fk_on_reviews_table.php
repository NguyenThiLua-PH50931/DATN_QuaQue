<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductVariantValueFkOnReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // XÓA FK CŨ nếu có (tên mặc định Laravel tạo là reviews_product_variant_value_id_foreign)
            try {
                $table->dropForeign(['product_variant_value_id']);
            } catch (\Throwable $e) {
                // Nếu không có cũng không sao, chỉ để tránh lỗi khi migrate
            }

            // THÊM FK MỚI
            $table->foreign('product_variant_value_id')
                ->references('id')
                ->on('product_variants')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Xóa FK mới
            $table->dropForeign(['product_variant_value_id']);

            // Thêm lại FK cũ (nếu muốn rollback), thường là:
            // $table->foreign('product_variant_value_id')->references('id')->on('product_variant_attribute_values')->onDelete('set null');
        });
    }
}
