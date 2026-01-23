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
        Schema::create('devolucions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId(column: 'documento_id')->constrained('documentos')->onDelete('cascade');
            $table->foreignId(column: 'cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId(column: 'user_id')->constrained(table: 'users');
            $table->string(column: 'serie')->nullable();
            $table->integer('folio')->nullable();
            $table->date(column: 'fecha')->nullable();
            $table->decimal('total', 15, 4)->nullable();
            $table->text('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devolucions');
    }
};
