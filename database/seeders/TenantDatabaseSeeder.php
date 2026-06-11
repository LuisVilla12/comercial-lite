<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AlmacenesSeeder::class,
            SucursalSeeder::class,
            UsoCfdiSeeder::class,
            CodigosPostalesSeeder::class,
            DocumentosModeloSeeder::class,
            ClasificacionSeeder::class,
            ProductosSeeder::class,
            ClientesSeeder::class,
            ExistenciasSeeder::class,
            AgenteSeeder::class,
            DomiciliosSeeder::class]);
    }
}
