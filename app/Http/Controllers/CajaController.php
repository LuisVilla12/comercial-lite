<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\User;
use App\Models\Gasto;
use App\Models\Documento;
use App\Models\Clasificacion;
use App\Models\Empresa;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;


class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $query = Caja::query();
    //FILTRO POR USUARIO
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
    //FILTRO POR SUCURSAL
        if ($request->filled('sucursal_id')) {
            $query->where('sucursal_id', $request->sucursal_id);
        }
        // 📅 Filtro por fechas (correcto)
        if ($request->filled('fecha_inicio') && $request->filled('fecha_fin')) {
        $query->whereBetween('created_at', [
            Carbon::parse($request->fecha_inicio)->startOfDay(),
            Carbon::parse($request->fecha_fin)->endOfDay()
        ]);
    }

        $cajas = $query->paginate(10)->withQueryString();

        $sucursales = Sucursal::orderBy('nombre')->get();
           // Para el select de usuarios
        $users = User::orderBy('name')->get();

        return view('cajas.index',['cajas'=>$cajas,'sucursales'=>$sucursales,'users'=>$users]);
        }

    /**
     * Show the form for creating a new resource.
     */
    public function create($sucursal)
    {
        $sucursal=Sucursal::findOrFail($sucursal);
        return view('cajas.create',['sucursal'=>$sucursal]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $sucursal)
    {
        $sucursal=Sucursal::findOrFail($sucursal);
        $request->validate([
            'monto_inicial' => 'required',
        ]);

        $caja = Caja::create([
            'sucursal_id' => $sucursal->id,
            'user_id' => auth()->user()->id,
            'fecha_apertura' => now(),
            'monto_inicial' => $request->monto_inicial,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Caja creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($caja)
    {
        $caja=Caja::findOrFail($caja);
        $ventas = Documento::where('estatus', 4)
        ->where('caja_id', $caja->id)
        ->where('timbrado_online', 0)
        ->whereIn('documento_modelo_id', [2, 3])
        ->selectRaw('forma_pago,SUM(total) as total')
        ->groupByRaw('forma_pago')
        ->get();
        $totalVentas = $ventas->sum('total');

        //CONSULTA DE GASTOS
        $gastos=Gasto::where('caja_id', $caja->id)->get();
        $totalGastos = $gastos->sum('total');

        return view('cajas.show', ['caja'=>$caja, 'ventas'=>$ventas,'totalVentas'=>$totalVentas,'gastos'=> $gastos,'totalGastos'=>$totalGastos]);

    }
    public function pdf( $caja,$mm = 80)
    {
        $caja = Caja::findOrFail($caja);
        $empresa=Empresa::first();

        $ventas = Documento::where('estatus', 4)
        ->where('caja_id', $caja->id)
        ->whereIn('documento_modelo_id', [2, 3])
        ->selectRaw('forma_pago,SUM(total) as total')
        ->groupByRaw('forma_pago')
        ->get();

        $totalVentas = $ventas->sum('total');

        // TOTAL EFECTIVO
        $ventasEfectivo = Documento::where('estatus', 4)
        ->where('caja_id', $caja->id)
        ->whereIn('documento_modelo_id', [2, 3])
        ->where('forma_pago', '01')
        ->get();
        $totalEfectivo = $ventasEfectivo->sum('total');


        //GASTOS DE LA CAJA
        $gastos=Gasto::where('caja_id', $caja->id)
        ->selectRaw('tipo,SUM(total) as total')
        ->groupByRaw('tipo')
        ->get();
        $totalGastos = $gastos->sum('total');

        $width = $mm == 58 ? 164 : 227;
        $customPaper = [0, 0, $width, 450];

        $pdf = Pdf::loadView('cajas.pdf', ['caja'=>$caja, 'ventas'=>$ventas,'totalVentas'=>$totalVentas,'gastos'=>$gastos,'totalGastos'=>$totalGastos,'totalEfectivo'=>$totalEfectivo])
            ->setPaper($customPaper);

        return $pdf->stream("corte_caja{$caja->id}-{$caja->fecha_cierre}.pdf");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($caja)
    {
        $caja=Caja::findOrFail($caja);
        $ventas = Documento::where('estatus', 4)
        ->where('caja_id', $caja->id)
        ->whereIn('documento_modelo_id', [2, 3])
        ->selectRaw('forma_pago,SUM(total) as total')
        ->groupByRaw('forma_pago')
        ->get();
        $totalVentas = $ventas->sum('total');

    //CONSULTAR LOS GASTOS
        $gastos=Gasto::where('caja_id', $caja->id)->get();
        $totalGastos = $gastos->sum('total');

        // dd($gastos);
        // dd($ventas);
        return view('cajas.edit', ['caja'=>$caja, 'ventas'=>$ventas,'totalVentas'=>$totalVentas,'gastos'=> $gastos,'totalGastos'=>$totalGastos]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $caja)
    {
        $request->validate([
            'monto_final' => 'required',
        ]);

        $caja=Caja::findOrFail($caja);
        $caja->update([
            'fecha_cierre' => now(),
            'monto_final' => $request->monto_final,
            'estado'=>'cerrada'
            ]);


        return redirect()
            ->route('cajas.show',$caja)
            ->with('success', 'La caja se ha cerrado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($clasificacion)
    {
        $clasificacion=Clasificacion::findOrFail($clasificacion);
        $clasificacion->delete();

    return redirect()
        ->route('clasificaciones.index')
        ->with(
            'success', 'La categoria se ha  eliminado correctamente.'
        );
    }
}
