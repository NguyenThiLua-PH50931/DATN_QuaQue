<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\admin\Order;
use App\Models\admin\OrderItem;
use App\Models\admin\Product;
use App\Models\admin\ProductVariant;
use App\Models\admin\DiscountCode;
use App\Models\Address;
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

        $users = User::has('addresses')->get();
        $products = Product::with('firstImage')->has('variants')->get();
        $now = now();
        $discounts = DiscountCode::where('active', 1)
            ->where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->whereColumn('used_count', '<', 'usage_limit')
            ->get()
            ->filter->isValid()
            ->values();

        if ($users->count() < 2 || $products->count() < 2) {
            $this->command->warn('⚠️ Cần ít nhất 2 users có địa chỉ và 2 sản phẩm có biến thể để seed.');
            return;
        }

        // Chọn số sản phẩm cho từng đơn: [1, 2] rồi shuffle cho random vị trí
        $productsCountArr = [1, 2];
        shuffle($productsCountArr);

        $ordersInfo = []; // Lưu info từng order để sau cùng xét áp discount

        for ($i = 0; $i < 2; $i++) {
            $user = $users->random();
            $address = $user->addresses()->inRandomOrder()->first();

            $order = Order::create([
                'order_code' => 'ORD' . strtoupper(Str::random(6)),
                'user_id' => $user->id,
                'address_id' => $address->id,
                'shipping_method_id' => 1,
                'discount_code_id' => null,
                'discount_amount' => 0,
                'total_amount' => 0,
                'shipping_cost' => 20000,
                'status' => 'pending',
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
                'is_hidden' => 0,
            ]);

            $numProducts = $productsCountArr[$i];
            $selectedProducts = $products->random($numProducts);

            $subtotal = 0;

            foreach ($selectedProducts as $product) {
                $variant = $product->variants()->inRandomOrder()->first();
                if (!$variant) continue;

                $quantity = rand(1, 3);
                $price = $variant->price ?? 10000;
                $itemTotal = $price * $quantity;

                $image = optional($product->firstImage)->image_url
                    ? str_replace(asset('storage/'), '', optional($product->firstImage)->image_url)
                    : 'images/no-image.png';

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_variant_value_id' => $variant->id,
                    'product_variant_value_name' => $variant->name,
                    'product_sku' => $variant->sku,
                    'product_image' => $image,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $itemTotal,
                ]);

                $subtotal += $itemTotal;
            }

            // Ghi lại info cho bước áp discount
            $ordersInfo[] = [
                'order' => $order,
                'subtotal' => $subtotal,
            ];
        }

        // Bước 2: Xác định đơn nào đủ điều kiện áp discount
        $eligibleIndexes = [];
        foreach ($ordersInfo as $idx => $orderInfo) {
            if ($discounts->isNotEmpty()) {
                foreach ($discounts as $discountCode) {
                    if ($orderInfo['subtotal'] >= $discountCode->min_order_amount && $discountCode->isValid()) {
                        $eligibleIndexes[] = $idx;
                        break;
                    }
                }
            }
        }

        // Bước 3: Áp discount ngẫu nhiên cho 1 đơn đủ điều kiện (nếu có)
        if (!empty($eligibleIndexes)) {
            $chosenIdx = $eligibleIndexes[array_rand($eligibleIndexes)];
            $order = $ordersInfo[$chosenIdx]['order'];
            $subtotal = $ordersInfo[$chosenIdx]['subtotal'];
            $discountCode = $discounts->random();
            $discountAmount = $discountCode->calculateDiscount($subtotal);

            $order->discount_code_id = $discountCode->id;
            $order->discount_amount = $discountAmount;
            $order->total_amount = max(0, $subtotal - $discountAmount + $order->shipping_cost);
            $order->save();

            $discountCode->increment('used_count');
        }

        // Các đơn còn lại cập nhật total_amount (nếu chưa có)
        foreach ($ordersInfo as $idx => $orderInfo) {
            $order = $orderInfo['order'];
            $subtotal = $orderInfo['subtotal'];

            if (!$order->discount_code_id) {
                $order->total_amount = max(0, $subtotal + $order->shipping_cost);
                $order->save();
            }
        }

        $this->command->info('✅ Đã seed thành công 2 đơn hàng: 1 đơn 2 sản phẩm, 1 đơn 1 sản phẩm, và ngẫu nhiên 1 đơn hợp lệ sẽ được áp mã giảm giá.');
    }
}
