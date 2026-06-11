<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Regimen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function indexClientes(Request $request)
{
    $search = $request->get('search');

    $clientes = Cliente::where('tipo', 1)
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

public function indexProveedores(Request $request)
{
    $search = $request->get('search');

    $clientes = Cliente::where('tipo', 3)
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                ->orWhere('rfc', 'like', "%{$search}%");
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString(); // ← mantiene el search en la paginación

    return view('proveedores.index', compact('clientes', 'search'));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create(string $tipo)
    {
        //
        $regimenes=Regimen::all();
        return view('clientes.create', data: ['tipo'=>$tipo, 'regimenes'=>$regimenes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([ 'codigo' => [
        'required',
        'string',
        'max:50',
        function ($attribute, $value, $fail) {
            if (Cliente::where('codigo', $value)->exists()) {
                $fail('El código ya existe.');
            }
        },
    ],
        'nombre' => 'required|string|max:255',
            'rfc' => 'required|string|max:13',
            'email1' => 'required|email',
            'regimen_fiscal' => 'required|string|max:255'
        ]);
        // dd((new Cliente)->getConnectionName());

        $cliente = Cliente::create([
            'tipo' => $request->tipo,
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
            ->route('clientes.show', [$cliente->id, $cliente->tipo])
            ->with('success',  $cliente->tipo == 1 ? 'El cliente ha sido registrado.' : 'El proveedor ha sido registrado.');
    }

    /**
     * Display the specified resource.
     */
    public function show($cliente, string $tipo)
    {
        $cliente = Cliente::findOrFail($cliente);
        return view('clientes.show', compact('cliente', 'tipo'));

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($cliente, string $tipo)
    {
        //
        $cliente = Cliente::findOrFail($cliente);
        $regimenes=Regimen::all();
        return view('clientes.edit', ['cliente'=>$cliente, 'tipo'=>$tipo,'regimenes'=>$regimenes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $cliente)
    {
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:255',
            'rfc' => 'required|string|max:13',
            'email1' => 'required|email',
            'regimen_fiscal' => 'required|string|max:255'
        ]);
        $cliente = Cliente::findOrFail($cliente);
        $tipo = $cliente->tipo;
        $cliente->update($request->all());

        return redirect()->route($tipo == 1 ? 'clientes.index' : 'proveedores.index')
            ->with('success', $tipo == 1 ? 'Cliente actualizado' : 'Proveedor actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($cliente) {
    $cliente = Cliente::findOrFail($cliente);
    $tipo = $cliente->tipo;
    $cliente->delete();

    return redirect()
        ->route($tipo == '1' ? 'clientes.index' : 'proveedores.index')
        ->with(
            'success',
            ($tipo === '1' ? 'Cliente' : 'Proveedor') . ' eliminado correctamente.'
        );
    }
}
