<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrderItemsAddVariantColumns extends Migration
{
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Thêm variant_id nếu chưa có
            if (!Schema::hasColumn('order_items', 'variant_id')) {
                $table->unsignedBigInteger('variant_id')->nullable()->after('product_id');
            }

            // Thêm các cột mới
            if (!Schema::hasColumn('order_items', 'variant_name')) {
                $table->string('variant_name')->nullable()->after('variant_id');
            }
            if (!Schema::hasColumn('order_items', 'barcode')) {
                $table->string('barcode')->nullable()->after('product_sku');
            }
            if (!Schema::hasColumn('order_items', 'product_image')) {
                $table->string('product_image')->nullable()->after('barcode');
            }

            // Đổi product_sku thành nullable nếu chưa nullable
            // Lưu ý: cần package doctrine/dbal để dùng change()
            $table->string('product_sku')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Xóa các cột đã thêm
            if (Schema::hasColumn('order_items', 'variant_name')) {
                $table->dropColumn('variant_name');
            }
            if (Schema::hasColumn('order_items', 'barcode')) {
                $table->dropColumn('barcode');
            }
            if (Schema::hasColumn('order_items', 'product_image')) {
                $table->dropColumn('product_image');
            }
            if (Schema::hasColumn('order_items', 'variant_id')) {
                $table->dropColumn('variant_id');
            }
            // Không revert product_sku nullable tự động
        });
    }
}
