<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            UsersTableSeeder::class,                // Tạo user trước
            AddressSeeder::class,                   // Tạo địa chỉ, cần user_id
            ShippingMethodsTableSeeder::class,      // Tạo phương thức giao hàng, cần trước orders
            CategoriesTableSeeder::class,           // Tạo danh mục sản phẩm
            RegionsTableSeeder::class,              // Tạo vùng miền (nếu dùng)
            ProductsTableSeeder::class,             // Tạo sản phẩm, cần categories, regions
            OrdersTableSeeder::class,               // Tạo đơn hàng, cần user, address, shipping_method
            OrderItemsTableSeeder::class,           // Tạo chi tiết đơn hàng, cần orders, products
            CommentSeeder::class,// Thêm comment (nếu có)

            CouponSeeder::class,

        ]);
    }
}
