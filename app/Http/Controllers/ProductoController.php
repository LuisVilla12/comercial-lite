<?php

namespace App\Http\Controllers;

use App\Models\Clasificacion;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
    $search = $request->get('search');

    $productos = Producto::where('estatus_producto', 1)
        ->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nombre_producto', 'like', "%{$search}%")
                ->orWhere('codigo_producto', 'like', "%{$search}%");
            });
        })
        ->orderBy('id', 'desc')
        ->paginate(10)
        ->withQueryString(); // ← mantiene el search en la paginación

    return view('productos.index', compact('productos', 'search'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clasificaciones = Clasificacion::all();
        return view('productos.create', compact('clasificaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
 $request->validate([
            'codigo_producto' => 'required|unique:productos,codigo_producto|string|max:50',
            'nombre_producto' => 'required|string|max:255',
            'clave_sat' => 'required|string|max:13',
            'precio1' => 'required|string|max:255',
            'unidad_medida' => 'required'
        ]);
        $cliente = Producto::create([
            'codigo_producto' => $request->codigo_producto,
            'nombre_producto' => $request->nombre_producto,
            'codigo_alterno' => $request->codigo_alterno,
            'clave_sat' => $request->clave_sat,
            'peso_producto' => $request->peso_producto,
            'unidad_medida' => $request->unidad_medida,
            'valor_clasificacion1' => $request->valor_clasificacion1,
            'precio1' => $request->precio1,
            'precio2' => $request->precio2,
            'precio3' => $request->precio3,
            'precio4' => $request->precio4,
            'precio5' => $request->precio5,
            'precio_calculado' => $request->precio_calculado,
            'importe_extra' => $request->importe_extra,
            'impuesto1' => $request->impuesto1,
            'retencion1' => $request->retencion1,
            'exento_impuesto' => $request->exento_impuesto,
            'estatus_producto' => 1
        ]);

        return redirect()
            ->route('productos.index' )
            ->with('success', 'Producto creado correctamente.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        //
        $clasificaciones = Clasificacion::all();
        return view('productos.edit', compact('producto', 'clasificaciones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        //
            $request->validate([
                'codigo_producto' => 'required|string|max:50,' ,
                'nombre_producto' => 'required|string|max:255',
                'clave_sat' => 'required|string|max:13',
                'precio1' => 'required|string|max:255'
            ]);
             $producto->update($request->all());
             return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        //
        $producto->delete();
        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado');
    }
}
