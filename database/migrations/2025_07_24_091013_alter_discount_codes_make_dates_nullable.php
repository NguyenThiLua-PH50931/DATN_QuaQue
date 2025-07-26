<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDiscountCodesMakeDatesNullable extends Migration
{
    public function up()
    {
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->dateTime('start_date')->nullable()->change();
            $table->dateTime('end_date')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('discount_codes', function (Blueprint $table) {
            // Nếu muốn revert lại thì để NOT NULL, có thể set giá trị default nếu cần
            $table->dateTime('start_date')->nullable(false)->change();
            $table->dateTime('end_date')->nullable(false)->change();
        });
    }
}
