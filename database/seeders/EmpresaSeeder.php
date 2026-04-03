<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        Empresa::insert([
            'nombre' => 'EMPRESA PRUEBA',
            'codigo' => '0001',
            'rfc' => 'RFC001',
            'regimen_fiscal' => '601',
        ]);
    }
}
