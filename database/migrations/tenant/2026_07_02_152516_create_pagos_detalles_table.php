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
        Schema::create('pagos_detalles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId(column: 'pago_id')->constrained('pagos')->cascadeOnDelete();
            $table->foreignId(column: 'documento_id')->constrained('documentos')->cascadeOnDelete();
            $table->decimal('monto', 12, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_detalles');
    }
};
