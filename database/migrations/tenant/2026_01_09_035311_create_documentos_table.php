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
            $table->string(column: 'serie')->nullable();
            $table->integer('folio')->nullable();
            $table->date(column: 'fecha')->nullable();
            $table->foreignId(column: 'cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId(column: 'almacen_id')->constrained('almacens')->onDelete('cascade');
            $table->integer(column: 'user_id');
            $table->decimal('subtotal', 15, 4)->nullable();
            $table->decimal('impuestos', 15, 4)->nullable();
            $table->decimal('total', 15, 4)->nullable();
            $table->decimal('descuentos', 15, 4)->nullable();
            $table->string('forma_pago')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->string('uso_cfdi')->nullable();
            // DATOS DEL FACTURAMA
            $table->string('uuid')->nullable()->unique();
            $table->string('cadena_original')->nullable()->unique();
            $table->string('facturama_id')->nullable()->unique();
            $table->string('estado')->default('pendiente'); // pendiente | timbrado | cancelado
            $table->text('observaciones')->nullable();
            $table->date('vigencia')->nullable();
            $table->foreignId(column: 'agente_id')->constrained('agentes')->onDelete('cascade');
            $table->integer(column: 'sucursal_id');
            $table->integer(column: 'estatus')->default(1); //1 pendiente, 2 convertida, 3 cancelada, 4 efectuada
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
