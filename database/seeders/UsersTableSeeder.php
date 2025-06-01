<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Tạm thời tắt ràng buộc khóa ngoại để tránh lỗi khi truncate
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Xóa sạch bảng users và reset ID về 1
        User::truncate();

        // Bật lại kiểm tra khóa ngoại
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Tạo 3 user mẫu
        User::create([
            'name' => 'Nguyen Van A',
            'email' => 'user1@example.com',
            'phone' => '0912345678',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'avatar' => null,
        ]);

        User::create([
            'name' => 'Le Thi B',
            'email' => 'seller1@example.com',
            'phone' => '0987654321',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'avatar' => null,
        ]);

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '0909090909',
            'password' => Hash::make('adminpass'),
            'role' => 'admin',
            'avatar' => null,
        ]);
    }
}
