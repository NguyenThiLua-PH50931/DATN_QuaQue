<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSellerIdFromProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Trước khi drop cột cần drop FK
            $table->dropForeign(['seller_id']); 
            $table->dropColumn('seller_id');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('seller_id')->after('id');
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}
