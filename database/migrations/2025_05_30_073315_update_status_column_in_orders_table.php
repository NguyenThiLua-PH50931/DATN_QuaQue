<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStatusColumnInOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Chuyển sang dạng string (nếu đang dùng enum hoặc cần mở rộng thêm giá trị)
            $table->string('status')->default('pending')->change();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Trong down(), bạn có thể revert về enum nếu muốn (ví dụ nếu trước đây dùng enum)
            $table->enum('status', ['pending', 'confirmed', 'shipping', 'completed', 'cancelled'])->default('pending')->change();
        });
    }
}
