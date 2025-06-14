<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToProductVariantsTable extends Migration
{
    public function up()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            // Thêm các cột còn thiếu, nếu chưa có
            if (!Schema::hasColumn('product_variants', 'attribute_value_id')) {
                $table->unsignedBigInteger('attribute_value_id')->nullable()->after('product_id');
                // Nếu bạn dùng pivot thì có thể bỏ cột này, tùy thiết kế
            }
            if (!Schema::hasColumn('product_variants', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('product_variants', 'barcode')) {
                $table->string('barcode')->nullable()->after('sku');
            }
            if (!Schema::hasColumn('product_variants', 'active')) {
                $table->tinyInteger('active')->default(1)->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'attribute_value_id')) {
                $table->dropColumn('attribute_value_id');
            }
            if (Schema::hasColumn('product_variants', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('product_variants', 'barcode')) {
                $table->dropColumn('barcode');
            }
            if (Schema::hasColumn('product_variants', 'active')) {
                $table->dropColumn('active');
            }
        });
    }
}
