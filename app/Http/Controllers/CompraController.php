<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;
use App\Models\Almacen;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Compras_detalle;
use Illuminate\Support\Facades\DB;


class CompraController extends Controller
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
    public function create()
    {
        $proveedores = Cliente::all();
        $productos = Producto::all();
        $almacenes = Almacen::all();
        return view('compras.create', [
            'proveedores' =>  $proveedores,
            'productos' => $productos,
            'almacenes' => $almacenes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar detalles de la compra
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.costo' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $serie = 'CPX'; // o lo que definas
            $ultimoFolio = Compra::where('serie', $serie)
                ->lockForUpdate()
                ->max('folio');
            $siguienteFolio = $ultimoFolio ? $ultimoFolio + 1 : 1;

            $compra = Compra::create([
                'serie'        => $serie,
                'folio'        => $siguienteFolio,
                'proveedor_id' => $request->proveedor_id,
                'almacen_id'   => $request->almacen_id,
                'user_id'      => $request->user_id,
                'fecha'        => $request->fecha,
                'subtotal'     => $request->subtotal,
                'impuestos' => $request->impuestos,
                'total' => $request->total,
                'estatus'      => 1,
            ]);
            DB::commit();

            foreach ($request->productos as $item) {
                // Evitar filas vacías (la fila extra de Alpine)
                if (empty($item['producto_id'])) {
                    continue;
                }

                Compras_detalle::create([
                    'compra_id'   => $compra->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad'    => $item['cantidad'],
                    'costo'       => $item['costo'],
                    'importe'     => $item['cantidad'] * $item['costo'],
                ]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compra $compra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra)
    {
        //
    }
}
