<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileClientController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('frontend.profile-user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'digits:10', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:2048'], // max 2MB
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Họ tên là bắt buộc.',
            'name.string' => 'Họ tên phải là chuỗi ký tự.',
            'name.max' => 'Họ tên không được dài quá 255 ký tự.',

            'phone.digits' => 'Số điện thoại phải đúng 10 chữ số.',
            'phone.unique' => 'Số điện thoại đã được sử dụng.',

            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã được sử dụng.',

            'avatar.image' => 'Ảnh đại diện phải là file hình ảnh.',
            'avatar.max' => 'Ảnh đại diện không được lớn hơn 2MB.',

            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Cập nhật thông tin
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;

        // Nếu có avatar mới thì lưu file
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu cần
            if ($user->avatar) {
                Storage::delete('public/' . $user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        // Nếu có đổi mật khẩu
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        /** @var \App\Models\User $user */
        $user->save();

        return redirect()->route('index')->with('success', 'Cập nhật hồ sơ thành công!');
    }
}
