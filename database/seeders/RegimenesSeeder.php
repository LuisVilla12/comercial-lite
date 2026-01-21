<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Regimen;
class RegimenesSeeder extends Seeder
{
    public function run(): void
    {
        $regimenes = [
            ['codigo' => '601', 'nombre' => 'REGIMEN GENERAL DE LEY PERSONAS MORALES'],
            ['codigo' => '602', 'nombre' => 'RÉGIMEN SIMPLIFICADO DE LEY PERSONAS MORALES'],
            ['codigo' => '603', 'nombre' => 'PERSONAS MORALES CON FINES NO LUCRATIVOS'],
            ['codigo' => '604', 'nombre' => 'RÉGIMEN DE PEQUEÑOS CONTRIBUYENTES'],
            ['codigo' => '605', 'nombre' => 'RÉGIMEN DE SUELDOS Y SALARIOS E INGRESOS ASIMILADOS A SALARIOS'],
            ['codigo' => '606', 'nombre' => 'RÉGIMEN DE ARRENDAMIENTO'],
            ['codigo' => '607', 'nombre' => 'RÉGIMEN DE ENAJENACIÓN O ADQUISICIÓN DE BIENES'],
            ['codigo' => '608', 'nombre' => 'RÉGIMEN DE LOS DEMÁS INGRESOS'],
            ['codigo' => '609', 'nombre' => 'RÉGIMEN DE CONSOLIDACIÓN'],
            ['codigo' => '610', 'nombre' => 'RÉGIMEN RESIDENTES EN EL EXTRANJERO SIN ESTABLECIMIENTO PERMANENTE EN MÉXICO'],
            ['codigo' => '611', 'nombre' => 'RÉGIMEN DE INGRESOS POR DIVIDENDOS (SOCIOS Y ACCIONISTAS)'],
            ['codigo' => '612', 'nombre' => 'RÉGIMEN DE LAS PERSONAS FÍSICAS CON ACTIVIDADES EMPRESARIALES Y PROFESIONALES'],
            ['codigo' => '613', 'nombre' => 'RÉGIMEN INTERMEDIO DE LAS PERSONAS FÍSICAS CON ACTIVIDADES EMPRESARIALES'],
            ['codigo' => '614', 'nombre' => 'RÉGIMEN DE LOS INGRESOS POR INTERESES'],
            ['codigo' => '615', 'nombre' => 'RÉGIMEN DE LOS INGRESOS POR OBTENCIÓN DE PREMIOS'],
            ['codigo' => '616', 'nombre' => 'SIN OBLIGACIONES FISCALES'],
            ['codigo' => '617', 'nombre' => 'PEMEX'],
            ['codigo' => '618', 'nombre' => 'RÉGIMEN SIMPLIFICADO DE LEY PERSONAS FÍSICAS'],
            ['codigo' => '619', 'nombre' => 'INGRESOS POR LA OBTENCIÓN DE PRÉSTAMOS'],
            ['codigo' => '620', 'nombre' => 'SOCIEDADES COOPERATIVAS DE PRODUCCIÓN QUE OPTAN POR DIFERIR SUS INGRESOS'],
            ['codigo' => '621', 'nombre' => 'RÉGIMEN DE INCORPORACIÓN FISCAL'],
            ['codigo' => '622', 'nombre' => 'RÉGIMEN DE ACTIVIDADES AGRÍCOLAS, GANADERAS, SILVÍCOLAS Y PESQUERAS PM'],
            ['codigo' => '623', 'nombre' => 'RÉGIMEN DE OPCIONAL PARA GRUPOS DE SOCIEDADES'],
            ['codigo' => '624', 'nombre' => 'RÉGIMEN DE LOS COORDINADOS'],
            ['codigo' => '625', 'nombre' => 'RÉGIMEN DE LAS ACTIVIDADES EMPRESARIALES CON INGRESOS A TRAVÉS DE PLATAFORMAS TECNOLÓGICAS'],
            ['codigo' => '626', 'nombre' => 'RÉGIMEN SIMPLIFICADO DE CONFIANZA'],
        ];

        Regimen::insert($regimenes);
    }
}
