<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\admin\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
=======
use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
        DB::statement('ALTER TABLE reviews AUTO_INCREMENT = 1');
=======
>>>>>>> 4462985ab5e2de3f6f036916a5fd1082cbb78391
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
