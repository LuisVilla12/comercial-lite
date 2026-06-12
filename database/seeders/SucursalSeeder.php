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
        'nombre' => ' ORIZABA',
        'serie_cotizacion' => 'ORI-C',
        'serie_remision'   => 'ORI-R',
        'serie_factura'    => 'ORI-F',
        'serie_devolucion'    => 'ORI-DEV',
        'almacen_id'    => 1,
        'empresa_id'    => 1,
    ],
    [
        'codigo' => ' 004',
        'nombre' => ' ZARAGOZA',
        'serie_cotizacion' => 'ORI-C',
        'serie_remision'   => 'ORI-R',
        'serie_factura'    => 'ORI-F',
        'serie_devolucion'    => 'ORI-DEV',
        'almacen_id'    => 3,
        'empresa_id'    => 1,
    ],
    [
        'codigo' => ' 002',
        'nombre' => ' REBSAMEN',
        'serie_cotizacion' => 'ORI-C',
        'serie_remision'   => 'ORI-R',
        'serie_factura'    => 'ORI-F',
        'serie_devolucion'    => 'ORI-DEV',
        'almacen_id'    => 4,
        'empresa_id'    => 1,
    ],
]);
    }
}
