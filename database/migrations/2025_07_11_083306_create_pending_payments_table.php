<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pending_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index(); // người dùng thanh toán
            $table->string('order_id')->unique(); // orderId từ MoMo
            $table->unsignedBigInteger('amount'); // số tiền đã thanh toán
            $table->json('cart_item_ids'); // các cart item đã chọn
            $table->string('payment_method')->default('momo'); // dự phòng cho các phương thức khác sau này
            $table->string('status')->default('pending'); // trạng thái: pending / used / expired
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_payments');
    }
};
