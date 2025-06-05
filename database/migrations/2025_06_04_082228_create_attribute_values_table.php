<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeValuesTable extends Migration
{
    public function up()
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_id');
            $table->string('value', 100); // VD: 1kg, 500g, Đỏ, Xanh
            $table->string('slug', 120);
            $table->timestamps();

            $table->foreign('attribute_id')->references('id')->on('attributes')->onDelete('cascade');
            $table->unique(['attribute_id', 'value']); // Không cho trùng giá trị cùng 1 attribute
        });
    }

    public function down()
    {
        Schema::dropIfExists('attribute_values');
    }
}