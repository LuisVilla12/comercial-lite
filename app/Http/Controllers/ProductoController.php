<?php

namespace App\Http\Controllers;

use App\Models\Clasificacion;
use App\Models\Producto;
use App\Models\ProductoClave;
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
                $q->where('clave_producto', 'like', "%{$search}%")
                ->orWhere('codigo_producto', 'like', "%{$search}%")
                ->orWhere('nombre_producto', 'like', "%{$search}%");
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
        $claves = ProductoClave::all();
        return view('productos.create', compact('clasificaciones','claves'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
 $request->validate([
            'codigo_producto' => ['required','string','max:50'],
            'nombre_producto' => 'required|string|max:255',
            'precio1' => 'required|string|max:255',
            'unidad_medida' => 'required'
        ]);
        $cliente = Producto::create([
            'codigo_producto' => $request->codigo_producto,
            'clave_producto' => $request->clave_producto,
            'nombre_producto' => $request->nombre_producto,
            'codigo_alterno' => $request->codigo_alterno,
            'clave_sat' => $request->clave_sat,
            'marca' => $request->marca,
            'peso_producto' => $request->peso_producto,
            'volumen' => $request->volumen,
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
    public function show($producto)
    {
        $producto=Producto::findOrFail($producto);
        // $producto = Producto::with('maximominimo')->find($producto);

        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($producto)
    {
        $producto=Producto::findOrFail($producto);
        $claves = ProductoClave::all();
        $clasificaciones = Clasificacion::all();
        return view('productos.edit', compact('producto', 'clasificaciones','claves'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $producto)
    {
            $producto=Producto::findOrFail($producto);

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
    public function destroy($producto)
    {
    $producto=Producto::findOrFail($producto);
        $producto->delete();
        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado');
    }
}
