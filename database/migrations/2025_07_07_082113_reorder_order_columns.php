<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReorderOrderColumns extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Không thêm mới, chỉ reorder bằng cách change() + after()
            $table->string('order_code')->after('id')->change();
            $table->unsignedBigInteger('user_id')->after('order_code')->change();

            $table->string('recipient_name')->after('user_id')->change();
            $table->string('phone', 20)->after('recipient_name')->change();
            $table->text('full_address')->after('phone')->change();

            $table->unsignedBigInteger('shipping_method_id')->after('full_address')->change();
            $table->decimal('shipping_cost', 10, 2)->after('shipping_method_id')->change();

            $table->unsignedBigInteger('discount_code_id')->nullable()->after('shipping_cost')->change();
            $table->unsignedBigInteger('free_shipping_code_id')->nullable()->after('discount_code_id')->change();
            $table->decimal('discount_amount', 10, 2)->default(0.00)->after('free_shipping_code_id')->change();

            $table->decimal('total_amount', 10, 2)->after('discount_amount')->change();
            $table->enum('payment_method', ['cod', 'bank', 'wallet'])->default('cod')->after('total_amount')->change();
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid')->after('payment_method')->change();
            $table->boolean('bank_transfer_confirmed')->default(false)->after('payment_status')->change();

            $table->string('status')->default('pending')->after('bank_transfer_confirmed')->change();
            $table->boolean('is_hidden')->default(false)->after('status')->change();

            $table->timestamp('created_at')->nullable()->after('is_hidden')->change();
            $table->timestamp('updated_at')->nullable()->after('created_at')->change();
            $table->timestamp('deleted_at')->nullable()->after('updated_at')->change();
        });
    }

    public function down()
    {
        //
    }
}
