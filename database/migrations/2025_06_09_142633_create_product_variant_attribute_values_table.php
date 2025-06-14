<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductVariantAttributeValuesTable extends Migration
{
    public function up()
    {
        Schema::create('product_variant_attribute_values', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại tới biến thể
            $table->unsignedBigInteger('product_variant_id');
            $table->foreign('product_variant_id')
                ->references('id')->on('product_variants')
                ->onDelete('cascade');

            // Khóa ngoại tới thuộc tính
            $table->unsignedBigInteger('attribute_id');
            $table->foreign('attribute_id')
                ->references('id')->on('attributes')
                ->onDelete('cascade');

            // Khóa ngoại tới giá trị thuộc tính
            $table->unsignedBigInteger('attribute_value_id');
            $table->foreign('attribute_value_id')
                ->references('id')->on('attribute_values')
                ->onDelete('cascade');

            $table->timestamps();

            // Chỉ cho phép một attribute_value duy nhất cho 1 biến thể
            $table->unique(['product_variant_id', 'attribute_id'], 'variant_attribute_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_variant_attribute_values');
    }
}
