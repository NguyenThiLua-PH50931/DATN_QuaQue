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
    public ?string $reference;   // mã tham chiếu refund do merchant tạo (m_refund_id MoMo/ZLP)
    /** @var mixed $raw  Raw response từ cổng (array|string|null) */
    public $raw;
    public ?int $amount;         // số tiền hoàn (VND)
    public ?int $code;           // mã trạng thái cổng: 1=success, 3=processing (ZLP), khác=fail

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
            'momo'    => $this->refundMomo($order, $options),
            default   => RefundResult::fail('Phương thức thanh toán không hỗ trợ hoàn tiền.'),
        };
    }

    /* ---------------------------------------------------------------------
     |  ZALOPAY
     |---------------------------------------------------------------------*/

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

            // Số tiền hoàn (VND integer)
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

            // Nếu vẫn trống, thử query theo app_trans_id (nếu hệ thống có lưu)
            if (!$zpTransId) {
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
                ->timeout(15)
                ->retry(2, 200)
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
     * Lấy zp_trans_id (giao dịch ZaloPay) bằng app_trans_id.
     * Endpoint: /v2/query ; MAC = HMAC_SHA256(app_id|app_trans_id|key1, key1)
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

    /* ---------------------------------------------------------------------
     |  MOMO
     |---------------------------------------------------------------------*/

    /**
     * Hoàn tiền MoMo v2: POST /v2/gateway/api/refund
     * rawSignature = accessKey=&amount=&orderId=&partnerCode=&requestId=&transId=
     */
   /**
 * Hoàn tiền MoMo v2: POST /v2/gateway/api/refund
 * rawSignature (exact order):
 *   accessKey=&amount=&orderId=&partnerCode=&requestId=&transId=
 */
/**
 * Hoàn tiền MoMo v2: POST /v2/gateway/api/refund
 * LƯU Ý (sandbox của bạn): rawSignature phải CÓ description, thứ tự:
 *   accessKey=&amount=&description=&orderId=&partnerCode=&requestId=&transId=
 */
/**
 * Hoàn tiền MoMo v2: POST /v2/gateway/api/refund
 * Yêu cầu sandbox hiện tại: rawSignature có description, thứ tự:
 *   accessKey=&amount=&description=&orderId=&partnerCode=&requestId=&transId=
 * Lưu ý: orderId trong refund LÀ MÃ MỚI (khác orderId mua hàng).
 */
protected function refundMomo($order, array $options = []): RefundResult
{
    try {
        $cfg = config('services.momo');

        // --- normalize: remove BOM/non-printables/trim ---
        $norm = function ($s): string {
            $s = (string) $s;
            $s = preg_replace('/\x{FEFF}/u', '', $s);        // BOM
            $s = preg_replace('/[[:^print:]]/u', '', $s);    // non-printables
            return trim($s);
        };

        // 1) Config
        $partnerCode = $norm($cfg['partner_code'] ?? '');
        $accessKey   = $norm($cfg['access_key']   ?? '');
        $secretKey   = $norm($cfg['secret_key']   ?? '');
        $refundUrl   = $norm($cfg['refund_url']   ?? '');
        if ($partnerCode === '' || $accessKey === '' || $secretKey === '' || $refundUrl === '') {
            return RefundResult::fail('Thiếu cấu hình MoMo (partner_code/access_key/secret_key/refund_url).');
        }

        // 2) Amount (int VND)
        $amount = (int) ($options['amount'] ?? (int) round((float) ($order->total_amount ?? 0)));
        if ($amount <= 0) {
            return RefundResult::fail('Số tiền hoàn không hợp lệ (<= 0).');
        }

        // 3) transId (từ đơn gốc)
        $transId = $norm($options['transaction_id'] ?? ($order->payment_txn_id ?? ''));
        if ($transId === '') {
            return RefundResult::fail('Không tìm thấy transId của giao dịch MoMo để hoàn tiền.');
        }

        // 4) orderId gốc của giao dịch mua (chỉ để đối soát)
        $origOrderId = $norm($order->payment_ref ?? '');
        if ($origOrderId === '') {
            return RefundResult::fail('Thiếu orderId (payment_ref) của giao dịch MoMo.');
        }

        // 5) requestId & refundOrderId (mã MỚI)
        $requestId     = (string) ((int) round(microtime(true) * 1000)) . (string) random_int(100, 999);
        $refundOrderId = 'REF-' . $origOrderId . '-' . substr($requestId, -6);

        // 6) Description (THAM GIA KÝ)
        $rawDesc     = (string) ($options['reason'] ?? ('Hoàn tiền đơn ' . ($order->order_code ?? $order->id)));
        $description = str_replace('|', '/', $rawDesc);
        $description = mb_substr($description, 0, 255, 'UTF-8');
        if (class_exists('\Normalizer')) {
            $description = \Normalizer::normalize($description, \Normalizer::FORM_C) ?: $description;
        }

        // 7) rawSignature (đúng thứ tự + có description)
        $rawSignature = 'accessKey=' . $accessKey
            . '&amount='      . $amount
            . '&description=' . $description
            . '&orderId='     . $refundOrderId
            . '&partnerCode=' . $partnerCode
            . '&requestId='   . $requestId
            . '&transId='     . $transId;

        $signature = hash_hmac('sha256', $rawSignature, $secretKey);

        // ====== LOG NẶNG: input & raw (HEX) ======
        \Log::info('[MoMoRefund] sign_debug_hex', [
            'order_id'        => $order->id ?? null,
            'partnerCode'     => $partnerCode,
            'origOrderId'     => $origOrderId,
            'refundOrderId'   => $refundOrderId,
            'refundOrderId_hex'=> bin2hex($refundOrderId),
            'transId'         => $transId,
            'transId_hex'     => bin2hex($transId),
            'amount'          => $amount,
            'requestId'       => $requestId,
            'requestId_hex'   => bin2hex($requestId),
            'description'     => $description,
            'description_hex' => bin2hex($description),
            'raw'             => $rawSignature,
            'raw_hex'         => bin2hex($rawSignature),
            'sig'             => $signature,
            'envs_hex'        => [
                'partnerCode' => bin2hex($partnerCode),
                'accessKey'   => bin2hex($accessKey),
                'secretKey'   => bin2hex($secretKey),
            ],
        ]);

        // helper gửi + log chi tiết
        $send = function (array $payload, string $mode) use ($refundUrl) {
            \Log::info('[MoMoRefund] payload_'.$mode, [
                'payload'      => $payload,
                'payload_hex'  => array_map(fn($v) => bin2hex((string) $v), $payload),
                'json'         => json_encode($payload, JSON_UNESCAPED_UNICODE),
                'json_hex'     => bin2hex(json_encode($payload, JSON_UNESCAPED_UNICODE)),
            ]);

            $res = Http::timeout(25)->retry(1, 200)
                ->withHeaders(['Content-Type' => 'application/json; charset=utf-8'])
                ->post($refundUrl, $payload);

            \Log::info('[MoMoRefund] response_'.$mode, [
                'http_status' => $res->status(),
                'json'        => $res->json(),
                'text'        => (string) $res->body(),
            ]);

            return $res;
        };

        // 8) MODE A: có accessKey trong body (ép STRING các tham số tham gia ký)
        $payloadA = [
            'partnerCode' => (string) $partnerCode,
            'accessKey'   => (string) $accessKey,
            'orderId'     => (string) $refundOrderId, // orderId MỚI
            'requestId'   => (string) $requestId,
            'amount'      => (string) $amount,
            'transId'     => (string) $transId,
            'lang'        => 'vi',
            'description' => (string) $description,
            'signature'   => (string) $signature,
        ];
        $resA = $send($payloadA, 'A');
        if ($resA->ok()) {
            $jsonA = $resA->json() ?? [];
            if ((int) ($jsonA['resultCode'] ?? -999) === 0) {
                return RefundResult::ok($refundOrderId, $jsonA, $amount, 1);
            }
            $msgA = (string) ($jsonA['message'] ?? '');
            $isInvalidSig = str_contains(mb_strtolower($msgA, 'UTF-8'), 'chữ ký không hợp lệ');
            if (!$isInvalidSig) {
                return RefundResult::fail($msgA ?: 'MoMo refund trả về lỗi.', $jsonA, (int) ($jsonA['resultCode'] ?? -999));
            }
        }

        // 9) MODE B: bỏ accessKey trong body (giữ signature y hệt)
        $payloadB = [
            'partnerCode' => (string) $partnerCode,
            'orderId'     => (string) $refundOrderId,
            'requestId'   => (string) $requestId,
            'amount'      => (string) $amount,
            'transId'     => (string) $transId,
            'lang'        => 'vi',
            'description' => (string) $description,
            'signature'   => (string) $signature,
        ];
        $resB = $send($payloadB, 'B');

        if (!$resB->ok()) {
            return RefundResult::fail(
                'MoMo refund HTTP ' . $resB->status(),
                ['status' => $resB->status(), 'body' => (string) $resB->body()]
            );
        }

        $jsonB = $resB->json() ?? [];
        $codeB = (int) ($jsonB['resultCode'] ?? -999);
        $msgB  = (string) ($jsonB['message'] ?? 'MoMo refund trả về lỗi.');
        if ($codeB === 0) {
            return RefundResult::ok($refundOrderId, $jsonB, $amount, 1);
        }

        return RefundResult::fail($msgB, $jsonB, $codeB);

    } catch (\Throwable $e) {
        Log::error('[MoMoRefund] exception', [
            'order_id' => $order->id ?? null,
            'error'    => $e->getMessage(),
            'trace'    => $e->getTraceAsString(),
        ]);
        return RefundResult::fail('Lỗi khi gọi hoàn tiền MoMo.', $e->getMessage());
    }
}


    /**
     * Query MoMo lấy transId theo orderId (payment_ref)
     * POST /v2/gateway/api/query
     * rawSignature = accessKey=&orderId=&partnerCode=&requestId=
     */
    protected function queryMomoTransIdByOrderId(string $orderId): ?string
    {
        $cfg         = config('services.momo');
        $partnerCode = (string)($cfg['partner_code'] ?? '');
        $accessKey   = (string)($cfg['access_key']   ?? '');
        $secretKey   = (string)($cfg['secret_key']   ?? '');
        $queryUrl    = (string)($cfg['query_url']    ?? '');

        if (!$partnerCode || !$accessKey || !$secretKey || !$queryUrl || !$orderId) {
            Log::warning('[MoMo] query missing config/orderId', compact('partnerCode','accessKey','queryUrl','orderId'));
            return null;
        }

        $requestId = 'q-' . $orderId . '-' . now()->timestamp;

        $raw = sprintf(
            'accessKey=%s&orderId=%s&partnerCode=%s&requestId=%s',
            $accessKey, $orderId, $partnerCode, $requestId
        );
        $signature = hash_hmac('sha256', $raw, $secretKey);

        $payload = [
            'partnerCode' => $partnerCode,
            'requestId'   => $requestId,
            'orderId'     => $orderId,
            'lang'        => 'vi',
            'signature'   => $signature,
        ];

        try {
            $res = Http::timeout(30)->retry(2, 200)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($queryUrl, $payload);

            if (!$res->ok()) {
                Log::warning('[MoMo] query HTTP fail', ['status' => $res->status(), 'body' => (string)$res->body()]);
                return null;
            }

            $json = $res->json() ?: [];
            $tid  = $json['transId'] ?? null;
            return $tid ? (string)$tid : null;

        } catch (\Throwable $e) {
            Log::error('[MoMo] query exception', ['orderId' => $orderId, 'err' => $e->getMessage()]);
            return null;
        }
    }
}
