<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFreeShippingToDiscountCodesTable extends Migration
{
    public function up()
    {
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->boolean('free_shipping')->default(0)->after('discount_type');
        });
    }

    public function down()
    {
        Schema::table('discount_codes', function (Blueprint $table) {
            $table->dropColumn('free_shipping');
        });
    }
}
