<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\admin\Order;
use App\Models\admin\OrderItem;
use App\Models\admin\Product;
use App\Models\admin\ProductVariant;
use App\Models\User;

class OrdersWithItemsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::statement('ALTER TABLE order_items AUTO_INCREMENT = 1');

        $products = Product::with('firstImage')->get();
        $users = User::all();

        if ($products->count() < 1 || $users->count() < 1) {
            $this->command->warn('⚠️ Cần ít nhất 1 sản phẩm và 1 user để seed.');
            return;
        }

        for ($i = 0; $i < 3; $i++) {
            $order = Order::create([
                'order_code' => 'ORD' . strtoupper(Str::random(6)),
                'user_id' => $users->random()->id,
                'address_id' => 1,
                'shipping_method_id' => 1,
                'discount_code_id' => null,
                'total_amount' => 0,
                'shipping_cost' => 20000,
                'status' => 'pending',
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'receiver_name' => 'Test User ' . $i,
                'receiver_phone' => '09' . rand(10000000, 99999999),
            ]);

            $selectedProducts = $products->random(rand(1, 2));
            $totalAmount = 0;

            foreach ($selectedProducts as $product) {
                $variant = ProductVariant::where('product_id', $product->id)->inRandomOrder()->first();

                if (!$variant) {
                    $this->command->warn("⚠️ Không có biến thể cho sản phẩm ID {$product->id}");
                    continue;
                }

                $quantity = rand(1, 3);
                $price = $variant->price ?? 10000;
                $discount = rand(0, 5000);
                $itemTotal = ($price - $discount) * $quantity;
                $image = optional($product->firstImage)->image_url 
                ? str_replace(asset('storage/'), '', optional($product->firstImage)->image_url)
                : 'images/no-image.png';


                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $variant->sku,
                    'product_image' => $image,
                    'product_variant_value_id' => $variant->id,
                    'product_variant_value_name' => $variant->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'discount' => $discount,
                    'total' => $itemTotal,
                    'status' => 'pending',
                    'note' => null,
                ]);

                $totalAmount += $itemTotal;
            }

            $order->update([
                'total_amount' => $totalAmount + $order->shipping_cost,
            ]);
        }

        $this->command->info('✅ Đã seed thành công 3 đơn hàng mẫu.');
    }
}
