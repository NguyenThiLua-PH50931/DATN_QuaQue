<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_hidden');
            $table->dropSoftDeletes(); // Xóa cột deleted_at
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->tinyInteger('is_hidden')->default(0)->after('status');
            $table->softDeletes(); // Thêm lại cột deleted_at
        });
    }
};
