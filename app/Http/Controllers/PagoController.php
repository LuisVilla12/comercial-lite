<?php

namespace App\Http\Controllers;

use App\Models\Pago;
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
        $pagos =Pago::orderBy("created_at","desc")->paginate(10);
        return view('pagos.index', compact('pagos'));
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
        dd($request->all());
        $request->validate([
            'fecha' => 'required|date',
            'proveedor_id' => 'required|integer',
            'user_id' => 'required',
            'forma_pago' => 'required|string|max:255',
            'facturas' => 'required|array',
            //DATOS DE DOMICLIO
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'nullable|string|max:50',
            'codigo_postal' => 'required|string|max:6',
        ]);
        DB::beginTransaction();

        try {
            $pago = Pago::create($request->only(['fecha', 'proveedor_id', 'user_id', 'forma_pago']));

            foreach ($request->facturas as $factura) {
                $pago->facturas()->attach($factura['id'], ['monto' => $factura['monto']]);
            }

            DB::commit();
            return redirect()->route('pagos.index')->with('success', 'Pago creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al crear el pago: ' . $e->getMessage()]);
        }

        //$facturas = json_decode($request->facturas, true);

    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        //
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
