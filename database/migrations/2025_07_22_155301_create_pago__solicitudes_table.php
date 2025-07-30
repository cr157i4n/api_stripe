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
        Schema::create('pago__solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('state');
            $table->integer('amount');
            $table->text('description')->nullable();
            $table->string('type_coin');
            $table->json('tarjeta')->nullable();
            $table->foreignId('id_caja')->constrained('cajas')->onDelete('cascade');
            $table->string('id_stripe')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago__solicitudes');
    }
};