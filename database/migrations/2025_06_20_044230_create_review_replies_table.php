<?php

// database/migrations/2024_06_20_000000_create_review_replies_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewRepliesTable extends Migration
{
    public function up()
    {
        Schema::create('review_replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('review_id');
            $table->unsignedBigInteger('admin_id');
            $table->text('reply');
            $table->timestamps();

            // Ràng buộc khóa ngoại
            $table->foreign('review_id')->references('id')->on('reviews')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('review_replies');
    }
}
