<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Xóa hết dữ liệu cũ
       User::query()->delete();

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
            'role' => 'seller',
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
