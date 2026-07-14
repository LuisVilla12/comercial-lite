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
            $table->decimal('saldo_pendiente', 15, 4)->nullable();
            $table->decimal('descuentos', 15, 4)->nullable();
            $table->string('forma_pago')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->string('uso_cfdi')->nullable();
            // Timbrado on line
            $table->string('codigo_unico')->nullable()->unique();
            $table->integer('timbrado_online')->default(0); // 0 pendiente, 1 timbrado
            //CODIGO UTILIZADO
            $table->integer('codigo_utilizado')->default(0);
            // DATOS DEL FACTURAMA
            $table->string('uuid')->nullable()->unique();
            $table->longText('cadena_original')->nullable();
            $table->string('facturama_id')->nullable()->unique();
            $table->string('estado')->default('pendiente'); // pendiente | timbrado | cancelado
            //DATOS DE LA CANCELACION
            $table->string('motivo_cancelacion')->nullable();
            $table->date('fecha_cancelacion')->nullable();
            $table->string('uuid_cancelado')->nullable();
            $table->string('id_cancelado')->nullable();
            $table->string('cancellation_status')->nullable();
            //DATOS EXTRAS
            $table->text('observaciones')->nullable();
            $table->date('vigencia')->nullable();
            $table->foreignId(column: 'agente_id')->constrained('agentes')->onDelete('cascade');
            $table->integer(column: 'sucursal_id');
            $table->integer(column: 'estatus')->default(1);
            //1 pendiente, 2 convertida, 3 cancelada, 4 efectuada



            // $table->unique(
            //     ['sucursal_id', 'documento_modelo_id', 'serie', 'folio'],
            //     'documentos_folio_unique'
            // );
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
