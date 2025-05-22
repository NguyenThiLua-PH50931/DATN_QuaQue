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
        Schema::create('search_history', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id'); // Người gửi yêu cầu
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');// Người dùng thực hiện
            
            $table->string('keyword', 255); // Từ khóa tìm kiếm
            $table->integer('result_count')->default(0); // Số lượng kết quả tìm kiếm
            $table->dateTime('searched_at'); // Thời gian tìm kiếm

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_history');
    }
};
