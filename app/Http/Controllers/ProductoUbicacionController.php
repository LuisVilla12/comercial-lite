<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Almacen;
use App\Models\ProductoUbicacion;
use Illuminate\Http\Request;

class ProductoUbicacionController extends Controller
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
        $producto = Producto::findOrFail($producto);
        $almacenes = Almacen::all();
        return view('producto-ubicacion.create', ['almacenes' => $almacenes, 'producto' => $producto]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'almacen_id' => 'required',
            'producto_id' => 'required',
        ]);

        $existe = ProductoUbicacion::where('almacen_id', $request->almacen_id)
            ->where('producto_id', $request->producto_id)
            ->exists();

        if ($existe) {
            return back()
                ->withErrors([
                    'almacen_id' => 'Este producto ya tiene un registro de máximos y mínimos en el almacén seleccionado.'
                ])
                ->withInput();
        }
        ProductoUbicacion::create([
            'almacen_id' => $request->almacen_id,
            'producto_id' => $request->producto_id,
            'zona' => $request->zona,
            'pasillo' => $request->pasillo,
            'anaquel' => $request->anaquel,
            'repisa' => $request->repisa,
        ]);
        $producto = Producto::findOrFail($request->producto_id);
        return redirect()->route('productos.show', $producto);

    }

    /**
     * Display the specified resource.
     */
    public function show(ProductoUbicacion $productoUbicacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($producto,$productoUbicacion)
    {
        $producto = Producto::findOrFail($producto);
        $productoUbicacion = ProductoUbicacion::findOrFail($productoUbicacion);
        $almacenes=Almacen::all();
        return view('producto-ubicacion.edit', compact('producto', 'productoUbicacion','almacenes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $productoUbicacion)
    {
        $productoUbicacion = ProductoUbicacion::findOrFail($productoUbicacion);
        $request->validate([
             'almacen_id' => 'required',
             'producto_id' => 'required',
        ]);
        $productoUbicacion->update($request->all());
        $producto = Producto::findOrFail($request->producto_id);
        return redirect()->route('productos.show', $producto)->with(
            'success', 'El registro se ha actualizado correctamente.'
        );;

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($producto,$productoUbicacion)
    {
        $producto = Producto::findOrFail($producto);
    $productoUbicacion = ProductoUbicacion::findOrFail($productoUbicacion);
    $productoUbicacion->delete();
    return redirect()
        ->route('productos.show',$producto)
        ->with(
            'success', 'El registro se ha eliminado correctamente.'
        );
    }
}
