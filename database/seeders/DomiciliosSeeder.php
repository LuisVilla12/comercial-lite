<?php

namespace Database\Seeders;

use App\Models\Cliente;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class DomiciliosSeeder extends Seeder
{
 public function run(): void
    {
        $file = storage_path('app/domicilios.csv');

        if (!file_exists($file)) {
            $this->command->error('❌ Archivo domicilios.csv no encontrado');
            return;
        }

        $handle = fopen($file, 'r');

        $batch = [];
        $count = 0;
        $noEncontrados = [];

        while (($row = fgetcsv($handle, 0, ',')) !== false) {

            $clienteId = intval($row[0]);

            // 🔎 Verificar si existe el cliente
            if (!Cliente::where('id', $clienteId)->exists()) {
                $noEncontrados[] = $clienteId;
                continue; // ⛔ saltar este registro
            }

            $batch[] = [
                'cliente_id' => $clienteId,
                'calle' => trim($row[1]) ?: '',
                'numero_exterior' => $row[2] !== '' ? intval($row[2]) : null,
                'numero_interior' => trim($row[3]) ?: null,
                'colonia' => trim($row[4]) ?: '',
                'cp' => $row[5] !== '' ? intval($row[5]) : null,
                'pais' => trim($row[6]) ?: 'México',
                'estado' => trim($row[7]) ?: '',
                'ciudad' => trim($row[8]) ?: '',
                'municipio' => trim($row[9]) ?: '',
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

        // 🧾 Reporte
        $noEncontrados = array_unique($noEncontrados);

        if (count($noEncontrados)) {
            $this->command->warn(
                '⚠️ cliente_id no encontrados: ' . implode(', ', $noEncontrados)
            );

            Log::warning('cliente_id no encontrados en domicilios', $noEncontrados);
        }

        $this->command->info("✅ Registros importados: {$count}");
    }
}
