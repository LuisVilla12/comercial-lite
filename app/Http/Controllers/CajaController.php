<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Clasificacion;
use App\Models\Sucursal;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
    public function show($clasificacion)
    {
        $clasificacion=Clasificacion::findOrFail($clasificacion);
        return view('clasificaciones.show', compact('clasificacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($clasificacion)
    {
                $clasificacion=Clasificacion::findOrFail($clasificacion);
        return view('clasificaciones.edit', compact('clasificacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $clasificacion)
    {
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:255',

            ]);
        $clasificacion=Clasificacion::findOrFail($clasificacion);
        $clasificacion->update([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
        ]);
        return redirect()
            ->route('clasificaciones.index')
            ->with('success', 'Clasificación actualizada correctamente.');
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
