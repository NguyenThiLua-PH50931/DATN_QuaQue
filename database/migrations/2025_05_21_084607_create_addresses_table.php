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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('recipient_name', 100);  // Tên người nhận
            $table->string('phone', 20);            // SĐT người nhận
            $table->text('address');                // Chi tiết địa chỉ
            $table->string('province', 100);        // Tỉnh/TP
            $table->string('district', 100);        // Quận/Huyện
            $table->boolean('is_default')->default(false); // Mặc định: không
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
