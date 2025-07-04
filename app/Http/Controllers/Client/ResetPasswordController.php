<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    // Hiển thị form nhập mật khẩu mới
    public function showResetForm($token)
    {
        return view('frontend.profile-user.reset-password', ['token' => $token]);
    }

    // Xử lý lưu mật khẩu mới
    public function reset(Request $request)
    {
        $request->validate(
            [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
            ],
            [
                'token.required' => 'Mã token là bắt buộc.',
                'email.required' => 'Bạn cần nhập email.',
                'email.email' => 'Địa chỉ email không hợp lệ.',
                'password.required' => 'Bạn cần nhập mật khẩu.',
                'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            ]
        );


        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );
        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', 'Mật khẩu của bạn đã được đặt lại!');
        } else {
            return back()->withErrors(['email' => ['Email không hợp lệ hoặc lỗi khác.']]);
        }
    }
}
