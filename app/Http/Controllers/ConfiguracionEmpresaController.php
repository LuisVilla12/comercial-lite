<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConfiguracionEmpresa;
use App\Models\Regimen;

class ConfiguracionEmpresaController extends Controller
{
    //
    public function show(){
    $empresa=ConfiguracionEmpresa::first();
    $regimenes=Regimen::all();

    return view('empresa-config.show',['empresa'=>$empresa,'regimenes'=>$regimenes]);
}
public function edit(){
    $empresa=ConfiguracionEmpresa::first();
    $regimenes=Regimen::all();
    return view('empresa-config.edit',['empresa'=>$empresa,'regimenes'=>$regimenes]);
}
    public function update(Request $request,$empresa)
    {
        $empresa = ConfiguracionEmpresa::findOrFail($empresa);
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:250',
            'rfc' => 'required|string|max:13',
            'regimen_fiscal' => 'required|string|max:250',
            'email' => 'required|email',
            // DATOS DE DOMILIO
            'estado' => 'required|string|max:100',
            'municipio' => 'required|string|max:100',
            'ciudad' => 'required|string|max:100',
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'string|max:50',
            'cp' => 'required|string|max:6',
        ]);

        $empresa->update($request->all());
        return redirect()->route('configuracion-empresa.show', $empresa)
            ->with('success', 'Empresa ha sido actualizado');
    }
}
