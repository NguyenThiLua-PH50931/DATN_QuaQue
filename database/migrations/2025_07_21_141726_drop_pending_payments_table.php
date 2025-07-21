<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('pending_payments');
    }

    public function down()
    {
        // Nếu muốn rollback, có thể để trống hoặc copy lại cấu trúc bảng cũ vào đây nếu cần
    }
};
