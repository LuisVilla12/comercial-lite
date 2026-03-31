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

        // function limpiarTexto($texto)
        // {
        //     if ($texto === null) return null;

        //     // Convertir a string seguro
        //     $texto = (string) $texto;

        //     // Quitar BOM (muy común en CSV)
        //     $texto = preg_replace('/^\xEF\xBB\xBF/', '', $texto);

        //     // Quitar NBSP
        //     $texto = str_replace("\xC2\xA0", ' ', $texto);

        //     // Limpiar invisibles
        //     $texto = preg_replace('/[\x00-\x1F\x7F]/u', '', $texto);

        //     // Trim final
        //     $texto = trim($texto);

        //     return $texto === '' ? null : $texto;
        // }


        while (($row = fgetcsv($handle, 0, ',')) !== false) {

            $batch[] = [
                'id' => intval($row[0])?: null,
                'codigo_producto' => trim($row[1])?: null,
                'nombre_producto' => trim($row[2])?: null,
                'tipo_producto' => null,
                'peso_producto' => trim($row[4])?: null,
                'estatus_producto' => 1,
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
            // $mapUnidad = [
            //     'pieza' => 1,
            //     'juego' => 6,
            //     'set'   => 3,
            //     'par'   => 5,
            //     'kg'    => 4,
            // ];
            // $valor = strtolower(trim($row[4]));


            // $batch[] = [
            //     'id' => intval($row[0]) ?: null,
            //     'codigo_producto' => limpiarTexto($row[1]) ?: 'PENDIENTE' . $count
            //     ,
            //     'nombre_producto' =>  limpiarTexto($row[2])?: 'PENDIENTE' . $count,
            //     'tipo_producto' => null,
            //     'peso_producto' => trim($row[3]) ?: null,
            //     'estatus_producto' => 1,

            //     'unidad_medida' => $mapUnidad[$valor] ?? null,
            //     'precio1' => is_numeric($row[6]) ? $row[6] : null,
            //     'precio2' => is_numeric($row[7]) ? $row[7] : null,
            //     'precio3' => is_numeric($row[8]) ? $row[8] : null,
            //     'precio4' => is_numeric($row[9]) ? $row[9] : null,
            //     'precio5' => is_numeric($row[10]) ? $row[10] : null,
            //     'marca' => limpiarTexto($row[11]) ?: null,
            //     'clave_sat' => $row[12] ?: null,
            //     'codigo_alterno' => limpiarTexto($row[13]) ?: null,
            //     'volumen' => limpiarTexto($row[14]) ?: null,
            //     'importe_extra' => null,
            //     'precio_calculado' =>  null,
            //     'impuesto1' => null,
            //     'retencion1' => null,
            //     'valor_clasificacion1' =>  null,
            //     'valor_clasificacion2' => null,
            //     'exento_impuesto' =>  false,
            // ];

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
