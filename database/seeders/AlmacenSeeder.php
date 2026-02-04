<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Almacen;

class AlmacenSeeder extends Seeder
{
    public function run(): void
    {
        Almacen::insert([
    [
        'codigo' => ' 001',
        'nombre' => ' ALMACEN PRUEBA',
        'tipo'    => 1,
    ],]);
    }
}
