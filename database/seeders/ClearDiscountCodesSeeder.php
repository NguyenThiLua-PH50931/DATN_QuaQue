<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearDiscountCodesSeeder extends Seeder
{
    public function run(): void
    {
        // ⚠️ LƯU Ý: Truncate users sẽ xóa luôn tài khoản admin.
        // Nếu không muốn, hãy comment dòng 'users'.

        // Tắt ràng buộc khóa ngoại (MySQL)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Các bảng phụ/pivot & con trước
        DB::table('coupon_product')->truncate();
        DB::table('product_category')->truncate();
        DB::table('product_variant_attribute_values')->truncate();
        DB::table('product_images')->truncate();
        DB::table('product_searches')->truncate();

        DB::table('cart_items')->truncate();
        DB::table('favorites')->truncate();
        DB::table('wishlist')->truncate();
        DB::table('comments')->truncate();
        DB::table('comment_replies')->truncate();
        DB::table('reviews')->truncate();
        DB::table('order_items')->truncate();
        DB::table('order_status_logs')->truncate();
        DB::table('discount_code_usages')->truncate();
        DB::table('blog_comments')->truncate();
        DB::table('support_ticket_replies')->truncate();

        // Các bảng chính
        DB::table('orders')->truncate();
        DB::table('addresses')->truncate();
        DB::table('carts')->truncate();

        DB::table('products')->truncate();
        DB::table('product_variants')->truncate();

        DB::table('categories')->truncate();
        DB::table('attributes')->truncate();
        DB::table('attribute_values')->truncate();

        DB::table('regions')->truncate();
        DB::table('discount_codes')->truncate();

        DB::table('blogs')->truncate();
        DB::table('banners')->truncate();

        DB::table('support_requests')->truncate();
        DB::table('support_tickets')->truncate();

        DB::table('search_history')->truncate();
        DB::table('search_suggestions')->truncate();

        // Hệ thống/Laravel mặc định (tùy bạn có dùng không)
        DB::table('sessions')->truncate();
        DB::table('jobs')->truncate();
        DB::table('job_batches')->truncate();
        DB::table('failed_jobs')->truncate();
        DB::table('password_reset_tokens')->truncate();
        DB::table('cache')->truncate();
        DB::table('cache_locks')->truncate();

        // Người dùng (comment nếu không muốn xóa)
        DB::table('users')->truncate();

        // Bật lại khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
