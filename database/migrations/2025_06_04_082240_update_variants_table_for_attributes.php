<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVariantsTableForAttributes extends Migration
{
    public function up()
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->unsignedBigInteger('attribute_value_id')->nullable()->after('product_id');
            // Xóa cột name nếu muốn, hoặc để lại nếu vẫn cần tên tự do
            // $table->dropColumn('name'); // chỉ khi không cần tên thủ công nữa
            $table->foreign('attribute_value_id')->references('id')->on('attribute_values')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->dropForeign(['attribute_value_id']);
            $table->dropColumn('attribute_value_id');
        });
    }
}