<?php

namespace App\Http\Controllers;

use App\Models\ExistenciaProducto;
use Illuminate\Http\Request;

class ExistenciaProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    $search = $request->get('search');
$existencias = ExistenciaProducto::with(['producto', 'almacen'])
    ->when($search, function ($query, $search) {
        $query->whereHas('producto', function ($q) use ($search) {
            $q->where('nombre_producto', 'like', "%{$search}%")
              ->orWhere('codigo_producto', 'like', "%{$search}%");
        })
        ->orWhereHas('almacen', function ($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%");
        });
    })
    ->paginate(10)
    ->withQueryString();    // dd($existencias);
    return view('existencias.index', compact('existencias'));
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
