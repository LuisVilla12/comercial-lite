<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CodigosPostalesSeeder extends Seeder
{
    public function run(): void
    {
        $file = storage_path('app/codigos_postales.txt');

        if (!file_exists($file)) {
            $this->command->error('❌ Archivo codigos_postales.txt no encontrado');
            return;
        }

        $handle = fopen($file, 'r');

        // Convertir de ISO-8859-1 a UTF-8 (CRÍTICO)
        stream_filter_append($handle, 'convert.iconv.ISO-8859-1/UTF-8');

        $batch = [];
        $count = 0;

        while (($row = fgetcsv($handle, 0, '|')) !== false) {

            // Validación mínima
            if (count($row) < 15) {
                continue;
            }

            $batch[] = [
                'd_codigo' => trim($row[0]),
                'd_asenta' => trim($row[1]),
                'd_tipo_asenta' => trim($row[2]),
                'd_mnpio' => trim($row[3]),
                'd_estado' => trim($row[4]),
                'd_ciudad' => trim($row[5]) ?: null,
                'd_cp' => trim($row[6]) ?: null,
                'c_estado' => trim($row[7]),
                'c_oficina' => trim($row[8]) ?: null,
                'c_cp' => trim($row[9]) ?: null,
                'c_tipo_asenta' => trim($row[10]),
                'c_mnpio' => trim($row[11]),
                'id_asenta_cpcons' => trim($row[12]),
                'd_zona' => trim($row[13]) ?: null,
                'c_cve_ciudad' => trim($row[14]) ?: null,
            ];

            if (count($batch) === 500) {
                DB::table('codigos_postales')->insert($batch);
                $batch = [];
            }

            $count++;
        }

        if (!empty($batch)) {
            DB::table('codigos_postales')->insert($batch);
        }

        fclose($handle);

        $this->command->info("✅ Registros importados: {$count}");
    }
}
