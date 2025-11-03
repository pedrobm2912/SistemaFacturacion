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
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('cod_factura')->unique();
            $table->boolean('estado')->default(1);
            $table->decimal('subtotal', 10, 2);
            $table->enum('moneda', ['dolares', 'soles'])->default('dolares');
            $table->decimal('igv', 10, 2);
            $table->decimal('total', 10, 2);
            $table->text('observaciones');
            $table->foreignId('cliente_id')->contrained('clientes')->cascadeOnDelete();
            $table->foreignId('user_id')->contrained('clientes')->cascadeOnDelete();
            $table->foreignId('cotizacion_id')->contrained('cotizaciones')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
