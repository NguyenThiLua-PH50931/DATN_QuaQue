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
        Schema::create('product_searches', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id')->nullable(); // Nếu muốn lưu cả user đã tìm kiếm
    $table->unsignedBigInteger('product_id');
    $table->string('keyword')->nullable(); // Nếu muốn lưu keyword đã tìm kiếm
    $table->timestamps();

    $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
    $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_searches');
    }
};
