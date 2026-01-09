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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId(column: 'documento_modelo_id')->constrained('documento_modelos')->onDelete('cascade');
            $table->string('serie')->nullable();
            $table->string('folio')->nullable();
            $table->date('fecha')->nullable();
            $table->foreignId(column: 'cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('nombre_cliente')->nullable();
            $table->string('rfc_cliente')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('afectado')->nullable();
            $table->decimal('neto', 15, 4)->nullable();
            $table->decimal('impuestos', 15, 4)->nullable();
            $table->decimal('retenciones', 15, 4)->nullable();
            $table->decimal('total', 15, 4)->nullable();
            $table->decimal('total_unidades', 15, 4)->nullable();
            $table->string('metodo_pago')->nullable();
            $table->string('usuario')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};
