<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DatosBancario;

class BancoSeeder extends Seeder
{
    public function run(): void
    {
        DatosBancario::insert([
    [
        'nombre_banco' => ' PENDIENTE',
        'cuenta_bancaria' => ' PENDIENTE',
        'clabe'=>'PENDIENTE',
        'correo_electronico'=>'PENDIENTE',
        'whatsapp'=>'0',
        'predeterminado'=>'1',
    ],]);
    }
}
