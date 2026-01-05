<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClavesSatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
         $file = storage_path('app/productos.csv');

        if (!file_exists($file)) {
            $this->command->error('❌ Archivo productos.csv no encontrado');
            return;
        }

        $handle = fopen($file, 'r');

        $batch = [];
        $count = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {

            $batch[] = [
                'clave' => trim($row[0])?: null,
                'descripcion' => trim($row[1])?: null,
            ];

            if (count($batch) === 500) {
                DB::table('claves_sat')->insert($batch);
                $batch = [];
            }

            $count++;
        }

        if (!empty($batch)) {
            DB::table('claves_sat')->insert($batch);
        }

        fclose($handle);

        $this->command->info("✅ Registros importados: {$count}");
    }
}
