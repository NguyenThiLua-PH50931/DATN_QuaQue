<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropForeign(['category_id']);  // nếu có FK
        $table->dropColumn('category_id');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->unsignedBigInteger('category_id')->nullable();

        // Nếu cần, thêm lại FK
        $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
    });
}

};
