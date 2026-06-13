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
        Schema::create('traspasos_detalles', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('traspaso_id')->constrained('traspasos')->cascadeOnDelete();
            $table->foreignId(column: 'producto_id')->constrained(table: 'productos')->cascadeOnDelete();
            $table->integer(column: 'cantidad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traspasos_detalles');
    }
};
