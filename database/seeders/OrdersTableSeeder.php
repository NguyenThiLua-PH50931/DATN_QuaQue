<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\admin\Order;
use App\Models\admin\OrderItem;
use App\Models\admin\Product;
use App\Models\admin\Variant;
use App\Models\User;

class OrdersTableSeeder extends Seeder
{
    public function run(): void
    {
        // 🧹 Xóa dữ liệu cũ và tắt khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $products = Product::all();
        $users = User::all();

        if ($products->count() < 4 || $users->count() < 1) {
            $this->command->warn('⚠️ Cần ít nhất 4 sản phẩm và 1 user để seed.');
            return;
        }

        // 🔄 Tạo 4 đơn hàng
        for ($i = 0; $i < 4; $i++) {
            $order = Order::create([
                'order_code' => 'ORD' . strtoupper(Str::random(6)),
                'user_id' => $users->random()->id,
                'address_id' => 1,
                'shipping_method_id' => 1,
                'discount_code_id' => null,
                'total_amount' => 0, // cập nhật sau
                'shipping_cost' => 20000,
                'status' => 'pending',
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'receiver_name' => 'Test User ' . $i,
                'receiver_phone' => '09' . rand(10000000, 99999999),
            ]);

            $selectedProducts = $products->random(2);
            $total = 0;

            foreach ($selectedProducts as $product) {
                $variant = Variant::where('product_id', $product->id)->inRandomOrder()->first();

                $price = $variant?->price ?? $product->price ?? 100000;
                $quantity = rand(1, 3);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'variant_id' => $variant?->id,
                    'product_name' => $product->name,
                    'variant_name' => $variant?->name ?? null,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total' => $price * $quantity, // 👈 thêm dòng này
                ]);


                $total += $price * $quantity;
            }

            $order->update([
                'total_amount' => $total + $order->shipping_cost,
            ]);
        }

        $this->command->info('✅ Đã seed thành công 4 đơn hàng mẫu.');
    }
}
