<?php

namespace App\Http\Controllers;

use App\Models\CodigoPostal;

class CodigoPostalController extends Controller
{
    public function buscar($cp)
    {
        $resultados = CodigoPostal::where('d_codigo', $cp)->get();
        // dd($resultados);
        if ($resultados->isEmpty()) {
            return response()->json(['message' => 'Código postal no encontrado'], 404);
        }

        return response()->json($resultados);
    }
}
