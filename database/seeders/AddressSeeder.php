<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\admin\Address;
use Illuminate\Support\Facades\DB;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('ALTER TABLE addresses AUTO_INCREMENT = 1');

        Address::create([
            'user_id'        => 1,
            'recipient_name' => 'Nguyễn Văn A',
            'phone'          => '0901234567',
            'address'        => '123 Đường Lê Lợi, Phường Phú Hội',
            'province'       => 'Thừa Thiên Huế',
            'district'       => 'TP. Huế',
            'is_default'     => true,
        ]);

        Address::create([
            'user_id'        => 2,
            'recipient_name' => 'Trần Thị B',
            'phone'          => '0912345678',
            'address'        => '456 Đường Trần Phú, Phường Bình Thuận',
            'province'       => 'Đà Nẵng',
            'district'       => 'Hải Châu',
            'is_default'     => true,
        ]);
    }
}
