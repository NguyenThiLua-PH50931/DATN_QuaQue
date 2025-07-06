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
        $table->string('discount_type')->nullable()->change();
    });
}

public function down()
{
    Schema::table('discount_codes', function (Blueprint $table) {
        $table->string('discount_type')->nullable(false)->change();
    });
}

};
