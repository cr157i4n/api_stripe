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
        Schema::create('qr_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cashier_id')->constrained('cashiers')->onDelete('cascade');
            $table->text('image_qr');
            $table->enum('status', ['active', 'expired', ])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_devices');
    }
};
