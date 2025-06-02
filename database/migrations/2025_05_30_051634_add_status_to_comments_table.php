<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToCommentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
           // $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('content');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
