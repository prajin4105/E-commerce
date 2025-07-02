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
            // Drop the old foreign key constraint
            $table->dropForeign(['voucher_id']);
            
            // Rename the column
            $table->renameColumn('voucher_id', 'coupon_id');
            
            // Add the new foreign key constraint
            $table->foreign('coupon_id')->references('id')->on('coupons')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop the new foreign key constraint
            $table->dropForeign(['coupon_id']);
            
            // Rename the column back
            $table->renameColumn('coupon_id', 'voucher_id');
            
            // Add the old foreign key constraint back
            $table->foreign('voucher_id')->references('id')->on('vouchers')->onDelete('set null');
        });
    }
};
