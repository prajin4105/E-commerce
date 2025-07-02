<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('discount_amount', 8, 2)->default(0)->after('total_amount');
            $table->decimal('final_amount', 8, 2)->default(0)->after('discount_amount');
            $table->unsignedBigInteger('voucher_id')->nullable()->after('final_amount');
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['voucher_id']);
            $table->dropColumn(['discount_amount', 'final_amount', 'voucher_id']);
        });
    }
};
