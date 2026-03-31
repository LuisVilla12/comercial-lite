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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // $table->integer('id_producto')->nullable();
            // $table->string('codigo_producto')->unique();
            $table->string('codigo_producto');
            $table->string('nombre_producto');
            $table->string('tipo_producto')->nullable();
            $table->decimal('peso_producto', 15, 4)->nullable()->default(0);
            $table->integer('estatus_producto')->nullable()->default(1);
            $table->integer('unidad_medida')->nullable()->default(0);
            $table->integer('impuesto1')->nullable()->default(0);
            $table->integer('retencion1')->nullable()->default(0);
            $table->foreignId(column: 'valor_clasificacion1')->nullable()->constrained('clasificacions')->nullOnDelete();
            $table->integer('valor_clasificacion2')->nullable()->default(0);
            $table->decimal('importe_extra', 15, 2)->nullable()->default(0);
            $table->decimal('precio1', 15, 4);
            $table->decimal('precio2', 15, 4)->nullable()->default(0);
            $table->decimal('precio3', 15, 4)->nullable()->default(0);
            $table->decimal('precio4', 15, 4)->nullable()->default(0);
            $table->decimal('precio5', 15, 4)->nullable()->default(0);
            $table->decimal('precio_calculado', 15, 4)->nullable()->default(0);
            $table->decimal('volumen', 15, 4)->nullable()->default(0);
            $table->integer('exento_impuesto')->nullable()->default(0);
            $table->string('codigo_alterno')->nullable();
            $table->string('clave_sat')->nullable();
            $table->string('marca')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
