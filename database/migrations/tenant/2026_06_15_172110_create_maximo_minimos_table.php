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
        Schema::create('maximo_minimos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId(column: 'producto_id')->constrained(table: 'productos')->cascadeOnDelete();
            $table->foreignId(column: 'almacen_id')->constrained(table: 'almacens')->cascadeOnDelete();
            $table->integer(column: 'minimo');
            $table->integer(column: 'maximo');
            $table->string('zona')->nullable();
            $table->string('pasillo')->nullable();
            $table->string('anaquel')->nullable();
            $table->string('repisa')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maximo_minimos');
    }
};
