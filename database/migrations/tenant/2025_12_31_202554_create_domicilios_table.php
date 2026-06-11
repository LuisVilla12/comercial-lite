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
        Schema::create('domicilios', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string(column: 'pais')->nullable();
            $table->string(column: 'estado')->nullable();
            $table->string(column: 'ciudad')->nullable();
            $table->string(column: 'municipio')->nullable();
            $table->string(column: 'colonia')->nullable();
            $table->string(column: 'calle')->nullable();
            $table->string(column: 'numero_interior')->nullable();
            $table->string(column: 'numero_exterior')->nullable();
            $table->string(column: 'cp')->nullable();
            $table->integer(column: 'tipo')->default(1);//1.-Cliente/proveedores, 2->Sucursales, 3->empresa
            // $table->foreignId(column: 'cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->morphs('domiciliable');
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domicilios');
    }
};
