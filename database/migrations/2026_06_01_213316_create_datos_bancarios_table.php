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
        Schema::create('datos_bancarios', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nombre_banco');
            $table->string('cuenta_bancaria');
            $table->string('clabe');
            $table->string('correo_electronico');
            $table->string('whatsapp');
            $table->boolean('predeterminado')->default(false);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_bancarios');
    }
};
