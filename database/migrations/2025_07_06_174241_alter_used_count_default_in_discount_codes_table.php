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
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->integer('used_count')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->integer('used_count')->nullable()->change(); // hoặc bỏ default tùy nhu cầu
        });
    }
};
