<?php

namespace App\Http\Controllers;

use App\Models\Clasificacion;
use Illuminate\Http\Request;

class ClasificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $search = $request->get('search');
        $clasificaciones = Clasificacion::when($search, function ($query, $search) {
            $query->where('nombre', 'like', "%{$search}%")->orWhere('codigo', 'like', "%{$search}%");
        })
        ->paginate(10)
        ->withQueryString();
        return view('clasificaciones.index', compact('clasificaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('clasificaciones.create');
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
            if (Clasificacion::where('codigo', $value)->exists()) {
                $fail('El código ya existe.');
            }
        }

                ],
            'nombre' => 'required|string|max:255',

        ]);
        $clasificacion = Clasificacion::create([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
        ]);

        return redirect()
            ->route('clasificaciones.index')
            ->with('success', 'Clasificación creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($clasificacion)
    {
        $clasificacion=Clasificacion::findOrFail($clasificacion);
        return view('clasificaciones.show', compact('clasificacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($clasificacion)
    {
                $clasificacion=Clasificacion::findOrFail($clasificacion);
        return view('clasificaciones.edit', compact('clasificacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $clasificacion)
    {
        $request->validate([
            'codigo' => 'required|string|max:50',
            'nombre' => 'required|string|max:255',

            ]);
        $clasificacion=Clasificacion::findOrFail($clasificacion);
        $clasificacion->update([
            'codigo' => $request->codigo,
            'nombre' => $request->nombre,
        ]);
        return redirect()
            ->route('clasificaciones.index')
            ->with('success', 'Clasificación actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($clasificacion)
    {
        $clasificacion=Clasificacion::findOrFail($clasificacion);
        $clasificacion->delete();

    return redirect()
        ->route('clasificaciones.index')
        ->with(
            'success', 'La categoria se ha  eliminado correctamente.'
        );
    }
}
