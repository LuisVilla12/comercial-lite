<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class AlmacenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = storage_path('app/documentos_modelos.csv');

        if (!file_exists($file)) {
            $this->command->error('❌ Archivo documentos_modelos.csv no encontrado');
            return;
        }

        $handle = fopen($file, 'r');

        $batch = [];
        $count = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {

            $batch[] = [
                'id' => intval($row[0]),
                'nombre' => trim($row[1]),
                'afecta_existencia' => intval($row[2]),
            ];

            if (count($batch) === 500) {
                DB::table('documentos_modelos')->insert($batch);
                $batch = [];
            }

            $count++;
        }

        if (!empty($batch)) {
            DB::table('almacens')->insert($batch);
        }

        fclose($handle);

        $this->command->info("✅ Registros importados: {$count}");
    }
}
