<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tham chiếu giao dịch bên cổng (MoMo: orderId, ZLP: app_trans_id)
            $table->string('payment_ref')->nullable()->after('payment_method');

            // Mã giao dịch ngân hàng/gateway cấp (ZLP: zp_trans_id) — sẽ có sau khi query/refund
            $table->string('payment_txn_id')->nullable()->after('payment_ref');

            // Trạng thái hoàn tiền
            $table->enum('refund_status', ['none', 'pending', 'success', 'failed'])
                  ->default('none')->after('payment_status');

            // Số tiền hoàn
            $table->bigInteger('refund_amount')->nullable()->after('refund_status');

            // Mã yêu cầu hoàn tiền do mình sinh (ZLP: m_refund_id)
            $table->string('refund_ref', 100)->nullable()->after('refund_amount');

            // Thông điệp/JSON trả về khi refund
            $table->text('refund_message')->nullable()->after('refund_ref');

            // Thời điểm hoàn tiền (nếu biết)
            $table->timestamp('refunded_at')->nullable()->after('refund_message');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_ref',
                'payment_txn_id',
                'refund_status',
                'refund_amount',
                'refund_ref',
                'refund_message',
                'refunded_at',
            ]);
        });
    }
};
