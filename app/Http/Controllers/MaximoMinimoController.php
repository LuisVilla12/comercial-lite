<?php

namespace App\Http\Controllers;

use App\Models\MaximoMinimo;
use App\Models\Almacen;
use App\Models\Producto;
use Illuminate\Http\Request;

class MaximoMinimoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($producto)
    {
        //
        $producto = Producto::findOrFail($producto);
        $almacenes = Almacen::all();
        return view('maxmin.create', ['almacenes' => $almacenes, 'producto' => $producto]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'almacen_id' => 'required',
            'producto_id' => 'required',
            'minimo' => 'required',
            'maximo' => 'required',
        ]);

        $existe = MaximoMinimo::where('almacen_id', $request->almacen_id)
            ->where('producto_id', $request->producto_id)
            ->exists();

        if ($existe) {
            return back()
                ->withErrors([
                    'almacen_id' => 'Este producto ya tiene un registro de máximos y mínimos en el almacén seleccionado.'
                ])
                ->withInput();
        }
        MaximoMinimo::create([
            'almacen_id' => $request->almacen_id,
            'producto_id' => $request->producto_id,
            'minimo' => $request->minimo,
            'maximo' => $request->maximo,
        ]);
        $producto = Producto::findOrFail($request->producto_id);
        return redirect()->route('productos.show', $producto);
    }

    /**
     * Display the specified resource.
     */
    public function show(MaximoMinimo $maximoMinimo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MaximoMinimo $maximoMinimo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MaximoMinimo $maximoMinimo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($producto,$maximoMinimo)
    {
    $producto = Producto::findOrFail($producto);
    $maximoMinimo = MaximoMinimo::findOrFail($maximoMinimo);
    $maximoMinimo->delete();
    return redirect()
        ->route('productos.show',$producto)
        ->with(
            'success', 'El registro se ha eliminado correctamente.'
        );
    }
}
