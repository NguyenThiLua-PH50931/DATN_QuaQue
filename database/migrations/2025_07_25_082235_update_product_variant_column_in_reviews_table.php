<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductVariantColumnInReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Xóa khóa ngoại cũ nếu có (nếu đã từng có)
            if (Schema::hasColumn('reviews', 'product_variant_id')) {
                try {
                    $table->dropForeign(['product_variant_id']);
                } catch (\Exception $e) {}
                $table->dropColumn('product_variant_id');
            }

            // Thêm cột mới + khóa ngoại mới
            $table->unsignedBigInteger('product_variant_value_id')->nullable()->after('product_id');
            $table->foreign('product_variant_value_id')
                ->references('id')
                ->on('product_variant_attribute_values')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Xóa khóa ngoại mới và cột mới
            $table->dropForeign(['product_variant_value_id']);
            $table->dropColumn('product_variant_value_id');

            // Thêm lại cột cũ (nếu cần rollback)
            $table->unsignedBigInteger('product_variant_id')->nullable()->after('product_id');
            // Nếu cần thêm lại FK cũ thì bổ sung:
            // $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('set null');
        });
    }
}
