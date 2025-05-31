<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToVariantsTable extends Migration
{
    public function up()
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->text('description')->nullable()->after('name');
            $table->decimal('price', 10, 2)->default(0)->after('description');
            $table->integer('stock')->default(0)->after('price');
            $table->string('sku')->nullable()->after('stock');
            $table->string('barcode')->nullable()->after('sku');
            $table->string('image')->nullable()->after('barcode');
            $table->boolean('active')->default(1)->after('image');
        });
    }

    public function down()
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->dropColumn([
                'description', 'price', 'stock', 'sku', 'barcode', 'image', 'active'
            ]);
        });
    }
}
