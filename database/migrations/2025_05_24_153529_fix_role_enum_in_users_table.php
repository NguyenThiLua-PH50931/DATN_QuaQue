<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void {
        // Đảm bảo dữ liệu hợp lệ trước
        DB::statement("UPDATE users SET role = 'member' WHERE role = 'seller'");

        // Thay đổi enum
        DB::statement("ALTER TABLE users MODIFY role ENUM('member', 'admin') DEFAULT 'member'");
    }

    public function down(): void {
        DB::statement("ALTER TABLE users MODIFY role ENUM('member', 'seller', 'admin') DEFAULT 'member'");
    }
};
