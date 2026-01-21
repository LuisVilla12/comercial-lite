<?php

namespace App\Http\Controllers;

use App\Models\Devolucion;
use App\Models\Documento;
use Illuminate\Http\Request;

class DevolucionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $documentos = Documento::where('documento_modelo_id', 3)->where('estatus',2)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('serie', 'like', "%{$search}%")
                        ->orWhere('folio', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();
        return view('devoluciones.index', compact(var_name: 'documentos'));
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
    public function show(Devolucion $devolucion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Devolucion $devolucion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Devolucion $devolucion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Devolucion $devolucion)
    {
        //
    }
}
