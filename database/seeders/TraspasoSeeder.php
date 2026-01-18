<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Traspaso;
use Illuminate\Support\Facades\Hash;

class TraspasoSeeder extends Seeder
{
    public function run(): void
    {
$trapaso = Traspaso::create([
                'serie' => 'TL',
                'folio' => 2,
                'fecha' => '2026-10-1',
                'almacen_origen_id' => 1,
                'almacen_destino_id' => 4,
                'user_id' => 1,
                'estatus' => 1,
            ]);
    }
}
