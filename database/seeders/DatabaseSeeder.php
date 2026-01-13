<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
        ClasificacionSeeder::class,
        ProductosSeeder::class,
        ClientesSeeder::class,
        CodigosPostalesSeeder::class,
        AlmacenSeeder::class,
        DocumentosModeloSeeder::class,
        ExistenciasSeeder::class,
    ]);
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
