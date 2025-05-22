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
        Schema::create('search_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('keyword', 255); // Từ khóa gợi ý

            $table->unsignedBigInteger('category_id')->nullable(); // Danh mục liên quan
            $table->unsignedBigInteger('region_id')->nullable(); // Vùng miền liên quan

            $table->integer('search_count')->default(0); // Số lượt tìm kiếm
            $table->boolean('active')->default(true); // Có hiển thị không

            // Khóa ngoại
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_suggestions');
    }
};
