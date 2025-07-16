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
        $table->dropColumn('bank_transfer_confirmed');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->boolean('bank_transfer_confirmed')->default(false);
        // Nếu cột của bạn kiểu khác, thay đổi lại cho đúng nhé!
    });
}

};
