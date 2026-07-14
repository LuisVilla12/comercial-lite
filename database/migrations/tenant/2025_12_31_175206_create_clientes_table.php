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
            $table->integer('tipo'); // 1=cliente, 2=empleado, 3=proveedor
            $table->string(column: 'email1')->nullable();
            $table->string(column: 'email2')->nullable();
            $table->string(column: 'regimen_fiscal')->nullable();
            $table->string('telefono')->nullable();
            $table->string('whatsapp')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('saldo')->nullable()->default(0);
            $table->decimal('credito', 15, 4)->nullable();
            $table->integer('diasCredito')->nullable()->default(0);
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
