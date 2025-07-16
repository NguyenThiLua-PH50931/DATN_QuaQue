<?php

// database/migrations/xxxx_xx_xx_xxxxxx_add_fields_to_pending_payments.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToPendingPayments extends Migration
{
    public function up()
    {
        Schema::table('pending_payments', function (Blueprint $table) {
            $table->string('recipient_name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('full_address')->nullable();
            $table->unsignedBigInteger('shipping_method_id')->nullable();
            $table->decimal('shipping_cost', 10, 2)->nullable();
            $table->unsignedBigInteger('discount_code_id')->nullable();
            $table->unsignedBigInteger('free_shipping_code_id')->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
        });
    }

    public function down()
    {
        Schema::table('pending_payments', function (Blueprint $table) {
            $table->dropColumn([
                'recipient_name', 'phone', 'full_address',
                'shipping_method_id', 'shipping_cost',
                'discount_code_id', 'free_shipping_code_id', 'discount_amount'
            ]);
        });
    }
}
