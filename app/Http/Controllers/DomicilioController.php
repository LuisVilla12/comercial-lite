<?php

namespace App\Http\Controllers;

use App\Models\Domicilio;
use App\Models\Cliente;
use App\Models\Sucursal;
use App\Models\Empresa;


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
    // public function create(Cliente $cliente)
    // {
    //     return view('domicilios.create', compact('cliente'));
    // }


    public function create(string $modeloTipo, int $id)
    {
        $model = match ($modeloTipo) {
            'clientes' => Cliente::findOrFail($id),
            'sucursales' => Sucursal::findOrFail($id),
            'empresas' => Empresa::findOrFail($id),
        };

        return view('domicilios.create', [
            'model' => $model,
            'modeloTipo' => $modeloTipo,
        ]);
    }
    public function store(Request $request, string $modeloTipo, int $id)
    {
        $data = $request->validate([
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'nullable|string|max:50',
            'cp' => 'required|string|max:10',
        ]);

        $model = match ($modeloTipo) {
            'clientes' => Cliente::findOrFail($id),
            'sucursales' => Sucursal::findOrFail($id),
            'empresas' => Empresa::findOrFail($id),
        };

        $model->domicilios()->create($data);
        if ($modeloTipo === 'clientes') {
            return redirect()
                ->route('clientes.show', [$model, $model->tipo])
                ->with('success', 'Domicilio agregado correctamente.');
        }

        return redirect()
            ->route("{$modeloTipo}.show", $model)
            ->with('success', 'Domicilio agregado correctamente.');
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

    public function edit(string $modeloTipo, Domicilio $domicilio)
    {
        $model = $domicilio->domiciliable;
        // dd($model);
        return view('domicilios.edit', ['domicilio' => $domicilio, 'model' => $model, 'modeloTipo' => $modeloTipo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $modeloTipo,Domicilio $domicilio)
    {
        // dd($modeloTipo,$domicilio);
        $request->validate([
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'nullable|string|max:50',
            'cp' => 'required|string|max:10',
        ]);
        $domicilio->update([
            'pais' => 'MEXICO',
            'estado' => $request->estado,
            'municipio' => $request->municipio,
            'ciudad' => $request->ciudad ?? '',
            'colonia' => $request->colonia,
            'calle' => $request->calle,
            'numero_exterior' => $request->numero_exterior,
            'cp' => $request->cp
        ]);

        $model = $domicilio->domiciliable;
        if ($modeloTipo === 'cliente') {
            return redirect()
                ->route('clientes.show', [$model, $model->tipo])
                ->with('success', 'Domicilio agregado correctamente.');
        }
        if ($modeloTipo === 'sucursal') {
            return redirect()
                ->route('sucursales.show', [$model, $model->tipo])
                ->with('success', 'Domicilio agregado correctamente.');
        }
        return redirect()
            ->route("{$modeloTipo}.show", $model)
            ->with('success', 'Domicilio agregado correctamente.');
            }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Domicilio $domicilio)
    {
        //
    }
}
