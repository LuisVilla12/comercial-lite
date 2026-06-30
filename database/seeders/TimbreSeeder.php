<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Timbre;

class TimbreSeeder extends Seeder
{
    public function run(): void
    {
        Timbre::create([
            'contratados' => 0,
            'utilizados'=>0,
            'disponibles' =>0,
        ]);
    }
}
