<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Almacen;
use App\Models\Producto;

class ExistenciasSeeder extends Seeder
{
    public function run(): void
    {
        $file = storage_path('app/existencias_2026.csv');

        if (!file_exists($file)) {
            $this->command->error('❌ Archivo existencias.csv no encontrado');
            return;
        }

        $handle = fopen($file, 'r');

        if (!$handle) {
            $this->command->error('❌ No se pudo abrir el archivo');
            return;
        }

        $batch   = [];
        $productosInexistentes = [];
        $count   = 0;
        $skipped = 0;
        $skipFormato   = 0;
        $skipAlmacen   = 0;
        $skipProducto  = 0;
        $skipColumnas  = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {

            if (count($row) < 3) {
                $skipColumnas++;
                $skipped++;
                continue;
            }

            $productoId = trim($row[0]);
            $almacenId  = trim($row[1]);
            $cantidad   = trim($row[2]);

            // Validaciones
            if (
                !is_numeric($almacenId) ||
                !is_numeric($productoId) ||
                !is_numeric($cantidad)
            ) {
                $skipped++;
                $skipFormato++;
                continue;
            }

            if (!Almacen::where('id', $almacenId)->exists()) {
                $skipAlmacen++;
                continue;
            }

            // 4️⃣ Producto inexistente
            if (!Producto::where('id', $productoId)->exists()) {
                $skipProducto++;
                if (count($productosInexistentes) < 50) {
                    $productosInexistentes[] = $productoId;
                }
                continue;
            }

            $batch[] = [
                'almacen_id'  => (int) $almacenId,
                'producto_id' => (int) $productoId,
                'cantidad'    => (int) $cantidad,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];

            if (count($batch) === 500) {
                DB::table('existencia_productos')->insert($batch);
                $batch = [];
            }

            $count++;
        }

        // Inserta lo que quedó
        if (!empty($batch)) {
            DB::table('existencia_productos')->insert($batch);
        }

        fclose($handle);

        $this->command->info("✅ Registros importados: {$count}");
        $this->command->warn("⚠️ Omitidos por columnas incompletas: {$skipColumnas}");
        $this->command->warn("⚠️ Omitidos por formato inválido: {$skipFormato}");
        $this->command->warn("⚠️ Omitidos por almacén inexistente: {$skipAlmacen}");
        $this->command->warn("⚠️ Omitidos por producto inexistente: {$skipProducto}");

        $totalOmitidos = $skipColumnas + $skipFormato + $skipAlmacen + $skipProducto;
        $this->command->warn("⚠️ Total omitidos: {$totalOmitidos}");
        $this->command->warn('🧾 Ejemplos de producto_id inexistentes:');
$this->command->line(implode(', ', array_unique($productosInexistentes)));

    }
}
