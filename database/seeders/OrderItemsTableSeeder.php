<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\admin\Order;
use App\Models\admin\Product;

class OrderItemsTableSeeder extends Seeder
{
   public function run()
{
    DB::statement('ALTER TABLE order_items AUTO_INCREMENT = 1');

    $orders = Order::inRandomOrder()->take(5)->get();
    $products = Product::inRandomOrder()->take(10)->get();

    foreach ($orders as $order) {
        $productsCount = $products->count();
        $numberToPick = rand(2, 3);
        if ($productsCount < $numberToPick) {
            $numberToPick = $productsCount;
        }

        $selectedProducts = $products->random($numberToPick);

        foreach ($selectedProducts as $product) {
            $quantity = rand(1, 5);
            $price = $product->price ?? 10000;
            $discount = rand(0, 5000);
            $total = ($price - $discount) * $quantity;

            DB::table('order_items')->insert([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_sku' => $product->sku ?? null,
                'product_image' => $product->image ?? null,
                'quantity' => $quantity,
                'price' => $price,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
                'note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

}
