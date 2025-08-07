<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropVariantsWalletsWalletTransactionsTablesAndRemoveVariantColumnsFromOrderItems extends Migration
{
    public function up()
    {
        // Xóa bảng variants, wallets, wallet_transactions nếu tồn tại
        Schema::dropIfExists('variants');
        Schema::dropIfExists('wallet_transactions');
        Schema::dropIfExists('wallets');

        // Xóa cột variant_id và variant_name ở bảng order_items nếu tồn tại
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'variant_id')) {
                $table->dropColumn('variant_id');
            }
            if (Schema::hasColumn('order_items', 'variant_name')) {
                $table->dropColumn('variant_name');
            }
        });
    }

    public function down()
    {
        // Tạo lại bảng (nếu cần rollback)
        Schema::create('variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('attribute_value_id')->nullable();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0.00);
            $table->integer('stock')->default(0);
            $table->string('sku', 255)->nullable();
            $table->string('barcode', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->tinyInteger('active')->default(1);
            $table->timestamps();
        });

        Schema::create('wallets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('balance', 10, 2)->default(0.00);
            $table->timestamps();
        });

        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('wallet_id');
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['deposit', 'payment', 'refund']);
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->unsignedBigInteger('variant_id')->nullable()->after('product_variant_value_id');
            $table->string('variant_name', 255)->nullable()->after('variant_id');
        });
    }
}
