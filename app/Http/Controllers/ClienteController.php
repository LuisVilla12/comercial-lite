<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
{
    $search = $request->get('search');

    $clientes = Cliente::where('tipo', '1')
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                ->orWhere('rfc', 'like', "%{$search}%");
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString(); // ← mantiene el search en la paginación

    return view('clientes.index', compact('clientes', 'search'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
                return view('clientes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:255',
            'rfc' => 'required|string|max:13',
            'email1' => 'required|email',
            'regimen_fiscal' => 'required|string|max:255'
        ]);
        $cliente = Cliente::create([
            'tipo' => 'CLIENTE',
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'rfc' => $request->rfc,
            'curp' => $request->curp,
            'email1' => $request->email1,
            'email2' => $request->email2,
            'whatsapp' => $request->whatsapp,
            'telefono' => $request->telefono,
            'regimen_fiscal' => $request->regimen_fiscal
        ]);

        return redirect()
            ->route('clientes.show', $cliente->id)
            ->with('success', 'Cliente creado correctamente. Ahora agrega el domicilio.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
        return view('clientes.show', compact('cliente'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        //
        return view('clientes.edit', compact('cliente'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        //
         $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'nullable|email'
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')
            ->with('success', 'Cliente actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
    $cliente->delete();

    return redirect()
        ->route('clientes.index')
        ->with('success', 'Cliente eliminado correctamente.');
    }
}
