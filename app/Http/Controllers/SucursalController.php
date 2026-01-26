<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use Illuminate\Http\Request;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search');
        $sucursales = Sucursal::when($search, function ($query, $search) {
            $query->where('nombre', 'like', "%{$search}%");
        })
        ->paginate(10)
        ->withQueryString();
        return view('sucursales.index', ['sucursales'=>$sucursales]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('sucursales.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'codigo'=>'required|unique:sucursales,codigo|string|max:50',
            'nombre'=>'required|string|max:50',
            'serie_cotizacion'=>'required|string|max:50',
            'serie_remision'=>'required|string|max:50',
            'serie_facturacion'=>'required|string|max:50',
            'folio_cotizacion'=>'required',
            'folio_remision'=>'required',
            'folio_facturacion'=>'required',
        ]);
        $sucursal = Sucursal::create([
                'codigo' => $request->codigo,
                'nombre'   => $request->nombre,
                'serie_cotizacion'      => $request->serie_cotizacion,
                'serie_remision'        => $request->serie_remision,
                'serie_facturacion'     => $request->serie_facturacion,
                'folio_cotizacion' => $request->folio_cotizacion,
                'folio_remision' => $request->folio_remision,
                'folio_facturacion'      => $request->folio_facturacion,
            ]);
        return redirect()->route('sucursales.index')
            ->with('success', 'Sucursal creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sucursal $sucursal)
    {
        return view('sucursales.show', ['sucursal'=>$sucursal]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sucursal $sucursal)
    {
        //
        return view('sucursales.edit', ['sucursal'=>$sucursal]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sucursal $sucursal)
    {
        // dd($request);
         $request->validate([
            'codigo'=>'required|string|max:50',
            'nombre'=>'required|string|max:50',
            'serie_cotizacion'=>'required|string|max:50',
            'serie_remision'=>'required|string|max:50',
            'serie_facturacion'=>'required|string|max:50',
            'folio_cotizacion'=>'required',
            'folio_remision'=>'required',
            'folio_facturacion'=>'required',
        ]);
        $sucursal->update($request->all());
        return redirect()->route('sucursales.index')
            ->with('success', 'Sucursal ha sido actualizada correctamente.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sucursal $sucursal)
    {
        //
    }
}
