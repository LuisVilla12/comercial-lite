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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'codigo');
            $table->string(column: 'nombre');
            $table->string('rfc', 20);
            $table->string('curp', 18)->nullable();
            $table->enum('tipo', allowed: ['1', '3'])->default('1');
            $table->string(column: 'email1')->nullable();
            $table->string(column: 'email2')->nullable();
            $table->string(column: 'regimen_fiscal');
            $table->string('telefono')->nullable();
            $table->string('whatsapp')->nullable();
            $table->boolean('activo')->default(true);

        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
