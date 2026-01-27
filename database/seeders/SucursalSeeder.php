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
        'nombre' => ' Orizaba',
        'serie_cotizacion' => 'ORI-C',
        'serie_remision'   => 'ORI-R',
        'serie_factura'    => 'ORI-F',
        'serie_devolucion'    => 'ORI-DEV',
        'almacen_id'    => 1,
    ],
    [
        'codigo' => ' 002',
        'nombre' => ' Zaragoza',
        'serie_cotizacion' => 'ZAR-C',
        'serie_remision'   => 'ZAR-R',
        'serie_factura'    => 'ZAR-F',
        'serie_devolucion'    => 'ZAR-DEV',
        'almacen_id'    => 3,
    ],
    [
        'codigo' => ' 003',
        'nombre' => ' Rebsamen',
        'serie_cotizacion' => 'REB-C',
        'serie_remision'   => 'REB-R',
        'serie_factura'    => 'REB-F',
        'serie_devolucion'    => 'REB-DEV',
        'almacen_id'    => 4,
    ],
]);
    }
}
