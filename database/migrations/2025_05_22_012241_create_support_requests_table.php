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
        Schema::create('support_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Người gửi yêu cầu
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->string('subject', 255); // Chủ đề
            $table->text('message'); // Nội dung
            $table->enum('status', ['open', 'resolved', 'closed'])->default('open'); // Trạng thái xử lý
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_requests');
    }
};
