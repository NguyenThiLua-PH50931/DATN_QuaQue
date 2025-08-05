<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
  public function payWithMomo(Request $request)
{
    $amount = intval($request->input('amount', 10000));
    $orderId = $request->input('orderId', uniqid());

    // LẤY selected_cart_item_ids từ request (bắt buộc truyền từ frontend)
    $selectedIds = $request->input('selected_cart_item_ids', []);
    if (!is_array($selectedIds)) {
        $selectedIds = explode(',', $selectedIds);
    }
    $selectedIds = array_filter(array_map('intval', $selectedIds));
    // Lưu vào session để CheckoutController lấy snapshot sau khi thanh toán thành công
    session(['momo_selected_cart_item_ids' => $selectedIds]);

    // Cấu hình MoMo
    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    $partnerCode = "MOMOSORK20250710_TEST";
    $accessKey = "jVMp4nX1cGAq8sg3";
    $secretKey = "smyMtdwl6o7XmNwZx8v90y0IR6v3Minu";

    $redirectUrl = route('client.checkout'); // Địa chỉ MoMo redirect về
    $ipnUrl = route('client.checkout');      // IPN cũng trả về luôn, cho demo
    $requestId = time() . "";
    $orderInfo = "Thanh toan don hang demo";
    $extraData = "";
    $requestType = "captureWallet";
    $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
    $signature = hash_hmac("sha256", $rawHash, $secretKey);

    $data = [
        'partnerCode' => $partnerCode,
        'partnerName' => "Test",
        'storeId' => "MomoTestStore",
        'requestId' => $requestId,
        'amount' => $amount,
        'orderId' => $orderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature
    ];

    $ch = curl_init($endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    $jsonResult = json_decode($result, true);

    if ($err) {
        return response()->json(['error' => 'CURL error: ' . $err], 500);
    }
    if (empty($jsonResult)) {
        return response()->json(['error' => 'Empty MoMo response', 'debug' => $result], 500);
    }
    if (!isset($jsonResult['payUrl'])) {
        return response()->json(['error' => 'MoMo API error', 'debug' => $jsonResult], 500);
    }

    return response()->json([
        'payUrl' => $jsonResult['payUrl']
    ]);
}


}