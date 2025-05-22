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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id'); // Ví liên quan
            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade');

            $table->decimal('amount', 10, 2); // Số tiền thay đổi
            $table->enum('type', ['deposit', 'payment', 'refund']); // Loại giao dịch
            $table->text('note')->nullable(); // Ghi chú giao dịch
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
