<?php

namespace App\Http\Controllers;

use App\Models\MedioPago;
use Illuminate\Http\Request;

class MedioPagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $medios=MedioPago::all();
        return view('metodos.index',['medios'=>$medios]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('metodos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
    $request->validate([
            'codigo' => 'required',
            'nombre' => 'required',
            'tipo' => 'required',
        ]);
        MedioPago::create($request->all());
        return redirect()->route('metodos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($medio)
    {
        //
        $medio = MedioPago::findOrFail($medio);
        return view('metodos.show', ['medio'=>$medio]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($medio)
    {
         $medio = MedioPago::findOrFail($medio);
        return view('metodos.edit', ['medio'=>$medio]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $medio)
    {
        $request->validate([
            'codigo' => 'required',
            'nombre' => 'required',
            'tipo' => 'required',
        ]);
        $medio = MedioPago::findOrFail($medio);
        $medio->update([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
        ]);
        return redirect()
            ->route('metodos.index')
            ->with('success', 'Método de pago actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $medio)
    {
        //
        $medio = MedioPago::findOrFail($medio);
        $medio->delete();
        return redirect()->route('metodos.index');
    }
}
