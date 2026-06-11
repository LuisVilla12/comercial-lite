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
        Schema::create('codigos_postales', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('d_codigo', 10)->index();
            $table->string('d_asenta', 255);
            $table->string('d_tipo_asenta', 100);
            $table->string('d_mnpio', 255);
            $table->string('d_estado', 255);
            $table->string('d_ciudad', 255)->nullable();
            $table->string('d_cp', 10)->nullable();
            $table->string('c_estado', 10);
            $table->string('c_oficina', 10)->nullable();
            $table->string('c_cp', 10)->nullable();
            $table->string('c_tipo_asenta', 10);
            $table->string('c_mnpio', 10);
            $table->string('id_asenta_cpcons', 20);
            $table->string('d_zona', 50)->nullable();
            $table->string('c_cve_ciudad', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('codigos_postales');
    }
};
