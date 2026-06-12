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
            //PRINCIPALES SEEDER PARA FUNCIONAR
            UsoCfdiSeeder::class,
            CodigosPostalesSeeder::class,
            DocumentosModeloSeeder::class,
            AgenteSeeder::class,
            BancoSeeder::class,

            //LLENADO DE INFORMACION

            AlmacenesSeeder::class,
            SucursalSeeder::class,
            ClasificacionSeeder::class,
            ProductosSeeder::class,
            ClientesSeeder::class,
            ExistenciasSeeder::class,
            DomiciliosSeeder::class
            ]);
    }
}
