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
        Schema::dropIfExists('vouchers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is to remove vouchers, so down() would recreate it
        // But we don't want to recreate the old structure
    }
};
