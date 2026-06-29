<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Exports\DocumentosDetallesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DocumentosExport;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Reporte;


class ReportesController extends Controller
{
    //
    public function index(Request $request)
    {
    $query = Reporte::query();

    // 🔍 Buscador general
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->Where('tipo', 'like', "%{$search}%");  
        });
    }

    // 👤 Filtro por usuario
    if ($request->filled('user_id')) {
        $query->where('user_id', $request->user_id);
    }

    // 📦 Filtro por tipo/modelo
    if ($request->filled('tipo')) {
        $query->where('tipo', $request->tipo);
    }

    // 📅 Filtro por fechas (correcto)
    if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereBetween('created_at', [
            Carbon::parse($request->fecha_inicio)->startOfDay(),
            Carbon::parse($request->fecha_fin)->endOfDay()
        ]);
    }

    $reportes = $query->paginate(20)->withQueryString();
        // $reportes=Reporte::all();
        $users = User::all();
        return view("reportes.index", ["reportes" => $reportes,'users'=>$users]);
    }

    public function descargar($archivo)
{
    $ruta = 'reportes/' . $archivo;

    if (!Storage::disk('public')->exists($ruta)) {
        abort(404);
    }

    return Storage::disk('public')->download($ruta);
}

     public function select()
    {
        return view("reportes.select");
    }
    public function create()
    {
        $sucursales = Sucursal::all();
        $usuarios = User::all();
        return view("reportes.create", ["sucursales" => $sucursales,'usuarios'=>$usuarios]);
    }
    public function exportConceptos(Request $request)
    {
        // dd(vars: $request);
        $request->validate([
            'sucursal_id' => 'required',
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
    public function exportProductos(Request $request)
    {
        $request->validate([
            'sucursal_id' => 'required'
            ]);

        $sucursal = Sucursal::findOrFail($request->sucursal_id);
        return Excel::download(
            new DocumentosDetallesExport(
                $request->fecha_inicio,
                $request->fecha_fin,
            ),
            'reporte.xlsx'
        );
    }
}
