<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('seller_id');    // FK → users(id)
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            

            $table->unsignedBigInteger('category_id');  // FK → categories(id)
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');


            $table->unsignedBigInteger('region_id');    // FK → regions(id)
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            

            $table->string('name', 200);        // Tên sản phẩm
            $table->string('slug', 200)->unique(); // URL thân thiện
            $table->text('description');        // Mô tả sản phẩm

            $table->decimal('price', 10, 2);    // Giá bán
            $table->integer('stock');           // Tồn kho
            $table->string('image', 255);       // Ảnh sản phẩm chính
            $table->string('origin', 100);      // Xuất xứ đặc sản
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
