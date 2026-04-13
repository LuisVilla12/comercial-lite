<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\Almacen;
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
        $almacenes=Almacen::all();
        return view('sucursales.create',['almacenes'=>$almacenes]);
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
            'serie_factura'=>'required|string|max:50',
            'serie_devolucion'=>'required|string|max:50',
            'folio_cotizacion'=>'required',
            'folio_remision'=>'required',
            'folio_factura'=>'required',
            'folio_devolucion'=>'required',
            'almacen_id'=>'required|exists:almacens,id|unique:sucursales,almacen_id',
        ]);
        $sucursal = Sucursal::create([
                'almacen_id' => $request->almacen_id,
                'empresa_id' => 1,
                'codigo' => $request->codigo,
                'nombre'   => $request->nombre,
                'serie_cotizacion'      => $request->serie_cotizacion,
                'serie_remision'        => $request->serie_remision,
                'serie_factura'     => $request->serie_factura,
                'serie_devolucion'     => $request->serie_devolucion,
                'folio_cotizacion' => $request->folio_cotizacion,
                'folio_remision' => $request->folio_remision,
                'folio_factura'      => $request->folio_factura,
                'folio_devolucion'      => $request->folio_devolucion,
            ]);

         return redirect()->route('sucursales.index')
             ->with('success', 'Sucursal creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sucursal $sucursal)
    {
        $almacenes=Almacen::all();
        return view('sucursales.show', ['sucursal'=>$sucursal,'almacenes'=>$almacenes]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sucursal $sucursal)
    {
        //
        $almacenes=Almacen::all();
        return view('sucursales.edit', ['sucursal'=>$sucursal,'almacenes'=>$almacenes]);
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
            'serie_factura'=>'required|string|max:50',
            'serie_devolucion'=>'required|string|max:50',
            'folio_cotizacion'=>'required',
            'folio_remision'=>'required',
            'folio_factura'=>'required',
            'folio_devolucion'=>'required',
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
