<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_intents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('public_id', 20)->unique();
            $table->foreignId('cashier_id')->constrained('cashiers')->onDelete('cascade');
            $table->foreignId('currency_id')->constrained('currencies')->onDelete('cascade');
            $table->string('stripe_payment_intent_id')->unique();
            $table->string('balance_transaction_id')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('description')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('receipt_url')->nullable();
            $table->string('billing_detail')->nullable();
            $table->string('payment_method_detail')->nullable();
            $table->enum('status', ['requires_payment_method', 'requires_confirmation', 'requires_action', 'processing', 'succeeded', 'canceled'])->default('requires_payment_method');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_intents');
    }
};
