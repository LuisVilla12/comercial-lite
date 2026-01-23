<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use App\Models\Cliente;

use Illuminate\Http\Request;

class DomicilioController extends Controller
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
    public function create(Cliente $cliente)
    {
        return view('domicilios.create', compact('cliente'));
    }

    /**
     * Store a newly created resource in storage.
     */
  public function store(Request $request, Cliente $cliente)
{
    $request->validate([
        'estado' => 'required|string|max:100',
        'municipio' => 'required|string|max:100',
        'colonia' => 'required|string|max:100',
        'calle' => 'required|string|max:255',
        // 'numero_interior' => 'required|string|max:50',
        'numero_exterior' => 'nullable|string|max:50',
        'cp' => 'required|string|max:10',
    ]);
    $domicilio = Domicilio::create([
            'pais' => 'MEXICO',
            'estado' => $request->estado,
            'ciudad' => $request->municipio,
            'colonia' => $request->colonia,
            'calle' => $request->calle,
            'numero_interior' => $request->numero_interior,
            'numero_exterior' => $request->numero_exterior,
            'cp' => $request->cp,
            'cliente_id' => $cliente->id,
        ]);


    return redirect()
        ->route('clientes.show', [$cliente, $cliente->tipo])
        ->with('success', value: 'Domicilio agregado correctamente.');
}

    /**
     * Display the specified resource.
     */
    public function show(Domicilio $domicilio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente, Domicilio $domicilio)
    {

    return view('domicilios.edit', compact('cliente', 'domicilio'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Cliente $cliente,  Domicilio $domicilio)
    {
        $request->validate([
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_interior' => 'required|string|max:50',
            'numero_exterior' => 'nullable|string|max:50',
            'cp' => 'required|string|max:10',
        ]);

        $domicilio->update([
            'pais' => 'MEXICO',
            'estado' => $request->estado,
            'ciudad' => $request->municipio,
            'colonia' => $request->colonia,
            'calle' => $request->calle,
            'numero_interior' => $request->numero_interior,
            'numero_exterior' => $request->numero_exterior,
            'cp' => $request->cp
        ]);

        return redirect()
            ->route('clientes.edit',[ $domicilio->cliente_id,$cliente->tipo])
            ->with('success', value: "Domicilio actualizado correctamente.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Domicilio $domicilio)
    {
        //
    }
}
