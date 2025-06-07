<?php

namespace Database\Seeders;

use App\Models\admin\Product;
use App\Models\admin\Review; // Hoặc App\Models\Review nếu bạn không dùng thư mục con "admin"
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
    {
        $products = Product::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();

        if (empty($products) || empty($users)) {
            $this->command->info('Không có sản phẩm hoặc user để tạo review.');
            return;
        }

        // Tạo một review mẫu
        Review::create([
            'user_id' => $users[0], // user đầu tiên
            'product_id' => $products[0], // product đầu tiên
            'rating' => 4,
            'comment' => 'Sản phẩm chất lượng, đáng tiền!',
        ]);

        Review::create([
            'user_id' => $users[1] ?? $users[0], // user thứ 2 hoặc user đầu tiên
            'product_id' => $products[1] ?? $products[0], // product thứ 2 hoặc product đầu tiên
            'rating' => 2,
            'comment' => 'Did not meet my expectations.',
        ]);
    }
}
