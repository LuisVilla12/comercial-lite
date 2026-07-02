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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('fecha_pago');
            $table->string('forma_pago', 2);
            $table->decimal('monto', 12, 2);
            $table->string('referencia')->nullable();
            $table->text('observaciones')->nullable();
               // Datos del REP
            $table->string('facturama_id')->nullable();
            $table->uuid('uuid')->nullable();
            $table->string('estatus')->default('pendiente');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
