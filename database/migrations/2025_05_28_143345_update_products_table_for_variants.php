<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTableForVariants extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Xóa seller_id nếu còn
            if (Schema::hasColumn('products', 'seller_id')) {
                $table->dropForeign(['seller_id']);
                $table->dropColumn('seller_id');
            }

            // Xóa các trường chi tiết biến thể
            if (Schema::hasColumn('products', 'price')) {
                $table->dropColumn('price');
            }
            if (Schema::hasColumn('products', 'stock')) {
                $table->dropColumn('stock');
            }

            // Nếu chưa có, thêm lại description tổng quan (cho mô tả chung sản phẩm)
            if (!Schema::hasColumn('products', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }

            // Thêm trường active để quản lý trạng thái sản phẩm
            if (!Schema::hasColumn('products', 'active')) {
                $table->boolean('active')->default(1)->after('origin');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Thêm lại các trường đã xóa (rollback)
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');

            // Xóa trường vừa thêm
            $table->dropColumn('active');
        });
    }
}
