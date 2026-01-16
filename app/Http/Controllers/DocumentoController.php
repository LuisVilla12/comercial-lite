<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Almacen;
use App\Models\Cliente;
use App\Models\DocumentosDetalle;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexCotizacion(Request $request)
    {
        $search = $request->get('search');
        $documentos = Documento::where('documento_modelo_id', 1)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('serie', 'like', "%{$search}%")
                        ->orWhere('folio', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();
        return view('cotizaciones.index', compact(var_name: 'documentos'));
    }
    public function indexFacturas(Request $request)
    {
          $search = $request->get('search');
        $documentos = Documento::where('documento_modelo_id', 2)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('serie', 'like', "%{$search}%")
                        ->orWhere('folio', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();
        return view('facturas.index', compact('documentos'));
    }
 public function indexRemisiones(Request $request)
    {
        $search = $request->get('search');
        $documentos = Documento::where('documento_modelo_id', 3)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('serie', 'like', "%{$search}%")
                        ->orWhere('folio', 'like', "%{$search}%");
                });
            })
            ->paginate(10)
            ->withQueryString();
        return view('remisiones.index', compact(var_name: 'documentos'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create($tipo)
    {
        $proveedores = Cliente::all();
        $productos = Producto::all();
        $almacenes = Almacen::all();
        return view('documentos.create', [
            'proveedores' => $proveedores,
            'productos' => $productos,
            'almacenes' => $almacenes,
            'tipo'=>$tipo
        ]);
    }

    public function store(Request $request)
    {
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
            'almacen_id' => 'required|exists:clientes,id',
            'user_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric',
            'impuestos' => 'required|numeric',
            'total' => 'required|numeric',
            'productos' => 'required|array|min:1',
            'tipo'=>'required'
        ]);
        DB::beginTransaction();

        try {
            if($request->tipo==1){
            $serie = 'CT';
            }elseif($request->tipo==2){
            $serie = 'FT';
            }
            else{
            $serie = 'RT';
            }
            $ultimoFolio = Documento::where('serie', $serie)
                ->lockForUpdate()
                ->max('folio');
            $siguienteFolio = $ultimoFolio ? $ultimoFolio + 1 : 1;

            $documento = Documento::create([
                'documento_modelo_id' => $request->tipo,
                'serie' => $serie,
                'folio' => $siguienteFolio,
                'fecha' => $request->fecha,
                'cliente_id' => $request->proveedor_id,
                'almacen_id' => $request->almacen_id,
                'user_id' => $request->user_id,
                'subtotal' => $request->subtotal,
                'impuestos' => $request->impuestos,
                'total' => $request->total,
                'estatus' => 1,
            ]);

            DB::commit();
            // CREAR DETALLES DOCUMENTOS
            foreach ($request->productos as $item) {
                // Evitar filas vacías (la fila extra de Alpine)
                if (empty($item['producto_id'])) {
                    continue;
                }
                DocumentosDetalle::create([
                    'documento_id' => $documento->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'costo_unitario' => $item['costo'],
                    'importe' => $item['importe'],
                ]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        // return redirect()->route('cotizacion.index')
        //     ->with('success', 'Cotización creada correctamente.');
        return redirect()
            ->route('documentos.show', $documento)
            ->with('open_pdf', true);
    }

    /**
     * Display the specified resource.
     */
    public function show(Documento $documento)
    {
        // dd(vars: $documento);
        $documento->load([
            'cliente',
            'detalles.producto'
        ]);
        return view('documentos.show', compact('documento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Documento $documento)
    {
        if ($documento->estatus != 1) {
            return redirect()
                ->route('cotizacion.show', $documento)
                ->with('error', 'La cotización ya fue surtida');
        }

        // ✅ CARGAR RELACIONES PRIMERO
        $documento->load([
            'cliente',
            'detalles.producto'
        ]);

        // Calcula el stock
        $documento->detalles->each(function ($d) use ($documento) {
            $d->stock = $d->producto
                ->existencias()
                ->where('almacen_id', $documento->almacen_id)
                ->value('cantidad') ?? 0;
        });

        return view('cotizacion.edit', compact('documento'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Documento $documento)
    {
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
            'almacen_id' => 'required|exists:clientes,id',
            'user_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric',
            'impuestos' => 'required|numeric',
            'total' => 'required|numeric',
            'productos' => 'required|array|min:1'
        ]);
        try {
            DB::transaction(function () use ($request, $documento) {

                /* ================= ACTUALIZAR COMPRA ================= */
                $documento->update([
                    'proveedor_id' => $request->proveedor_id,
                    'subtotal' => $request->subtotal,
                    'impuestos' => $request->impuestos,
                    'total' => $request->total,
                ]);

                /* ================= DETALLES ================= */
                $detallesExistentes = $documento->detalles()->pluck('id')->toArray();
                $detallesEnFormulario = [];

                foreach ($request->productos as $producto) {

                    $detalle = $documento->detalles()->updateOrCreate(
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
                    $documento->detalles()->whereIn('id', $detallesParaEliminar)->delete();
                }
            });
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect()
            ->route(route: 'cotizacion.index')
            ->with('success', 'Cotizacion actualizada correctamente');
    }
    public function pdf(Documento $documento)
    {
        $documento->load([
            'cliente',
            'detalles.producto'
        ]);

        $pdf = Pdf::loadView('cotizacion.pdf', compact('documento'))
            ->setPaper('letter');

        return $pdf->stream("Cotizacion_{$documento->serie}-{$documento->folio}.pdf");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Documento $documento)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Eliminar detalles
            $documento->detalles()->delete();

            // 2️⃣ Eliminar compra
            $documento->delete();

            DB::commit();

            return redirect()
                ->route('cotizacion.index')
                ->with('success', 'cotización eliminada correctamente');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Error al eliminar la cotización');
        }
    }

    public function convertirFactura(Documento $documento)
    {
        DB::transaction(function () use ($documento) {
            $folio = Documento::where('serie', 'R')->lockForUpdate()->max('folio');
            $folio = ($folio ?? 0) + 1;
            // Crear remisión
            $remision = Documento::create([
                'documento_modelo_id' => 2, // remisión
                'serie'               => 'R',
                'folio'               => $folio,
                'fecha'                => now(),
                'cliente_id'          => $documento->cliente_id,
                'almacen_id'          => $documento->almacen_id,
                'user_id'             => $documento->user_id,
                'subtotal'            => $documento->subtotal,
                'impuestos'            => $documento->impuestos,
                'total'               => $documento->total,
                'estatus'             => 1,
            ]);

            // Copiar detalles
            foreach ($documento->detalles as $detalle) {
                $remision->detalles()->create([
                    'producto_id' => $detalle->producto_id,
                    'cantidad'    => $detalle->cantidad,
                    'costo_unitario'      => $detalle->costo_unitario,
                    'importe'     => $detalle->importe,
                ]);
            }

            // Marcar cotización como convertida
            $documento->update([
                'estatus' => 2 // convertida
            ]);
        });
        return redirect()
            ->route(route: 'cotizacion.index')
            ->with('success', 'Cotizacion convertida correctamente');
    }
}
