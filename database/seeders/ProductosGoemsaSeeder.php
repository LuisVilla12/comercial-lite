<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosGoemsaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $file = storage_path('app/productos_goemsa.csv');

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
                'tipo_producto' => null,
                'peso_producto' => trim($row[3])?: null,
                'estatus_producto' => 1,
                'unidad_medida' => 1,
                'impuesto1' => null,
                'retencion1' => null,
                'valor_clasificacion1' =>  null,
                'valor_clasificacion2' =>  null,
                'importe_extra' => null,
                'precio1' => trim($row[5]),
                'precio2' => trim($row[6]) ?: null,
                'precio3' => trim($row[7]) ?: null,
                'precio4' => trim($row[8]) ?: null,
                'precio5' => trim($row[9]) ?: null,
                'precio_calculado' => null,
                'exento_impuesto' =>  false,
                'marca' =>  trim($row[10]) ?: null,
                'clave_sat' => trim($row[11]) ?: null,
                'codigo_alterno' => trim($row[12]) ?: null,
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
