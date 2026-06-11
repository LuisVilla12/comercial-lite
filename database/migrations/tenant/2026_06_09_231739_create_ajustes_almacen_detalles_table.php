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
        Schema::create('ajustes_almacen_detalles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
             $table->foreignId(column: 'ajustes_almacen_id')->constrained('ajustes_almacens')->cascadeOnDelete();
            $table->foreignId(column: 'producto_id')->constrained(table: 'productos')->cascadeOnDelete();
            $table->integer(column: 'cantidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajustes_almacen_detalles');
    }
};
