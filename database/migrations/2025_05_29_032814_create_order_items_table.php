<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            $table->string('product_name');            // Lưu tên sản phẩm lúc đặt
            $table->string('product_sku')->nullable(); // SKU nếu có
            $table->string('product_image')->nullable(); // Ảnh tại thời điểm mua

            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2); // (price - discount) * quantity

            $table->enum('status', ['pending', 'shipped', 'canceled'])->default('pending'); // Trạng thái dòng sản phẩm
            $table->text('note')->nullable(); // Ghi chú nếu có

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
