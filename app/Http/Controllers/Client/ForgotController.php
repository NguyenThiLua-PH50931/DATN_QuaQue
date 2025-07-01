<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ForgotController extends Controller
{
    public function forgot()
    {
        return view('frontend.profile-user.forgot');
    }
    public function sendResetLink(Request $request)
    {
        // Validate email
        $request->validate(['email' => 'required|email']);

        // Gửi link reset mật khẩu
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Kiểm tra kết quả gửi mail
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(['status' => 'Email đặt lại mật khẩu đã được gửi. Vui lòng kiểm tra hộp thư!']);
        } else {
            return back()->withErrors(['email' => 'Không tìm thấy email hoặc có lỗi xảy ra. Vui lòng thử lại!']);
        }
    }
}
