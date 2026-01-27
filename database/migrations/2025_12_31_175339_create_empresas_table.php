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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'codigo');
            $table->string(column: 'nombre');
            $table->string('rfc', 20);
            $table->string(column: 'regimen_fiscal')->nullable();
            $table->string('curp', 18)->nullable();
            $table->string(column: 'email')->nullable();
            $table->string(column: 'telefono')->nullable();
            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
