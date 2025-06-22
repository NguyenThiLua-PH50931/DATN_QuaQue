<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReceived; 

class ContactController extends Controller
{
    public function lienhe()
    {
        return view('frontend.contact-us');
    }
    public function submit(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:15',
            'message' => 'required|string|max:1000',
        ], [
            'first_name.required' => 'Vui lòng nhập họ của bạn.',
            'last_name.required' => 'Vui lòng nhập tên của bạn.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'message.required' => 'Vui lòng nhập lời nhắn.',
        ]);
        
        // Email được gửi tới:
        Mail::to('luantph50931@gmail.com')->send(new ContactReceived($request->all()));

        // Trả về trang hoặc redirect với thông báo thành công
        return back()->with('success', 'Cảm ơn bạn đã liên hệ. Chúng tôi sẽ phản hồi sớm nhất!');
    }
}
