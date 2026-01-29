<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\RegimenSeeder as SeedersRegimenSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RegimenSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Definir los Seeder a ejecutar
        $this->call([
        RegimenesSeeder::class,
        UsoCfdiSeeder::class,
        CodigosPostalesSeeder::class,
        DocumentosModeloSeeder::class,
        UserSeeder::class,

         ClasificacionSeeder::class,
         ProductosSeeder::class,
         ClientesSeeder::class,
         AlmacenSeeder::class,
         ExistenciasSeeder::class,
         SucursalSeeder::class,
         DomiciliosSeeder::class,
        ]);
    }
}
