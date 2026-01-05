<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosSeeder extends Seeder
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
                'id' => intval($row[0])?: null,
                'codigo_producto' => trim($row[1])?: null,
                'nombre_producto' => trim($row[2])?: null,
                'tipo_producto' => trim($row[3])?: null,
                'peso_producto' => trim($row[4])?: null,
                'estatus_producto' => trim($row[5]) ?: 1,
                'unidad_medida' => intval($row[6]) ?: null,
                'impuesto1' => trim($row[7])?: null,
                'retencion1' => trim($row[8]) ?: null,
                'valor_clasificacion1' => trim($row[9]) ?: null,
                'valor_clasificacion2' => trim($row[10]) ?: null,
                'importe_extra' => trim($row[11]) ?: null,
                'precio1' => trim($row[12]),
                'precio2' => trim($row[13]) ?: null,
                'precio3' => trim($row[14]) ?: null,
                'precio4' => trim($row[15]) ?: null,
                'precio5' => trim($row[16]) ?: null,
                'precio_calculado' => trim($row[17]) ?: null,
                'exento_impuesto' => trim($row[18]) ?: false,
                'codigo_alterno' => trim($row[19]) ?: null,
                'clave_sat' => trim($row[20]) ?: null,
            ];

            if (count($batch) === 500) {
                DB::table('productos')->insert($batch);
                $batch = [];
            }

            $count++;
        }

        if (!empty($batch)) {
            DB::table('productos')->insert($batch);
        }

        fclose($handle);

        $this->command->info("✅ Registros importados: {$count}");
    }
}

