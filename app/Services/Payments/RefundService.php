<?php
declare(strict_types=1);

namespace App\Services\Payments;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Kết quả hoàn tiền chuẩn hoá cho mọi cổng.
 */
class RefundResult
{
    public bool $success;
    public string $message;
    public ?string $reference;   // m_refund_id (mã hoàn tiền phía bạn tạo)
    /** @var mixed $raw  Raw response từ cổng (array|string|null) */
    public $raw;
    public ?int $amount;         // số tiền hoàn (VND)
    public ?int $code;           // return_code từ cổng: 1=success, 3=processing, khác=fail

    private function __construct(
        bool $success,
        string $message,
        ?string $reference = null,
        $raw = null,
        ?int $amount = null,
        ?int $code = null
    ) {
        $this->success   = $success;
        $this->message   = $message;
        $this->reference = $reference;
        $this->raw       = $raw;
        $this->amount    = $amount;
        $this->code      = $code;
    }

    public static function ok(?string $reference = null, $raw = null, ?int $amount = null, ?int $code = null): self
    {
        return new self(true, 'OK', $reference, $raw, $amount, $code);
    }

    /**
     * Trạng thái đang xử lý từ phía cổng (ví dụ ZaloPay return_code=3).
     * success=false để UI/logic phía trên biết là chưa hoàn tất.
     */
    public static function processing(?string $reference = null, $raw = null, ?int $amount = null): self
    {
        return new self(false, 'PROCESSING', $reference, $raw, $amount, 3);
    }

    public static function fail(string $message, $raw = null, ?int $code = null): self
    {
        return new self(false, $message, null, $raw, null, $code);
    }
}

/**
 * Service router hoàn tiền theo phương thức thanh toán.
 */
class RefundService
{
    /**
     * Router gọi cổng hoàn tiền theo phương thức thanh toán của đơn.
     *
     * @param  \App\Models\admin\Order|\App\Models\Client\Order  $order
     * @param  array{amount?:int, reason?:string, transaction_id?:string, initiator?:string} $options
     */
    public function refund($order, array $options = []): RefundResult
    {
        $method = (string) ($order->payment_method ?? '');

        return match ($method) {
            'zalopay' => $this->refundZaloPay($order, $options),
            'momo'    => RefundResult::fail('Refund MoMo hiện chưa triển khai.'),
            default   => RefundResult::fail('Phương thức thanh toán không hỗ trợ hoàn tiền.'),
        };
    }

    /**
     * Hoàn tiền ZaloPay v2 (sb-openapi / openapi).
     * Yêu cầu: app_id, zp_trans_id, m_refund_id, amount, timestamp(ms), description, mac(key1)
     *
     * @param  \App\Models\admin\Order|\App\Models\Client\Order $order
     * @param  array{amount?:int, reason?:string, transaction_id?:string, initiator?:string} $options
     */
    protected function refundZaloPay($order, array $options = []): RefundResult
    {
        try {
            $cfg = config('services.zlp');

            $appId     = (string) ($cfg['app_id'] ?? '');
            $key1      = (string) ($cfg['key1'] ?? '');
            $refundUrl = (string) ($cfg['refund_url'] ?? '');

            if (!$appId || !$key1 || !$refundUrl) {
                return RefundResult::fail('Thiếu cấu hình ZaloPay (app_id/key1/refund_url).');
            }

            // Số tiền hoàn (VND integer) - đảm bảo > 0
            $amount = (int) ($options['amount'] ?? (int) round((float) ($order->total_amount ?? 0)));
            if ($amount <= 0) {
                return RefundResult::fail('Số tiền hoàn không hợp lệ (<= 0).');
            }

            // Lý do: loại bỏ '|' (tránh phá MAC), cắt về 255 ký tự
            $rawDesc     = (string) ($options['reason'] ?? ('Hoàn tiền đơn ' . ($order->order_code ?? $order->id ?? '')));
            $description = str_replace('|', '/', $rawDesc);
            if (mb_strlen($description, 'UTF-8') > 255) {
                $description = mb_substr($description, 0, 255, 'UTF-8');
            }

            // timestamp (ms)
            $timestamp = (string) round(microtime(true) * 1000);

            // Ưu tiên transaction_id truyền vào; fallback từ order->zp_trans_id/payment_txn_id
            $zpTransId = $options['transaction_id']
                ?? ($order->zp_trans_id ?? null)
                ?? ($order->payment_txn_id ?? null);

           if (!$zpTransId) {
    // Lấy app_trans_id theo thứ tự: cột zlp_app_trans_id (nếu có) -> helper zlpAppTransId() -> null
    $appTransId = null;
    if (isset($order->zlp_app_trans_id) && !empty($order->zlp_app_trans_id)) {
        $appTransId = (string) $order->zlp_app_trans_id;
    } elseif (method_exists($order, 'zlpAppTransId')) {
        $appTransId = (string) $order->zlpAppTransId();
    }

    if ($appTransId) {
        $found = $this->queryZlpTransIdByAppTransId($appTransId);
        if ($found) {
            $zpTransId = $found;
            // Lưu lại để lần sau khỏi query
            try {
                $order->zp_trans_id    = $found;
                $order->payment_txn_id = $found;
                $order->save();
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }
}

if (!$zpTransId) {
    return RefundResult::fail('Không tìm thấy zp_trans_id của giao dịch ZaloPay để hoàn tiền.');
}

            // m_refund_id: yymmdd_appId_xxxxxx (unique phía merchant)
            $uid       = substr($timestamp, -6) . random_int(100, 999);
            $mRefundId = now()->format('ymd') . '_' . $appId . '_' . $uid;

            $params = [
                'app_id'      => $appId,
                'm_refund_id' => $mRefundId,
                'zp_trans_id' => (string) $zpTransId,
                'amount'      => $amount,
                'timestamp'   => $timestamp,
                'description' => $description,
            ];

            // MAC = HMAC_SHA256(app_id|zp_trans_id|amount|description|timestamp, key1)
            $macData       = $params['app_id'].'|'.$params['zp_trans_id'].'|'.$params['amount'].'|'.$params['description'].'|'.$params['timestamp'];
            $params['mac'] = hash_hmac('sha256', $macData, $key1);

            // Gọi API
            $res = Http::asForm()
                ->timeout(15)         // ổn định mạng
                ->retry(2, 200)       // thử lại nhẹ nhàng
                ->post($refundUrl, $params);

            if (!$res->ok()) {
                return RefundResult::fail(
                    'ZaloPay refund không thành công (HTTP '.$res->status().').',
                    ['status' => $res->status(), 'body' => (string) $res->body()]
                );
            }

            $json = $res->json() ?? [];
            $rc   = (int)($json['return_code'] ?? $json['returncode'] ?? 0);
            $msg  = $json['return_message'] ?? ($json['returnmessage'] ?? 'ZaloPay refund trả về lỗi.');

            // 1 = success, 3 = processing, khác = fail
            if ($rc === 1) {
                return RefundResult::ok($mRefundId, $json, $amount, 1);
            }
            if ($rc === 3) {
                return RefundResult::processing($mRefundId, $json, $amount);
            }

            return RefundResult::fail($msg, $json, $rc);

        } catch (\Throwable $e) {
            Log::error('ZaloPay refund exception', [
                'order_id' => $order->id ?? null,
                'error'    => $e->getMessage(),
            ]);
            return RefundResult::fail('Lỗi khi gọi hoàn tiền ZaloPay.', $e->getMessage());
        }
    }

    /**
     * Lấy zp_trans_id (giao dịch ZaloPay) bằng app_trans_id (chính là order_code bạn nên set = app_trans_id).
     * Endpoint: /v2/query
     * MAC = HMAC_SHA256(app_id|app_trans_id|key1, key1)
     */
    public function queryZlpTransIdByAppTransId(string $appTransId): ?string
    {
        $cfg   = config('services.zlp');
        $appId = (string)($cfg['app_id'] ?? '');
        $key1  = (string)($cfg['key1'] ?? '');
        $url   = (string)($cfg['query_order_url'] ?? 'https://sb-openapi.zalopay.vn/v2/query');

        if (!$appId || !$key1 || !$url || !$appTransId) {
            Log::warning('[ZLP] query missing config/app_trans_id', compact('appId','key1','url','appTransId'));
            return null;
        }

        $mac = hash_hmac('sha256', $appId.'|'.$appTransId.'|'.$key1, $key1);

        $res = Http::asForm()
            ->timeout(15)
            ->retry(2, 200)
            ->post($url, [
                'app_id'       => $appId,
                'app_trans_id' => $appTransId,
                'mac'          => $mac,
            ]);

        if (!$res->ok()) {
            Log::warning('[ZLP] query http fail', ['status' => $res->status(), 'body' => (string)$res->body()]);
            return null;
        }

        $json = $res->json() ?: [];
        Log::info('[ZLP] query response', is_array($json) ? $json : ['raw' => $json]);

        if ((int)($json['return_code'] ?? 0) === 1 && !empty($json['zp_trans_id'])) {
            return (string)$json['zp_trans_id'];
        }

        // return_code != 1 có thể là đang xử lý; trả null để retry sau
        return null;
    }
}
