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
        Schema::create('compras_detalles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId(column: 'compra_id')->constrained('compras')->cascadeOnDelete();
            $table->foreignId(column: 'producto_id')->constrained('productos')->cascadeOnDelete();
            $table->integer(column: 'cantidad');
            $table->decimal(column: 'costo_unitario');
            $table->decimal(column: 'importe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras_detalles');
    }
};
