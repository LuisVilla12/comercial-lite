<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agente;

class AgenteSeeder extends Seeder
{
    public function run(): void
    {
        Agente::insert([
    [
        'codigo' => '001112',
        'nombre' => ' NINGUNO',
        'apellidoP'=>'000',
        'apellidoM'=>'00',
    ],]);
    }
}
