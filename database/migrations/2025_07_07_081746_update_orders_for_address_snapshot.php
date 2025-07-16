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
    Schema::table('orders', function (Blueprint $table) {
        $table->dropForeign(['address_id']); // nếu có foreign key
        $table->dropColumn('address_id');

        $table->string('recipient_name');
        $table->string('phone', 20);
        $table->text('full_address');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->unsignedBigInteger('address_id')->nullable(); // hoặc không nullable tùy logic
        $table->foreign('address_id')->references('id')->on('addresses');

        $table->dropColumn(['recipient_name', 'phone', 'full_address']);
    });
}

};
