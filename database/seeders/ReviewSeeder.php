<?php

namespace Database\Seeders;

use App\Models\admin\Review; // Hoặc App\Models\Review nếu bạn không dùng thư mục con "admin"
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset AUTO_INCREMENT nếu cần
        DB::statement('ALTER TABLE reviews AUTO_INCREMENT = 1');

        $reviews = [
            [
                'user_id'    => 2,
                'product_id' => 1,
                'rating'     => 4,
                'comment'    => 'Great product, very comfortable!',
            ],
            [
                'user_id'    => 3,
                'product_id' => 2,
                'rating'     => 2,
                'comment'    => 'Did not meet my expectations.',
            ],
            [
                'user_id'    => 1,
                'product_id' => 3,
                'rating'     => 5,
                'comment'    => 'Absolutely love it! Highly recommend.',
            ],
        ];

        foreach ($reviews as $data) {
            Review::create($data);
        }
    }
}
