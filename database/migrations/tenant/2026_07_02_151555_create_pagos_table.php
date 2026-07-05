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
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->date('fecha');
            $table->integer('user_id');
            $table->string('forma_pago');
            $table->foreignId(column: 'cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->decimal('monto', 12, 2);
            $table->text('observaciones')->nullable();
            $table->integer('estatus')->default(1); //1 pendiente, 2 convertida, 3 cancelada, 4 efectuada

               // Datos del REP
            $table->string('facturama_id')->nullable();
            $table->uuid('uuid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
