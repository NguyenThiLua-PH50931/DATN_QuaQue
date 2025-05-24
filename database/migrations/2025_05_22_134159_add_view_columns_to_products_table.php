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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('view_total')->default(0);
            $table->unsignedInteger('view_day')->default(0);
            $table->unsignedInteger('view_week')->default(0);
            $table->unsignedInteger('view_month')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['view_total', 'view_day', 'view_week', 'view_month']);
        });
    }
};
