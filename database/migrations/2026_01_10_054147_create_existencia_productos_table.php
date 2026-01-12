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
        Schema::create('existencia_productos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId(column: 'almacen_id')->constrained(table: 'almacens');
            $table->foreignId(column: 'producto_id')->constrained('productos')->cascadeOnDelete();
            $table->integer('cantidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('existencia_productos');
    }
};
