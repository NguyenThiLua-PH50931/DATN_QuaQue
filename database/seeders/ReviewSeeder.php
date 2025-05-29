<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
                'user_id'    => 4,
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
