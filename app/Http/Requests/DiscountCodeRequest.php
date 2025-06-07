<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiscountCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Cho phép tất cả request
    }

    public function rules()
    {
        return [
            'description' => 'required|string|max:255',
            'code' => 'required|string|unique:discount_codes,code|max:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'free_shipping' => 'sometimes|accepted',
            'usage_limit' => 'required|integer|min:1',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'active' => 'sometimes|accepted',
            'min_order_amount' => 'nullable|numeric|min:0',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'used_count' => 'nullable|integer|min:0',
        ];
    }

    public function attributes()
    {
        return [
            'description' => 'mô tả',
            'code' => 'mã giảm giá',
            'start_date' => 'ngày bắt đầu',
            'end_date' => 'ngày kết thúc',
            'free_shipping' => 'miễn phí vận chuyển',
            'usage_limit' => 'giới hạn sử dụng',
            'discount_type' => 'loại giảm giá',
            'discount_value' => 'giá trị giảm',
            'active' => 'trạng thái',
            'min_order_amount' => 'giá trị đơn tối thiểu',
            'max_discount_amount' => 'giảm tối đa',
            'used_count' => 'số lần đã dùng',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Trường :attribute là bắt buộc.',
            'string' => 'Trường :attribute phải là chuỗi.',
            'max' => 'Trường :attribute không được vượt quá :max ký tự.',
            'min' => 'Trường :attribute phải lớn hơn hoặc bằng :min.',
            'numeric' => 'Trường :attribute phải là số.',
            'integer' => 'Trường :attribute phải là số nguyên.',
            'date' => 'Trường :attribute phải là ngày hợp lệ.',
            'after_or_equal' => 'Trường :attribute phải sau hoặc bằng ngày bắt đầu.',
            'in' => 'Giá trị của trường :attribute không hợp lệ.',
            'unique' => 'Trường :attribute đã tồn tại.',
            'accepted' => 'Trường :attribute phải được chọn.',
        ];
    }
}
