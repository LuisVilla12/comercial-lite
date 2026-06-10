<?php

namespace App\Http\Controllers;

use App\Models\ExistenciaProducto;
use Illuminate\Http\Request;
use App\Models\Almacen;

class ExistenciaProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $existencias = ExistenciaProducto::with(['producto', 'almacen'])
        ->when($request->search, function ($q) use ($request) {
            $q->whereHas('producto', function ($p) use ($request) {
                $p->where('nombre_producto', 'like', '%' . $request->search . '%')
                  ->orWhere('codigo_producto', 'like', '%' . $request->search . '%');
            });
        })
        ->when($request->almacen_id, function ($q) use ($request) {
            $q->where('almacen_id', $request->almacen_id);
        })
        ->paginate(10)
        ->withQueryString(); // mantiene search + almacen

    $almacenes = Almacen::orderBy('nombre')->get();

    return view('existencias.index', compact('existencias', 'almacenes'));
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ExistenciaProducto $existenciaProducto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExistenciaProducto $existenciaProducto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExistenciaProducto $existenciaProducto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExistenciaProducto $existenciaProducto)
    {
        //
    }
}
