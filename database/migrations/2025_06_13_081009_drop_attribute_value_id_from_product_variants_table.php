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
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('attribute_value_id');
        });
    }

    public function down()
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_value_id')->nullable()->after('product_id');
        });
    }
};
