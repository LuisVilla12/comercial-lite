<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\PagosDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $documentos =Pago::orderBy("created_at","desc")->paginate(10);
        return view('pagos.index', compact('documentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pagos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
// dd($facturas);
       $request->validate([
            'fecha' => 'required|date',
            'proveedor_id' => 'required|integer',
            'user_id' => 'required',
            'forma_pago' => 'required|string|max:255',
            'facturas' => 'required',
            //DATOS DE DOMICLIO
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'nullable|string|max:50',
            'codigo_postal' => 'required|string|max:6',
        ]);
        try {
            DB::beginTransaction();
            //calcular monto total de pago
            $facturas = json_decode($request->facturas, true);
            $montoTotal=0;
            foreach($facturas as $factura => $value) {
                $montoTotal+=$value['monto'];}

            $pago = Pago::create([
                'cliente_id'=> $request->proveedor_id,
                'user_id'=>$request->user_id,
                'fecha'=> $request->fecha,
                'forma_pago'=> $request->forma_pago,
                'monto'=> $montoTotal,
                'estatus'=> 1,
            ]);

            //ALMACENAR LOS DETALLES DEL PAGO
            foreach ($facturas as $item) {
                // dd($item);
                PagosDetalle::create([
                    'pago_id' => $pago->id,
                    'documento_id' => $item['id'],
                    'monto' => $item['monto'],
                ]);
            }

            DB::commit();

            // return redirect()->route('pagos.index')->with('success', 'Pago creado exitosamente.');
            return redirect()->back()->with('success', 'Pago creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al crear el pago: ' . $e->getMessage()]);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show($pago)
    {
        //Buscar pago
        $documento=Pago::findOrFail($pago);
        return view('pagos.show', compact('documento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        //
    }
}
