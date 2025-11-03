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
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->string('cod_cotizacion')->unique();
            $table->boolean('estado')->default(1); // 0: anulado, 1: en espera, 2: aceptado
            $table->decimal('igv', 10, 2);
            $table->decimal('total', 10, 2);
            $table->integer('dias_valido');
            $table->foreignId('cliente_id')->contrained('clientes')->cascadeOnDelete();
            $table->foreignId('user_id')->contrained('clientes')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
