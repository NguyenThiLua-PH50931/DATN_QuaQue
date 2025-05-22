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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100)->unique(); // Mã giảm giá duy nhất
            $table->text('description')->nullable(); // Mô tả chi tiết

            $table->enum('discount_type', ['percent', 'fixed']); // Loại giảm giá: phần trăm hoặc cố định
            $table->decimal('discount_value', 10, 2); // Giá trị giảm

            $table->decimal('min_order_amount', 10, 2)->default(0); // Đơn tối thiểu để áp dụng
            $table->decimal('max_discount_amount', 10, 2)->nullable(); // Số tiền giảm tối đa (chỉ khi %)

            $table->dateTime('start_date'); // Ngày bắt đầu
            $table->dateTime('end_date');   // Ngày kết thúc

            $table->integer('usage_limit')->default(1); // Số lần tối đa sử dụng
            $table->integer('used_count')->default(0);  // Số lần đã dùng

            $table->boolean('active')->default(true); // Có hiển thị hay không
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
