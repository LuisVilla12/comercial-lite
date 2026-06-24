<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductoClave;

class ProductoClaveSeeder extends Seeder
{
    public function run(): void
    {
        ProductoClave::insert([
    ['clave' => 'H87', 'descripcion' => 'Pieza'],
    ['clave' => 'E48', 'descripcion' => 'Servicio'],
    ['clave' => 'KGM', 'descripcion' => 'Kilogramo'],
    ['clave' => 'GRM', 'descripcion' => 'Gramo'],
    ['clave' => 'LTR', 'descripcion' => 'Litro'],
    ['clave' => 'MTR', 'descripcion' => 'Metro'],
    ['clave' => 'CMT', 'descripcion' => 'Centímetro'],
    ['clave' => 'MMT', 'descripcion' => 'Milímetro'],
    ['clave' => 'DAY', 'descripcion' => 'Día'],
    ['clave' => 'HUR', 'descripcion' => 'Hora'],
    ['clave' => 'MTK', 'descripcion' => 'Metro cuadrado'],
    ['clave' => 'MTQ', 'descripcion' => 'Metro cúbico'],
    ['clave' => 'PR',  'descripcion' => 'Par'],
    ['clave' => 'SET', 'descripcion' => 'Juego'],
    ['clave' => 'XBX', 'descripcion' => 'Caja'],
    ['clave' => 'XPK', 'descripcion' => 'Paquete'],
    ['clave' => 'EA',  'descripcion' => 'Unidad'],
    ['clave' => 'DOZ', 'descripcion' => 'Docena'],
]);
    }
}
