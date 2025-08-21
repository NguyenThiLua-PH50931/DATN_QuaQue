<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // Thêm giới tính
            $table->enum('gender', ['male', 'female'])->nullable()->after('email');

            // Thêm ngày sinh
            $table->date('birthday')->nullable()->after('gender');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['deletion_type', 'gender', 'birthday']);
        });
    }
};
