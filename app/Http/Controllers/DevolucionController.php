<?php

namespace App\Http\Controllers;

use App\Models\Devolucion;
use App\Models\Documento;
use App\Models\Sucursal;
use App\Models\UsoCfdi;
use Illuminate\Http\Request;

class DevolucionController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Sucursal $sucursal,Request $request)
    {
        $documentos = Devolucion::where('serie', $sucursal->serie_devolucion)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('folio', 'like', "%{$search}%")
                        ->orWhereHas('cliente', function ($c) use ($search) {
                            $c->where('nombre', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->fecha === 'hoy', function ($q) {
                $q->whereDate('fecha', now()->toDateString());
            })
            ->orderBy('folio', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('devoluciones.index', ['devoluciones' => $documentos, 'sucursal' => $sucursal]);
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

     public function show(Sucursal $sucursal, Devolucion $documento)
    {
        $usos_cfdi = UsoCfdi::all();
        $documento->load([
            'cliente',
            'detalles.producto'
        ]);
        return view('devoluciones.show', ['sucursal' => $sucursal, 'documento' => $documento, 'usos' => $usos_cfdi,]);
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
