<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('comment_replies', function (Blueprint $table) {
            // thêm admin_id nullable
            if (!Schema::hasColumn('comment_replies', 'admin_id')) {
                $table->unsignedBigInteger('admin_id')->nullable()->after('comment_id');
            }
            // nếu user_id hiện NOT NULL, đổi thành nullable
            if (Schema::hasColumn('comment_replies', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->change();
            }
        });
    }

    public function down()
    {
        Schema::table('comment_replies', function (Blueprint $table) {
            if (Schema::hasColumn('comment_replies', 'admin_id')) {
                $table->dropColumn('admin_id');
            }
            // revert user_id to not nullable (cẩn thận: chỉ làm nếu bạn muốn)
            // $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
