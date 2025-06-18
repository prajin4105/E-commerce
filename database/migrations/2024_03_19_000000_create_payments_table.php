<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('payment_id')->nullable(); // For external payment gateway ID
            $table->string('payment_method');
            $table->decimal('amount', 10, 2);
            $table->string('currency')->default('INR');
            $table->string('status');
            $table->string('transaction_id')->nullable();
            $table->json('payment_details')->nullable(); // For storing additional payment information
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
}; 