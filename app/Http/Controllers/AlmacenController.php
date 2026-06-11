<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use Illuminate\Http\Request;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search');
        $almacenes = Almacen::when($search, function ($query, $search) {
            $query->where('nombre', 'like', "%{$search}%")->orWhere('codigo', 'like', "%{$search}%");
        })
        ->paginate(10)
        ->withQueryString();
        return view('almacenes.index', compact('almacenes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('almacenes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
                'codigo' => [
                    'required','string','max:50',
function ($attribute, $value, $fail) {
            if (Almacen::where('codigo', $value)->exists()) {
                $fail('El código ya existe.');
            }
        }
                    ],
                'nombre' => 'required|string|max:255',
                'tipo' => 'required|in:1,2'
            ]);
            $almacen = Almacen::create([
                'codigo' => $request->codigo,
                'nombre' => $request->nombre,
                'tipo' => $request->tipo,
            ]);

            return redirect()
                ->route('almacenes.index')
                ->with('success', 'Almacen creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($almacen)
    {
        $almacen = Almacen::findOrFail($almacen);
        return view('almacenes.show', compact('almacen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($almacen)
    {
                $almacen = Almacen::findOrFail($almacen);
            return view('almacenes.edit', compact('almacen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $almacen)
    {
    $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:1,2,0'
        ]);
        $almacen = Almacen::findOrFail($almacen);
        $almacen->update([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
        ]);
        return redirect()
            ->route('almacenes.index')
            ->with('success', 'Almacen actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($almacen)
    {
    $almacen = Almacen::findOrFail($almacen);
    $almacen->delete();

    return redirect()
        ->route('almacenes.index')
        ->with(
            'success', 'El almacen se ha eliminado correctamente.'
        );
    }
}
