<?php

namespace App\Http\Controllers;

use App\Models\DatosBancario;
use Illuminate\Http\Request;

class DatosBancarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         //
        $search = $request->get('search');
        $bancos = DatosBancario::when($search, function ($query, $search) {
            $query->where('nombre_banco', 'like', "%{$search}%")->orWhere('cuenta_bancaria', 'like', "%{$search}%");
        })
        ->paginate(10)
        ->withQueryString();
        return view('bancos.index', compact('bancos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('bancos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'nombre_banco' => 'required|string|max:255',
            'cuenta_bancaria' => 'required|string|max:255',
            'clabe' => 'required|string|max:255',
            'correo_electronico' => 'required|email|max:255',
            'whatsapp' => 'required|string|max:255',
        ]);
        $datosBancario = DatosBancario::create($request->all());
        return redirect()->route('bancos.index')->with('success', 'Datos bancarios creados exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show( $banco)
    {
        //
        $banco = DatosBancario::findOrFail($banco);
        return view('bancos.show', compact('banco'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($banco)
    {
        $banco = DatosBancario::findOrFail($banco);
        return view('bancos.edit', compact('banco'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $banco)
    {
                $banco = DatosBancario::findOrFail($banco);
        $request->validate([
            'nombre_banco' => 'required|string|max:255',
            'cuenta_bancaria' => 'required|string|max:255',
            'clabe' => 'required|string|max:255',
            'correo_electronico' => 'required|email|max:255',
            'whatsapp' => 'required|string|max:255',
        ]);
        $banco->update($request->all());
        return redirect()->route('bancos.index')->with('success', 'Datos bancarios actualizados exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($banco)
    {
        $banco = DatosBancario::findOrFail($banco);
        $banco->delete();
        return redirect()->route('bancos.index')->with('success', 'Datos bancarios eliminados exitosamente.');
    }
    public function predeterminado($banco)
    {
        $banco = DatosBancario::findOrFail($banco);
        //Quitar el predeterminado de todos los bancos
        DatosBancario::where('predeterminado', true)->update(['predeterminado' => false]);
        //Colocar el banco seleccionado como predeterminado
        $banco->update(['predeterminado' => true]);
        return redirect()->route('bancos.index')->with('success', 'Banco seleccionado como predeterminado.');
    }
}
