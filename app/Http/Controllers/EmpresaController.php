<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Regimen;
use Illuminate\Http\Request;

use function Ramsey\Uuid\v1;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Empresa::all();
        // dd(vars: $empresas);
        return view('empresas.index', ['empresas' => $empresas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $regimenes = Regimen::all();
        return view('empresas.create', ['regimenes' => $regimenes]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:250',
            'rfc' => 'required|string|max:13',
            'regimen_fiscal' => 'required|string|max:250',
            'email' => 'required|email',
        ]);
        Empresa::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'rfc' => $request->rfc,
            'regimen_fiscal' => $request->regimen_fiscal,
            'curp' => $request->curp,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'activo' => 1,
        ]);
        $empresas = Empresa::all();
        return view('empresas.index',['empresas'=>$empresas])->with('success',   'La empresa ha sido registrada.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Empresa $empresa)
    {
            $regimenes = Regimen::all();
        return view('empresas.show',['empresa'=>$empresa,'regimenes'=>$regimenes]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Empresa $empresa)
    {
        $regimenes = Regimen::all();
        return view('empresas.edit', ['empresa'=>$empresa,'regimenes' => $regimenes]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Empresa $empresa)
    {
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:250',
            'rfc' => 'required|string|max:13',
            'regimen_fiscal' => 'required|string|max:250',
            'email' => 'required|email',
        ]);
        $empresa->update($request->all());
        return redirect()->route('empresas.show', $empresa)
            ->with('success', 'Empresa ha sido actualizado' );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Empresa $empresa)
    {
        //
    }
}
