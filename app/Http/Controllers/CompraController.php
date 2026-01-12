<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use Illuminate\Http\Request;
use App\Models\Almacen;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Compras_detalle;
use App\Models\ExistenciaProducto;
use Illuminate\Support\Facades\DB;


class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $compras = Compra::when($search, function ($query, $search) {
            $query->where('serie', 'like', "%{$search}%")->orWhere('folio', 'like', "%{$search}%");
        })
            ->paginate(10)
            ->withQueryString();
        return view('compras.index', compact('compras'));
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
        // Verificar arreglo de productos para quitar los vacios
        $productos = collect($request->productos)
            ->filter(fn($p) => !empty($p['producto_id']))
            ->values()
            ->toArray();
        $request->merge([
            'productos' => $productos
        ]);

        // Validar detalles de la compra
        $request->validate([
            // Compra
            'proveedor_id' => 'required|exists:clientes,id',
            'almacen_id'        => 'required|exists:clientes,id',
            'user_id'      => 'required|exists:users,id',
            'fecha'        => 'required|date',
            'subtotal'        => 'required|numeric',
            'impuestos'        => 'required|numeric',
            'total'        => 'required|numeric',
            'productos' => 'required|array|min:1'
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
                    'costo_unitario'       => $item['costo'],
                    'importe'     => $item['cantidad'] * $item['costo'],
                ]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect()->route('compras.index')
            ->with('success', 'Compra creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra)
    {
        $compra->load([
            'proveedor',
            'detalles.producto'
        ]);
        return view('compras.show', compact('compra'));
    }
    public function surtir(Compra $compra)
    {
        if ($compra->estatus != 1) {
            return redirect()
            ->route('compras.show', $compra)
            ->with('error', 'La compra ya fue surtida');
        }

        DB::transaction(function () use ($compra) {
            foreach ($compra->detalles as $detalle) {
                ExistenciaProducto::updateOrCreate(
                    [
                        'producto_id' => $detalle->producto_id,
                        'almacen_id' => $compra->almacen_id
                    ],
                    [
                        'cantidad' => DB::raw('cantidad + ' . $detalle->cantidad)
                    ]
                );
            }

            $compra->update([
                'estatus' => '2'
            ]);
        });

        return redirect()
            ->route('compras.show', $compra)
            ->with('success', 'Compra surtida correctamente');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Compra $compra)
    {
        if ($compra->estatus != 1) {
            return redirect()
            ->route('compras.show', $compra)
            ->with('error', 'La compra ya fue surtida');
        }

        // dd($compra->)
        $compra->load([
            'proveedor',
            'detalles.producto'
        ]);
        return view('compras.edit', compact('compra'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Compra $compra)
    {
        // Verificar arreglo de productos para quitar los vacios
        $productos = collect($request->productos)
            ->filter(fn($p) => !empty($p['producto_id']))
            ->values()
            ->toArray();
        $request->merge([
            'productos' => $productos
        ]);
        $request->validate([
            'proveedor_id' => 'required|exists:clientes,id',
            'almacen_id'        => 'required|exists:clientes,id',
            'user_id'      => 'required|exists:users,id',
            'fecha'        => 'required|date',
            'subtotal'        => 'required|numeric',
            'impuestos'        => 'required|numeric',
            'total'        => 'required|numeric',
            //Detalles compra
            'productos' => 'required|array|min:2'
        ]);
        try {
            DB::transaction(function () use ($request, $compra) {

                /* ================= ACTUALIZAR COMPRA ================= */
                $compra->update([
                    'proveedor_id' => $request->proveedor_id,
                    'subtotal' => $request->subtotal,
                    'impuestos' => $request->impuestos,
                    'total' => $request->total,
                ]);

                /* ================= DETALLES ================= */
                $detallesExistentes = $compra->detalles()->pluck('id')->toArray();
                $detallesEnFormulario = [];

                foreach ($request->productos as $producto) {

                    $detalle = $compra->detalles()->updateOrCreate(
                        [
                            'id' => $producto['detalle_id'] ?? null
                        ],
                        [
                            'producto_id' => $producto['producto_id'],
                            'cantidad' => $producto['cantidad'],
                            'costo_unitario' => $producto['costo'],
                            'importe' => $producto['cantidad'] * $producto['costo'],
                        ]
                    );

                    $detallesEnFormulario[] = $detalle->id;
                }

                /* ================= ELIMINAR DETALLES BORRADOS ================= */
                $detallesParaEliminar = array_diff(
                    $detallesExistentes,
                    $detallesEnFormulario
                );

                if (!empty($detallesParaEliminar)) {
                    $compra->detalles()->whereIn('id', $detallesParaEliminar)->delete();
                }
            });
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect()
            ->route('compras.index')
            ->with('success', 'Compra actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Compra $compra)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Eliminar detalles
            $compra->detalles()->delete();

            // 2️⃣ Eliminar compra
            $compra->delete();

            DB::commit();

            return redirect()
                ->route('compras.index')
                ->with('success', 'Compra eliminada correctamente');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Error al eliminar la compra');
        }
    }
}
