<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ClientesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = storage_path('app/clientes-proveedores.csv');
        if (!file_exists($file)) {
            $this->command->error('❌ Archivo clientes-proveedores.csv no encontrado');
            return;
        }

        $handle = fopen($file, 'r');

        $batch = [];
        $count = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {

            $batch[] = [
                'id' => intval($row[0]),
                'codigo' => trim($row[1])?: '',
                'nombre' => trim($row[2])?: '',
                'rfc' => trim($row[3])?: '',
                'curp' => trim($row[4])?: null,
                'tipo' => trim($row[5])?:4,
                'email1' => trim($row[6]) ?: '',
                'email2' => trim($row[7]) ?: '',
                'regimen_fiscal' => trim($row[8])?: null,
                'telefono' =>  null,
                'whatsapp' =>  null,
                'saldo' => 0,
            ];

            if (count($batch) === 500) {
                DB::table('clientes')->insert($batch);
                $batch = [];
            }

            $count++;
        }

        if (!empty($batch)) {
            DB::table('clientes')->insert($batch);
        }

        fclose($handle);

        $this->command->info("✅ Registros importados: {$count}");
    }
}
