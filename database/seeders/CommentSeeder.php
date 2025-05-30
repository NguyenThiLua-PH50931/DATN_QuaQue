<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run(): void
    {
        Comment::create([
            'user_id' => 2, // Nguyen Van A
            'product_id' => 1, // Đạc sản mắm tôm Hạ Long
            'content' => 'Sản phẩm rất ngon, giao hàng nhanh!',
        ]);
        Comment::create([
            'user_id' => 3, // Le Thi B
            'product_id' => 2, // Trà Shan Tuyết
            'content' => 'Trà ngon, nhưng giá hơi cao.',
        ]);
    }
}
