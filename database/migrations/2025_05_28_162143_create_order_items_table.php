<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');        // FK tới orders
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            $table->unsignedBigInteger('product_id');      // FK tới products
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');


            $table->string('product_name');                // Snapshot tên sản phẩm
            $table->string('product_sku')->nullable();     // Mã SKU nếu có
            $table->string('product_image')->nullable();   // Đường dẫn ảnh sản phẩm lúc mua (nếu muốn lưu)

            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);               // Giá tại thời điểm mua
            $table->decimal('discount', 10, 2)->default(0);// Giảm giá dòng sản phẩm
            $table->decimal('total', 10, 2);               // = (price - discount) * quantity

            $table->enum('status', ['pending', 'shipped', 'canceled'])
                  ->default('pending');

            $table->text('note')->nullable();              // Ghi chú riêng từng dòng nếu có

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
