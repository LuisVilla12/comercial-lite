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
        Schema::create('ajustes_almacens', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date(column: 'fecha');
            $table->text(column: 'observaciones')->nullable();
            $table->foreignId(column: 'agente_id')->constrained('agentes')->onDelete('cascade');
            $table->foreignId(column: 'almacen_id')->constrained('almacens')->onDelete('cascade');
            $table->integer(column: 'estatus')->default(1);
            $table->integer(column: 'tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajustes_almacens');
    }
};
