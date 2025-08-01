<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WipeAllTablesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $tables = [
            // 'addresses',
            'products',
            'attributes',
            'attribute_values',
            // 'banners',
            // 'blogs',
            // 'blog_comments',
            // 'cache',
            // 'cache_locks',
            // 'carts',
            // 'cart_items',
            // 'categories',
            // 'comments',
            // 'comment_replies',
            // 'coupon_product',
            // 'discount_codes',
            // 'discount_code_usages',
            // 'failed_jobs',
            // 'wishlist',
            'product_images',
            'product_variant_attribute_values',
            // Thêm các bảng khác bạn chắc chắn có trong DB
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                DB::table($table)->truncate();
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
