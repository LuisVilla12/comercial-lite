<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracionEmpresa;
use App\Models\Regimen;
use App\Models\Documento;


class ConfiguracionEmpresaController extends Controller
{
    //
    public function show(){
    $empresa=ConfiguracionEmpresa::first();
    $regimenes=Regimen::all();

    return view('empresa-config.show',['empresa'=>$empresa,'regimenes'=>$regimenes]);
}
public function edit(){
    $empresa=ConfiguracionEmpresa::first();
    $regimenes=Regimen::all();
    return view('empresa-config.edit',['empresa'=>$empresa,'regimenes'=>$regimenes]);
}
    public function update(Request $request,$empresa)
    {
        $empresa = ConfiguracionEmpresa::findOrFail($empresa);
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:250',
            'rfc' => 'required|string|max:13',
            'regimen_fiscal' => 'required|string|max:250',
            'email' => 'required|email',
            // DATOS DE DOMILIO
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'ciudad' => 'required|string|max:100',
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'string|max:50',
            'cp' => 'required|string|max:6',
        ]);

        $empresa->update($request->all());
        return redirect()->route('configuracion-empresa.show', $empresa)
            ->with('success', 'Empresa ha sido actualizado');
    }

    public function dashboard(Request $request){
$empresa = ConfiguracionEmpresa::first();

    $periodo = $request->get('periodo', 'dia');

$baseVentas = Documento::where('estatus', 4)
    ->whereIn('documento_modelo_id', [2, 3]);

if ($periodo == 'dia') {

    $baseVentas->whereDate('created_at', today());

    $ventas = (clone $baseVentas)
        ->selectRaw('HOUR(created_at) as etiqueta, SUM(total) as total')
        ->groupByRaw('HOUR(created_at)')
        ->orderBy('etiqueta')
        ->get();

} elseif ($periodo == 'semana') {

    $baseVentas->whereBetween('created_at', [
        now()->startOfWeek(),
        now()->endOfWeek()
    ]);

    $ventas = (clone $baseVentas)
        ->selectRaw('DATE(created_at) as etiqueta, SUM(total) as total')
        ->groupByRaw('DATE(created_at)')
        ->orderBy('etiqueta')
        ->get();

} else {

    $baseVentas->whereMonth('created_at', now()->month)
               ->whereYear('created_at', now()->year);

    $ventas = (clone $baseVentas)
        ->selectRaw('DATE(created_at) as etiqueta, SUM(total) as total')
        ->groupByRaw('DATE(created_at)')
        ->orderBy('etiqueta')
        ->get();
}

/*
|--------------------------------------------------------------------------
| KPIs
|--------------------------------------------------------------------------
*/

$ventasTotal = (clone $baseVentas)->sum('total');

$totalDocumentos = (clone $baseVentas)->count();

$ticketPromedio = (clone $baseVentas)->avg('total') ?? 0;

return view('empresa-config.dashboard', [
    'empresa' => $empresa,
    'periodo' => $periodo,
    'ventas' => $ventas,
    'ventasTotal' => $ventasTotal,
    'totalDocumentos' => $totalDocumentos,
    'ticketPromedio' => $ticketPromedio,
]);

    }
}
