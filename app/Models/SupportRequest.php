<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportRequest extends Model
{
   protected $table = 'support_requests'; // Định nghĩa rõ tên bảng
    protected $fillable = ['content', 'created_at']; // Điều chỉnh theo cấu trúc bảng thực tế
}
