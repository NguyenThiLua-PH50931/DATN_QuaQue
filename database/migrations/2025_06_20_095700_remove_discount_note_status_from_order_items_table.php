<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['discount', 'note', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'shipped', 'canceled'])->default('pending');
        });
    }
};

