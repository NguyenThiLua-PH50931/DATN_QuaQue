<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('comment_replies', function (Blueprint $table) {
        $table->foreignId('user_id')->after('comment_id')->constrained('users');
        $table->dropForeign(['admin_id']);
        $table->dropColumn('admin_id'); // Xoá luôn nếu không dùng
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comment_replies', function (Blueprint $table) {
            //
        });
    }
};
