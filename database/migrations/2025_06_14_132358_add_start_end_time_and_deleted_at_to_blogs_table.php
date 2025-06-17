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
        Schema::table('blogs', function (Blueprint $table) {
            $table->date('start_date')->nullable()->after('thumbnail');
            $table->date('end_date')->nullable()->after('start_date');
            $table->softDeletes()->after('updated_at'); // Thêm trường deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropSoftDeletes(); // Xóa trường deleted_at
        });
    }
};
