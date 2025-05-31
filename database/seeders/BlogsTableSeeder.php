<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BE\Blog;
use Illuminate\Support\Str;

class BlogsTableSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            'Giới thiệu Laravel cơ bản',
            'Cách tạo REST API với Laravel',
            'Hướng dẫn xác thực người dùng trong Laravel',
            'Tối ưu hiệu năng ứng dụng Laravel',
            'Triển khai Laravel trên server thật',
        ];

        foreach ($titles as $i => $title) {
            Blog::create([
                'title'     => $title,
                'slug'      => Str::slug($title),
                'content'   => "Đây là nội dung chi tiết của bài viết: $title.",
                'thumbnail' => "https://picsum.photos/seed/blog$i/600/400",
            ]);
        }
    }
}
