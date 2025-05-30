<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'phone'      => 'required|digits_between:10,12',
            'role'       => 'required|in:admin,member', // tuỳ vào hệ thống
            'password'   => 'required|min:6|confirmed',
            'avatar'     => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

      public function messages(): array
    {
        return [
            'name.required'      => 'Vui lòng nhập họ và tên.',
            'email.required'     => 'Vui lòng nhập email.',
            'email.email'        => 'Email không hợp lệ.',
            'email.unique'       => 'Email đã tồn tại.',
            'phone.required'     => 'Vui lòng nhập số điện thoại.',
            'phone.digits_between' => 'Số điện thoại phải từ 10 đến 12 chữ số.',
            'role.required'      => 'Vui lòng chọn vai trò.',
            'role.in'            => 'Vai trò không hợp lệ.',
            'password.required'  => 'Vui lòng nhập mật khẩu.',
            'password.min'       => 'Mật khẩu tối thiểu 6 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
            'avatar.image'       => 'File phải là ảnh.',
            'avatar.mimes'       => 'Định dạng ảnh phải là jpeg, png, jpg hoặc gif.',
            'avatar.max'         => 'Ảnh không được vượt quá 2MB.',
        ];
    }
}
