<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agente;

class AgenteController extends Controller
{
    //
    public function index(Request $request)
    {
        // return view('agentes.index');
        $search = $request->get('search');
        $agentes = Agente::when($search, function ($query, $search) {
            $query->where('nombre', 'like', "%{$search}%")->orWhere('codigo', 'like', "%{$search}%");
        })
        ->paginate(10)
        ->withQueryString();
        return view('agentes.index', compact('agentes'));
    }

    public function create()
    {
        return view('agentes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|unique:agentes',
            'nombre' => 'required',
        ]);
        Agente::create($request->all());
        return redirect()->route('agentes.index');
    }
    public function show(Agente $agente)
    {
        return view('agentes.show', compact('agente'));
    }
    public function edit(Agente $agente)
    {
        return view('agentes.edit', compact('agente'));
    }
    public function update(Request $request, Agente $agente)
    {
        $request->validate([
            'codigo' => 'required|unique:agentes,codigo,' . $agente->id,
            'nombre' => 'required',
        ]);
        $agente->update($request->all());
        return redirect()->route('agentes.index');
    }
    public function destroy(Agente $agente)
    {
        $agente->delete();
        return redirect()->route('agentes.index');
    }
}
