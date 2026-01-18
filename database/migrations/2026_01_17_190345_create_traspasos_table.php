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
        Schema::create('traspasos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('serie')->nullable();
            $table->integer('folio')->nullable();
            $table->date(column: 'fecha');
            $table->foreignId(column: 'almacen_origen_id')->constrained('almacens');
            $table->foreignId('almacen_destino_id')->constrained('almacens');
            $table->tinyInteger('estatus')->default(1); // 1=pendiente, 2=aplicado, 3=cancelado
            $table->foreignId(column: 'user_id')->constrained(table: 'users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('traspasos');
    }
};
