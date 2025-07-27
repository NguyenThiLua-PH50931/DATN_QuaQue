<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountCodeUsagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('discount_code_usages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('discount_code_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            // Index
            $table->index('discount_code_id');
            $table->index('user_id');
            $table->index('order_id');

            // Foreign keys (có thể bỏ nếu bạn không muốn ràng buộc cứng)
            $table->foreign('discount_code_id')->references('id')->on('discount_codes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('discount_code_usages');
    }
}
