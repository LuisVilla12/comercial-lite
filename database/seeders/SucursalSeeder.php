<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Hash;

class SucursalSeeder extends Seeder
{
    public function run(): void
    {
        Sucursal::insert([
    [
        'codigo' => ' 001',
        'nombre' => ' Centro',
        'serie_cotizacion' => 'CEN-C',
        'serie_remision'   => 'CEN-R',
        'serie_factura'    => 'CEN-F',
    ],
    [
        'codigo' => ' 002',
        'nombre' => ' Norte',
        'serie_cotizacion' => 'NOR-C',
        'serie_remision'   => 'NOR-R',
        'serie_factura'    => 'NOR-F',
    ],
]);
    }
}
