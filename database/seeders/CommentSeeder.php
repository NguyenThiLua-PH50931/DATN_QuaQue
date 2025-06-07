<?php

namespace Database\Seeders;

use App\Models\admin\Comment;
use App\Models\admin\Product;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
   public function run(): void
    {
        // Lấy id các sản phẩm có sẵn trong DB
        $products = Product::pluck('id')->toArray();

        // Kiểm tra có ít nhất 2 sản phẩm để tạo comment
        if (count($products) < 2) {
            $this->command->info('Không đủ sản phẩm để tạo comment.');
            return;
        }

        // Tạo comment với product_id lấy từ DB (giả sử lấy 2 sản phẩm đầu)
        Comment::create([
            'user_id' => 2, // hoặc lấy từ DB nếu bạn muốn random
            'product_id' => $products[0], // sản phẩm đầu tiên
            'content' => 'Sản phẩm rất ngon, giao hàng nhanh!',
        ]);

        Comment::create([
            'user_id' => 3,
            'product_id' => $products[1], // sản phẩm thứ hai
            'content' => 'Trà ngon, nhưng giá hơi cao.',
        ]);
    }
}

