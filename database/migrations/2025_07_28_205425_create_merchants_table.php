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
        Schema::create('merchants', function (Blueprint $table) {
            $table->id();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->integer('nit')->unique();
            $table->string('address', 45);
            $table->string('phone', 10);
            $table->string('account_username', 75)->unique();
            $table->string('account_password', 75);
            $table->string('image', 255);
            $table->decimal('rate_fixed', 10, 2)->default(0);
            $table->decimal('rate_porcentage', 10, 2)->default(0);
            $table->integer('max_cash_register')->default(1);
            $table->integer('active_cash_register')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
