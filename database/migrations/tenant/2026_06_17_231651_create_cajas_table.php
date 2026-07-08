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
        Schema::create('cajas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId(column: 'sucursal_id')->constrained(table: 'sucursales')->cascadeOnDelete();
            $table->integer(column: 'user_id');
            $table->datetime('fecha_apertura');
            $table->decimal('monto_inicial', 12, 2)->default(0);
            $table->datetime('fecha_cierre')->nullable();
            $table->decimal('monto_final', 12, 2)->nullable();
            $table->decimal('total_ventas', 12, 2)->default(0);
            $table->decimal('total_gastos', 12, 2)->default(0);
            $table->integer('total_documentos')->default(0);
            // $table->decimal('diferencia', 12, 2)->default(0);
             // abierta | cerrada
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
                $table->text('observaciones')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cajas');
    }
};
