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
        Schema::create('promociones', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('codigo');
            $table->string('nombre');
            $table->enum('tipo', ['PORCENTAJE', 'PRECIO']);
            $table->decimal('valor', 5, 2)->nullable(); // solo porcentaje
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('estatus')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promociones');
    }
};
