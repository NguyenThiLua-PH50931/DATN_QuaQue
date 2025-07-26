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
        $table->string('condition_type', 50)
              ->nullable()
              ->after('scope');
            
    });
}

public function down()
{
    Schema::table('discount_codes', function (Blueprint $table) {
        $table->dropColumn('condition_type');
    });
}

};
