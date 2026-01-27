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
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            // Datos generales
            $table->string('codigo');
            $table->string('nombre');
            // SERIES
            $table->string('serie_cotizacion', 10);
            $table->string('serie_remision', 10);
            $table->string('serie_factura', 10);
            $table->string('serie_devolucion', 10);

            // FOLIOS
            $table->unsignedBigInteger('folio_cotizacion')->default(0);
            $table->unsignedBigInteger('folio_remision')->default(0);
            $table->unsignedBigInteger('folio_factura')->default(0);
            $table->unsignedBigInteger('folio_devolucion')->default(0);

            // Relación con almacén (opcional pero recomendado)
            $table->foreignId('almacen_id')->nullable()
                  ->constrained()
                  ->nullOnDelete();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sucursales');
    }
};
