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
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255); // Tên phương thức
            $table->text('description'); // Mô tả chi tiết
            $table->decimal('cost', 10, 2); // Chi phí giao hàng
            $table->integer('estimated_days'); // Thời gian giao hàng dự kiến (số ngày)
            $table->boolean('active')->default(true); // Có hiển thị hay không
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
