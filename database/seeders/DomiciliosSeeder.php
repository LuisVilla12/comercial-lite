<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DomiciliosSeeder extends Seeder
{
    public function run(): void
    {
        $file = storage_path('app/domicilios.csv');
        if (!file_exists($file)) {
            $this->command->error('❌ Archivos domicilio.csv no encontrado');
            return;
        }

        $handle = fopen($file, 'r');

        $batch = [];
        $count = 0;


        while (($row = fgetcsv($handle, 0, ',')) !== false) {

            $batch[] = [
                'cliente_id' => intval($row[0]),
                'calle' => trim($row[1]),
                'numero_exterior' => intval($row[2]),
                'numero_interior' => trim($row[3]) ?: null,
                'colonia' => trim($row[4]) ?: null,
                'cp' => intval($row[5]),
                'pais' => trim($row[6]) ?: 'México',
                'estado' => trim($row[7]) ?: null,
                'ciudad' => trim($row[8]) ?: null,
                'municipio' => trim($row[9]) ?: null,
            ];

            if (count($batch) === 500) {
                DB::table('domicilios')->insert($batch);
                $batch = [];
            }

            $count++;
        }

        if (!empty($batch)) {
            DB::table('domicilios')->insert($batch);
        }

        fclose($handle);

        $this->command->info("✅ Registros importados: {$count}");
    }
}
