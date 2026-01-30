<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentosExport;
use App\Models\Sucursal;
use App\Models\User;

class ReportesController extends Controller
{
    //
    public function index()
    {
        $sucursales = Sucursal::all();
        $usuarios = User::all();
        return view("reportes.index", ["sucursales" => $sucursales,'usuarios'=>$usuarios]);
    }
    public function export(Request $request)
    {
        // dd(vars: $request);
        $request->validate([
            'sucursal_id' => 'required|exists:sucursales,id',
            'documento_modelo_id' => 'required|in:2,3,4',
            'user_id' => 'required',
        ]);

        $sucursal = Sucursal::findOrFail($request->sucursal_id);

        $series = [];
        $tipos  = [];

        switch ((int) $request->documento_modelo_id) {

            case 2: // Factura
                $series[] = $sucursal->serie_factura;
                $tipos[]  = 2;
                break;

            case 3: // Remisión
                $series[] = $sucursal->serie_remision;
                $tipos[]  = 3;
                break;

            case 4: // Ambos
                $series = [
                    $sucursal->serie_factura,
                    $sucursal->serie_remision,
                ];
                $tipos = [2, 3];
                break;
        }
        return Excel::download(
            new DocumentosExport(
                $series,
                $tipos,
                $request->fecha_inicio,
                $request->fecha_fin,
                $request->user_id,
            ),
            'reporte.xlsx'
        );
    }
}
