<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentosExport;

class ReportesController extends Controller
{
    //
    public function index(){
        return view("reportes.index");
    }
    public function export(Request $request)
{
    // dd($request->all());
    return Excel::download(
        new DocumentosExport(
            $request->documento_modelo_id,
            $request->fecha_inicio,
            $request->fecha_fin
        ),
        'reporte.xlsx'
    );
}
}
