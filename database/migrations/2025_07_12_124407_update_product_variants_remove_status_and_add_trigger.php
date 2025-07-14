<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateProductVariantsRemoveStatusAndAddTrigger extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Xóa cột status nếu có
        Schema::table('product_variants', function (Blueprint $table) {
            if (Schema::hasColumn('product_variants', 'status')) {
                $table->dropColumn('status');
            }
        });

        // Tạo trigger để tự động chuyển active = 0 nếu stock = 0
        DB::unprepared('
            CREATE TRIGGER product_variants_stock_to_active 
            BEFORE UPDATE ON product_variants
            FOR EACH ROW
            BEGIN
                IF NEW.stock = 0 THEN
                    SET NEW.active = 0;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Thêm lại cột status (nếu muốn rollback)
        Schema::table('product_variants', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });

        // Xóa trigger khi rollback
        DB::unprepared('DROP TRIGGER IF EXISTS product_variants_stock_to_active');
    }
}
