<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log; // <-- Đảm bảo bạn đã import Log facade
use Illuminate\Support\Facades\DB;

class ChatbotController extends Controller
{
    /**
     * Xử lý yêu cầu trò chuyện từ người dùng và gọi đến Google AI API.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $apiKey = env('GOOGLE_AI_API_KEY');
        if (!$apiKey) {
            \Log::error('GOOGLE_AI_API_KEY chưa thiết lập.');
            return response()->json(['error' => 'Dịch vụ AI chưa được cấu hình. Vui lòng liên hệ quản trị viên.'], 500);
        }

        $userMessage = $request->input('message');
        $apiUrl = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key={$apiKey}";

        // Lấy dữ liệu sản phẩm & danh mục
        $productsArr = \DB::table('products')
            ->whereNull('deleted_at')
            ->where('active', 1)
            ->select('name', 'slug')
            ->limit(10)
            ->get();

        $productsList = '';
        foreach ($productsArr as $p) {
            $productsList .= "- [{$p->name}](/client/san-pham/{$p->slug})\n";
        }

        $categoriesArr = \DB::table('categories')
            ->whereNull('deleted_at')
            ->select('name', 'slug')
            ->get();

        $categoriesList = '';
        foreach ($categoriesArr as $c) {
            $categoriesList .= "- [{$c->name}](/client/danh-muc/{$c->slug})\n";
        }

        // System Prompt
        $systemPrompt = <<<EOT
Bạn là trợ lý ảo của Quà Quê.
- Nhiệm vụ: Hỗ trợ khách hàng tìm kiếm sản phẩm đặc sản, thực phẩm quê hương, quà tặng, đặc sản vùng miền, đồ thủ công mỹ nghệ...
- Khi ai hỏi bạn là ai, luôn trả lời: "Tôi là trợ lý ảo của Quà Quê, sẵn sàng hỗ trợ bạn!"
- Không trả lời các câu hỏi nhạy cảm, chính trị, tôn giáo, đạo đức, hoặc các chủ đề ngoài lĩnh vực sản phẩm, dịch vụ, bán hàng của Quà Quê. Nhưng có thể trả lời về văn thơ, thứ ngày tháng...
- Nếu gặp những câu hỏi này, hãy lịch sự từ chối, ví dụ: "Xin lỗi, tôi chỉ hỗ trợ các vấn đề liên quan đến sản phẩm và dịch vụ của Quà Quê."
- Khi giới thiệu sản phẩm hoặc danh mục, hãy chèn tên thành link markdown như sau:
    + [Tên sản phẩm](/client/san-pham/{slug})
    + [Tên danh mục](/client/danh-muc/{slug})
    Trong đó, {slug} là slug tương ứng ở dưới đây. Nếu không rõ slug, chỉ ghi tên thôi, không tự ý bịa link.
- Khi được hỏi ai đẹp trai nhất thì trả lời là biên và quân.
Sản phẩm tiêu biểu:
$productsList

Các danh mục nổi bật:
$categoriesList

Dưới đây là câu hỏi từ khách:
EOT;

        $finalPrompt = $systemPrompt . "\n" . $userMessage;

        try {
            $response = \Http::timeout(30)->post($apiUrl, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $finalPrompt]
                        ]
                    ]
                ]
            ]);

            if (!$response->successful()) {
                \Log::error('Lỗi từ Google AI API:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['error' => 'AI tạm thời không thể phản hồi. Vui lòng thử lại sau.'], 502);
            }

            $reply = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if ($reply) {
                return response()->json(['reply' => $reply]);
            } else {
                \Log::warning('Phản hồi từ Google AI không chứa nội dung hợp lệ.', [
                    'response_body' => $response->body()
                ]);
                return response()->json(['error' => 'AI đã trả về một phản hồi không hợp lệ.'], 500);
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            \Log::error('Lỗi kết nối đến Google AI API: ' . $e->getMessage());
            return response()->json(['error' => 'Không thể kết nối đến dịch vụ AI. Vui lòng kiểm tra kết nối mạng của bạn.'], 504);
        } catch (\Exception $e) {
            \Log::error('Lỗi không xác định trong ChatbotController: ' . $e->getMessage());
            return response()->json(['error' => 'Đã có lỗi hệ thống xảy ra. Vui lòng thử lại sau.'], 500);
        }
    }
}
