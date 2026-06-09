<?php

namespace App\Http\Controllers;

use App\Models\AjustesAlmacen;
use App\Models\Agente;
use App\Models\Almacen;
use Illuminate\Http\Request;

class AjustesAlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,$tipo)
    {
        $entradas = AjustesAlmacen::when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('id', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('ajustes-almacen.index', compact('entradas','tipo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipo)
    {
        //
        $agentes = Agente::all();
        $almacenes =Almacen::all();
        return view('ajustes-almacen.create', compact('agentes', 'almacenes','tipo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
         $data = $request->validate([
            'agente_id' => 'required|exists:agentes,id',
            'almacen_id' => 'required|exists:almacens,id',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);

        AjustesAlmacen::create($data);
        return redirect()->route('entradas.index')->with('success', 'Entrada de almacén creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AjustesAlmacen $ajustesAlmacen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AjustesAlmacen $ajustesAlmacen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AjustesAlmacen $ajustesAlmacen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AjustesAlmacen $ajustesAlmacen)
    {
        //
    }
}
