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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer(column: 'folio');
            $table->string(column: 'serie')->nullable;
            $table->foreignId(column: 'proveedor_id')->constrained(table: 'clientes');
            $table->foreignId(column: 'almacen_id')->constrained(table: 'almacens');
            $table->integer(column: 'user_id');
            $table->date(column: 'fecha');
            $table->decimal(column: 'subtotal');
            $table->decimal(column: 'impuestos')->default(0);
            $table->decimal(column: 'total');
            $table->integer(column: 'estatus')->default(1); //1 pendiente, 2 recibida, 3 cancelada
            $table->text(column: 'observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
