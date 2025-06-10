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
        Schema::table('banners', function (Blueprint $table) {
            $table->softDeletes(); // cột deleted_at cho xóa mềm
            $table->dateTime('display_at')->nullable()->after('active'); // cột ngày hiển thị
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('display_at');
        });
    }
}; 