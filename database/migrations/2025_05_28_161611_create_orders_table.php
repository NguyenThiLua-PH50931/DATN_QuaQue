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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Khách dặt hàng
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('address_id');              // Địa chỉ giao hàng
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('cascade');


            $table->unsignedBigInteger('shipping_method_id');      // Phương thức giao hàng
            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('cascade');


            $table->unsignedBigInteger('discount_code_id')->nullable(); // Mã giảm giá (nullable)
            $table->foreign('discount_code_id')->references('id')->on('discount_codes')->onDelete('set null');


            $table->decimal('total_amount', 10, 2);                // Tổng tiền
            $table->decimal('shipping_cost', 10, 2);               // Phí ship

            $table->enum('status', ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'])
                  ->default('pending');                            // Trạng thái đơn hàng

            $table->enum('payment_method', ['cod', 'bank', 'wallet'])
                  ->default('cod');                                // Phương thức thanh toán

            $table->string('receiver_name')->nullable();           // Tên người nhận hàng (nếu mua hộ)
            $table->string('receiver_phone', 20)->nullable();      // SĐT người nhận


            // Khóa ngoại
           
              $table->timestamps();
        });
          
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
