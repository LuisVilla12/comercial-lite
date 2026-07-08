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
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->datetime('fecha');
            $table->integer('folio')->default(0);
            $table->string('descripcion');
            $table->decimal('total');
            $table->integer('tipo');//1 Gasto //2 Retiro
            $table->foreignId(column: 'caja_id')->constrained(table: 'cajas')->cascadeOnDelete();
            $table->integer('user_id');
            // $table->foreignId(column: 'sucursal_id')->constrained(table: 'sucursales')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
