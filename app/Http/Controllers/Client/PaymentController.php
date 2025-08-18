<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class PaymentController extends Controller
{
    // ====== Giữ nguyên MoMo (code của bạn) ======
    public function payWithMomo(Request $request)
{
    // 1) Input
    $amount  = (int) $request->input('amount', 10000);
    if ($amount < 1000) {
        return response()->json(['error' => 'Số tiền phải >= 1000đ'], 422);
    }

    $orderId = (string) ($request->input('orderId') ?: ('QQ' . date('Ymd') . '-' . now()->timestamp));

    // Lưu cart items cho bước sau
    $selectedIds = $request->input('selected_cart_item_ids', []);
    if (!is_array($selectedIds)) $selectedIds = explode(',', $selectedIds);
    $selectedIds = array_filter(array_map('intval', $selectedIds));
    session(['momo_selected_cart_item_ids' => $selectedIds]);

    // 2) Config (KHÔNG hardcode)
    $cfg         = config('services.momo');
    $endpoint    = 'https://test-payment.momo.vn/v2/gateway/api/create';
$partnerCode = trim((string) ($cfg['partner_code'] ?? ''));
$accessKey   = trim((string) ($cfg['access_key']   ?? ''));
$secretKey   = trim((string) ($cfg['secret_key']   ?? ''));


    if (!$partnerCode || !$accessKey || !$secretKey) {
        return response()->json(['error' => 'Thiếu cấu hình MoMo (partner/access/secret)'], 500);
    }

    // 3) URL & params
    $redirectUrl = route('client.checkout');
    // 👉 TẠM thời để ipnUrl = redirectUrl cho chắc (giống code cũ, không cần IPN để tạo QR)
    $ipnUrl      = $redirectUrl;

    $requestId   = (string) now()->timestamp;
    $orderInfo   = 'Thanh toán đơn hàng QQ';
    $extraData   = '';
    $requestType = 'captureWallet';

    // 4) Signature đúng thứ tự theo spec
    $rawHash = sprintf(
        'accessKey=%s&amount=%d&extraData=%s&ipnUrl=%s&orderId=%s&orderInfo=%s&partnerCode=%s&redirectUrl=%s&requestId=%s&requestType=%s',
        $accessKey, $amount, $extraData, $ipnUrl, $orderId, $orderInfo, $partnerCode, $redirectUrl, $requestId, $requestType
    );
    \Log::info('[MoMoCreate] key_tail', ['secret_last' => substr($secretKey, -4)]);

    $signature = hash_hmac('sha256', $rawHash, $secretKey);

    $payload = [
        'partnerCode' => $partnerCode,
        'partnerName' => 'QQ Store',
        'storeId'     => 'QQStore',
        'requestId'   => $requestId,
        'amount'      => $amount,
        'orderId'     => $orderId,   // nhớ sau này lưu vào orders.payment_ref
        'orderInfo'   => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl'      => $ipnUrl,
        'lang'        => 'vi',
        'extraData'   => $extraData,
        'requestType' => $requestType,
        'signature'   => $signature,
    ];

    session(['momo_order_id' => $orderId]);

    // 5) Call API
    $res = \Http::timeout(20)->retry(2, 200)
        ->withHeaders(['Content-Type' => 'application/json'])
        ->post($endpoint, $payload);

    // Log để debug nếu cần
    \Log::info('[MoMoCreate] status='.$res->status(), ['json' => $res->json(), 'text'=>(string)$res->body()]);

    if (!$res->ok()) {
        return response()->json([
            'error' => 'MoMo HTTP '.$res->status(),
            'debug' => (string) $res->body(),
        ], 500);
    }

    $json = $res->json() ?? [];

    // Nhận đủ các key có thể dùng để mở trang thanh toán
    $payUrl = $json['payUrl'] ?? $json['deeplink'] ?? $json['qrCodeUrl'] ?? null;

    if (!$payUrl) {
        return response()->json([
            'error' => 'MoMo API error',
            'debug' => $json,
        ], 500);
    }

    return response()->json(['payUrl' => $payUrl]);
}

    // ====== ZaloPay: Tạo đơn và trả về order_url ======
public function payWithZaloPay(Request $request)
{
    try {
        // ===== 1) Đọc input & validate cơ bản =====
        $amount  = (int) $request->input('amount', 0);
        if ($amount < 1000) {
            return response()->json([
                'error' => 'Số tiền phải >= 1000đ để thanh toán ZaloPay!'
            ], 422);
        }

        $orderId = (string) $request->input('orderId', 'QQ' . date('Ymd') . '-' . time());

        // Nhận list cart item id từ FE (giống MoMo)
        $selectedIds = $request->input('selected_cart_item_ids', []);
        if (!is_array($selectedIds)) {
            $selectedIds = explode(',', $selectedIds);
        }
        $selectedIds = array_filter(array_map('intval', $selectedIds));

        if (empty($selectedIds)) {
            return response()->json([
                'error' => 'Bạn chưa chọn sản phẩm nào để thanh toán!'
            ], 422);
        }

        // Giữ NGUYÊN tên session như MoMo để CheckoutController tái sử dụng
        session(['momo_selected_cart_item_ids' => $selectedIds]);

// ===== 2) ZaloPay sandbox config (dùng config .env) =====
$cfg      = config('services.zlp');
$app_id   = (int) ($cfg['app_id'] ?? 0);
$key1     = (string) ($cfg['key1']  ?? '');
$key2     = (string) ($cfg['key2']  ?? '');
$endpoint = 'https://sb-openapi.zalopay.vn/v2/create';
if (!$app_id || !$key1) {
    return response()->json(['error' => 'Thiếu cấu hình ZaloPay (app_id/key1).'], 500);
}


        // Yêu cầu định dạng của ZLP: yymmdd_xxxxxx
// ===== Sinh mã đơn hiển thị (COD format) =====
$displayOrderCode = 'QQ' . date('Ymd') . '-' . mt_rand(1000, 9999);
session(['pre_order_code' => $displayOrderCode]);

// ===== Sinh app_trans_id từ mã hiển thị (theo format ZLP) =====
$yymmdd = date('ymd');
$base   = preg_replace('/[^A-Za-z0-9_\-]/', '', $displayOrderCode);
$app_trans_id = substr($yymmdd . '_' . $base, 0, 40);
session(['zlp_app_trans_id' => $app_trans_id]);


        $redirectUrl = route('client.checkout'); // không auto success ở đây

        // Có thể nhúng thêm thông tin để IPN đọc được
        $embed_data = json_encode([
            'redirecturl'      => $redirectUrl,
            'user_id'          => auth()->id(),
            'order_id_client'  => $orderId,
        ], JSON_UNESCAPED_UNICODE);

        $item = json_encode([], JSON_UNESCAPED_UNICODE);

        $app_user    = (string) (auth()->id() ?? 'guest');
        $app_time    = (int) round(microtime(true) * 1000); // milliseconds
        $description = "QQ order {$orderId}";
        $bank_code   = 'zalopayapp'; // hiển thị app/QR

        // ===== 3) Tạo MAC theo spec =====
        // HMAC_SHA256(app_id|app_trans_id|app_user|amount|app_time|embed_data|item, key1)
        $mac_data = implode('|', [
            $app_id,
            $app_trans_id,
            $app_user,
            $amount,
            $app_time,
            $embed_data,
            $item
        ]);
        $mac = hash_hmac('sha256', $mac_data, $key1);

        // Payload form-encoded
        $payload = [
            'app_id'       => $app_id,
            'app_user'     => $app_user,
            'app_time'     => $app_time,
            'amount'       => $amount,
            'app_trans_id' => $app_trans_id,
            'embed_data'   => $embed_data,
            'item'         => $item,
            'bank_code'    => $bank_code,
            'description'  => $description,
            'callback_url' => route('client.zalopay.ipn'),
            'mac'          => $mac,
        ];

        // ===== 4) Gọi API tạo đơn =====
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload)); // form-encoded
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $result = curl_exec($ch);
        $err    = curl_error($ch);
        curl_close($ch);

        if ($err) {
            \Log::error('ZLP create CURL error', ['error' => $err]);
            return response()->json(['error' => 'CURL error: ' . $err], 500);
        }

        $json = json_decode($result, true);
// $json đã là mảng
if (empty($json)) {
    \Log::error('ZLP create empty response', ['raw' => $result]);
    return response()->json(['error' => 'Empty ZaloPay response', 'debug' => $result], 500);
}

// ZLP có 2 kiểu key: return_code / returncode (và return_message / returnmessage)
$rc  = isset($json['return_code']) ? (int)$json['return_code'] : (int)($json['returncode'] ?? 0);
$msg = $json['return_message'] ?? ($json['returnmessage'] ?? null);

// ✅ Thành công
if ($rc === 1) {
    $payUrl = $json['order_url']
           ?? $json['orderurl']
           ?? $json['qr_code']
           ?? null;

    if ($payUrl) {
        return response()->json(['payUrl' => $payUrl]);
    }

    \Log::warning('ZLP create success but missing order_url', ['resp' => $json]);
    return response()->json([
        'error' => 'ZaloPay response missing order_url',
        'debug' => $json,
    ], 500);
}

// ❌ Thất bại
\Log::error('ZLP create failed', ['response' => $json]);
return response()->json([
    'error'   => 'ZaloPay API error',
    'message' => $msg ?? 'Unknown error',
    'code'    => $rc,
    'subcode' => $json['sub_return_code'] ?? ($json['subreturncode'] ?? null),
    'debug'   => $json,
], 400);

    } catch (\Throwable $e) {
        \Log::error('ZLP create exception', ['ex' => $e]);
        return response()->json(['error' => 'Server error: ' . $e->getMessage()], 500);
    }
}


    // ====== ZaloPay IPN: verify MAC và trả ACK ======
    public function zalopayIpn(Request $request)
{
    // ZLP có 2 dạng IPN: (1) data + mac (2) các field rời
    $input = $request->all();

$appId = (int) config('services.zlp.app_id');
$key2  = (string) config('services.zlp.key2'); // key dùng verify IPN

    try {
        $payload     = [];
        $calcMac     = '';
        $sentMac     = (string) ($input['mac'] ?? '');

        if (isset($input['data'])) {
            // Dạng 1: data (JSON string) + mac (HMAC256(data, key2))
            $dataStr = (string) $input['data'];
            $payload = json_decode($dataStr, true) ?: [];
            $calcMac = hash_hmac('sha256', $dataStr, $key2);
        } else {
            // Dạng 2: các field rời theo spec IPN v2
            $payload = [
                'app_id'       => $input['app_id']       ?? null,
                'app_trans_id' => $input['app_trans_id'] ?? null,
                'zp_trans_id'  => $input['zp_trans_id']  ?? null,
                'amount'       => $input['amount']       ?? null,
                'server_time'  => $input['server_time']  ?? null,
            ];
            $dataStr = implode('|', [
                $payload['app_id']       ?? '',
                $payload['app_trans_id'] ?? '',
                $payload['zp_trans_id']  ?? '',
                $payload['amount']       ?? '',
                $payload['server_time']  ?? '',
            ]);
            $calcMac = hash_hmac('sha256', $dataStr, $key2);
        }

        if (!$sentMac || !hash_equals($calcMac, $sentMac)) {
            // Sai MAC -> để ZLP retry
            return response()->json(['returncode' => -1, 'returnmessage' => 'mac not match'], 200);
        }

        // Lấy trường cần thiết
        $appTransId = (string) ($payload['app_trans_id'] ?? '');
        $zpTransId  = (string) ($payload['zp_trans_id']  ?? '');
        $amountInt  = (int)    ($payload['amount']       ?? 0);

        if ($appTransId === '' || $zpTransId === '') {
            // Thiếu dữ liệu cốt lõi, vẫn trả 1 tránh retry vô hạn
            \Log::warning('ZLP IPN missing fields', $payload);
            return response()->json(['returncode' => 1, 'returnmessage' => 'OK'], 200);
        }

        // Map về đơn: order_code == app_trans_id
        /** @var \App\Models\admin\Order|null $order */
$order = \App\Models\admin\Order::where('payment_ref', $appTransId)->first();

if (!$order) {
    // Lưu tạm zp_trans_id để lúc redirect tạo Order sẽ pull
    cache()->put('zlp:ipn:' . $appTransId, $zpTransId, 3600);
    \Log::info('ZLP IPN: order not found yet -> cached', [
        'app_trans_id' => $appTransId,
        'zp_trans_id'  => $zpTransId
    ]);
    return response()->json(['returncode' => 1, 'returnmessage' => 'OK'], 200);
}


        // Idempotent: nếu đã paid và đã có zp_trans_id thì coi như xong
        if ($order->payment_status === 'paid' && !empty($order->zp_trans_id)) {
            return response()->json(['returncode' => 1, 'returnmessage' => 'OK'], 200);
        }

        // (Tuỳ chọn) So khớp số tiền: đơn vị VND integer
        $orderAmountInt = (int) round($order->total_amount); // total_amount là decimal(10,2)
        if ($orderAmountInt !== $amountInt) {
            \Log::warning('ZLP IPN amount mismatch', [
                'order_id' => $order->id,
                'order_amount' => $orderAmountInt,
                'ipn_amount' => $amountInt,
            ]);
            // vẫn trả 1 để tránh retry nếu bạn không muốn khoá luồng
            // hoặc trả 0 để ZLP retry (tuỳ chiến lược)
        }

        // Cập nhật thông tin thanh toán
        $order->payment_method = 'zalopay';
        $order->payment_status = 'paid';     // GIỮ status đơn là 'pending' theo yêu cầu
        $order->payment_ref    = $appTransId; // bạn có thể dùng để trace
        $order->zp_trans_id    = $zpTransId;
$order->payment_txn_id = $zpTransId; 
        // Không auto confirm đơn — giữ nguyên $order->status (đang là 'pending')
        $order->save();

        return response()->json(['returncode' => 1, 'returnmessage' => 'OK'], 200);

    } catch (\Throwable $e) {
        \Log::error('ZLP IPN exception', ['message' => $e->getMessage(), 'input' => $input]);
        return response()->json(['returncode' => 0, 'returnmessage' => 'server error'], 200);
    }
}

}
