<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Almacen;
use App\Models\Cliente;
use App\Models\Devolucion;
use App\Models\DevolucionesDetalles;
use App\Models\UsoCfdi;
use App\Models\Sucursal;
use App\Models\DocumentosDetalle;
use App\Models\ExistenciaProducto;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\InventarioService;


class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexCotizacion(Request $request, Sucursal $sucursal)
    {
        $documentos = Documento::where('documento_modelo_id', 1)->where('serie', $sucursal->serie_cotizacion)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('folio', 'like', "%{$search}%")
                        ->orWhereHas('cliente', function ($c) use ($search) {
                            $c->where('nombre', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->fecha === 'hoy', function ($q) {
                $q->whereDate('fecha', now()->toDateString());
            })
            ->orderBy('folio', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('cotizaciones.index', ['documentos' => $documentos, 'sucursal' => $sucursal]);
    }

    public function indexFacturas(Request $request, Sucursal $sucursal)
    {
        $documentos = Documento::where('documento_modelo_id', 2)->where('serie', $sucursal->serie_factura)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('folio', 'like', "%{$search}%")
                        ->orWhereHas('cliente', function ($c) use ($search) {
                            $c->where('nombre', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->fecha === 'hoy', function ($q) {
                $q->whereDate('fecha', now()->toDateString());
            })
            ->orderBy('folio', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('facturas.index', ['documentos' => $documentos, 'sucursal' => $sucursal]);
    }
    public function indexRemisiones(Request $request, Sucursal $sucursal)
    {
        $documentos = Documento::where('documento_modelo_id', 3)->where('serie', $sucursal->serie_remision)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('folio', 'like', "%{$search}%")
                        ->orWhereHas('cliente', function ($c) use ($search) {
                            $c->where('nombre', 'like', "%{$search}%");
                        });
                });
            })
            ->when($request->fecha === 'hoy', function ($q) {
                $q->whereDate('fecha', now()->toDateString());
            })
            ->orderBy('folio', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('remisiones.index', ['documentos' => $documentos, 'sucursal' => $sucursal]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Sucursal $sucursal, $tipo)
    {
        $proveedores = Cliente::all();
        $productos = Producto::all();
        $almacenes = Almacen::all();
        $usos_cfdi = UsoCfdi::all();
        // dd($sucursal);
        return view('documentos.create', [
            'sucursal' => $sucursal,
            'proveedores' => $proveedores,
            'productos' => $productos,
            'almacenes' => $almacenes,
            'tipo' => $tipo,
            'usos' => $usos_cfdi
        ]);
    }

    public function store(Sucursal $sucursal, Request $request)
    {
        // dd($sucursal);
        $productos = collect($request->productos)
            ->filter(fn($p) => !empty($p['producto_id']))
            ->values()
            ->toArray();
        $request->merge([
            'productos' => $productos
        ]);
        $request->validate([
            'proveedor_id' => 'required|exists:clientes,id',
            'almacen_id' => 'required|exists:clientes,id',
            'user_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric',
            'impuestos' => 'required|numeric',
            'total' => 'required|numeric',
            'productos' => 'required|array|min:1',
            'tipo' => 'required',
            // DATOS DE PAGO
            'metodo_pago' => 'required',
            'forma_pago' => 'required',
            'uso_cfdi' => 'required|exists:uso_cfdis,clave',
        ]);
        DB::beginTransaction();

        try {
            if ($request->tipo == 1) {
                $serie = $sucursal->serie_cotizacion;
            } elseif ($request->tipo == 2) {
                $serie = $sucursal->serie_factura;
            } elseif ($request->tipo == 3) {
                $serie = $sucursal->serie_remision;
            } else {
                $serie = 'XX';
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
                'metodo_pago' => $request->metodo_pago,
                'forma_pago' => $request->forma_pago,
                'uso_cfdi' => $request->uso_cfdi,
                'observaciones' => $request->observaciones
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
            ->route('documentos.show', ['sucursal' => $sucursal->id, 'documento' => $documento])
            ->with('open_pdf', true);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sucursal $sucursal, Documento $documento)
    {
        $usos_cfdi = UsoCfdi::all();
        $documento->load([
            'cliente',
            'detalles.producto'
        ]);
        return view('documentos.show', ['sucursal' => $sucursal, 'documento' => $documento, 'usos' => $usos_cfdi,]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sucursal $sucursal, Documento $documento)
    {
        // dd($documento);
        $usos_cfdi = UsoCfdi::all();
        if ($documento->estatus != 1) {
            return redirect()
                ->route('documentos.show', $documento)
                ->with('error', 'La cotización ya fue surtida');
        }

        // ✅ CARGAR RELACIONES PRIMERO
        $documento->load([
            'cliente.domicilios',
            'detalles.producto',
        ]);

        // Calcula el stock
        $documento->detalles->each(function ($d) use ($documento) {
            $d->stock = $d->producto
                ->existencias()
                ->where('almacen_id', $documento->almacen_id)
                ->value('cantidad') ?? 0;
        });

        return view('documentos.edit', ['sucursal' => $sucursal, 'documento' => $documento, 'usos' => $usos_cfdi]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Sucursal $sucursal, Request $request, Documento $documento)
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
            'tipo' => 'required',
            'proveedor_id' => 'required|exists:clientes,id',
            'almacen_id' => 'required|exists:clientes,id',
            'user_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric',
            'impuestos' => 'required|numeric',
            'total' => 'required|numeric',
            'productos' => 'required|array|min:1',
            'metodo_pago' => 'required',
            'forma_pago' => 'required',
            'uso_cfdi' => 'required',
        ]);
        try {
            DB::transaction(function () use ($request, $documento) {

                /* ================= ACTUALIZAR COMPRA ================= */
                $documento->update([
                    'proveedor_id' => $request->proveedor_id,
                    'subtotal' => $request->subtotal,
                    'impuestos' => $request->impuestos,
                    'total' => $request->total,
                    'metodo_pago' => $request->metodo_pago,
                    'forma_pago' => $request->forma_pago,
                    'uso_cfdi' => $request->uso_cfdi,
                    'observaciones' => $request->observaciones
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
        return redirect()->route(
            match ($documento->documento_modelo_id) {
                1 => 'cotizaciones.index',
                2 => 'facturas.index',
                3 => 'remisiones.index',
            },
            $sucursal
        )
            ->with('success', match ($documento->documento_modelo_id) {
                1 => 'Cotización',
                2 => 'Factura',
                3 => 'Remisión'
            } . " a sido actualizada");
    }
    public function pdf(Documento $documento)
    {
        $documento->load([
            'cliente',
            'detalles.producto'
        ]);

        $pdf = Pdf::loadView('documentos.pdf', compact('documento'))
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
            $folio = Documento::where('serie', 'FT')->lockForUpdate()->max('folio');
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
            ->route(route: 'facturas.index')
            ->with('success', 'Factura convertida correctamente');
    }

    public function surtirDocumento(Sucursal $sucursal, Documento $documento)
    {
        if ($documento->estatus != 1) {
            return back()->with('error', 'La remisión ya fue surtida');
        }

        try {
            DB::transaction(function () use ($documento) {
                foreach ($documento->detalles as $detalle) {
                    InventarioService::restar(
                        $detalle->producto_id,
                        $documento->almacen_id,
                        $detalle->cantidad
                    );
                }

                $documento->update(['estatus' => 2]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()
            ->route('documentos.show', ['sucursal' => $sucursal, 'documento' => $documento])
            ->with('success', 'Remisión surtida correctamente');
    }
    public function devolucionEdit(Sucursal $sucursal, Documento $documento)
    {
        $usos_cfdi = UsoCfdi::all();
        // ✅ CARGAR RELACIONES PRIMERO
        $documento->load([
            'cliente.domicilios',
            'detalles.producto',
        ]);

        // Calcula el stock
        $documento->detalles->each(function ($d) use ($documento) {
            $d->stock = $d->producto
                ->existencias()
                ->where('almacen_id', $documento->almacen_id)
                ->value('cantidad') ?? 0;
        });

        return view('documentos.devolucion', ['sucursal' => $sucursal, 'documento' => $documento, 'usos' => $usos_cfdi]);
    }
    public function devolucionUpdate(Request $request, Sucursal $sucursal, Documento $documento)
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
            'proveedor_id' => 'required|exists:clientes,id',
            'user_id' => 'required|exists:users,id',
            'devoluciones' => 'required|json',
            'total' => 'required|numeric',
        ]);
        // Productos que se devolvieron
        $devoluciones = json_decode($request->devoluciones, true);
        // dd($devoluciones);
        try {
            DB::transaction(function () use ($request, $documento) {
                $serie = 'DV';
                $ultimoFolio = Documento::where('serie', $serie)
                    ->lockForUpdate()
                    ->max('folio');
                $siguienteFolio = $ultimoFolio ? $ultimoFolio + 1 : 1;

                $devolucion = Devolucion::create([
                    'documento_id' => $documento->id,
                    'cliente_id' =>  $request->proveedor_id,
                    'user_id' => $request->user_id,
                    'serie' => $serie,
                    'folio' => $siguienteFolio,
                    'fecha' => now()->format('Y-m-d'),
                    'total' => $request->total,
                    'estatus' => 1,
                    'observaciones' => $request->observaciones
                ]);
                DB::commit();
                /* ================= DETALLES ================= */
                foreach ($request->productos as $item) {

                    // Evitar filas vacías (fila extra de Alpine)
                    if (empty($item['producto_id'])) {
                        continue;
                    }

                    // Guardar detalle de devolución
                    DevolucionesDetalles::create([
                        'devolucion_id' => $devolucion->id,
                        'producto_id'   => $item['producto_id'],
                        'cantidad'      => $item['cantidad'],
                        'costo_unitario' => $item['costo'],
                        'importe'       => $item['importe'],
                    ]);

                    // Restar inventario (permite negativo)
                    $existencia = ExistenciaProducto::where('producto_id', $item['producto_id'])
                        ->where('almacen_id', $devolucion->almacen_id)
                        ->lockForUpdate()
                        ->first();

                    if ($existencia) {
                        $existencia->decrement('cantidad', $item['cantidad']);
                    } else {
                        // Si no existe registro, créalo en negativo
                        ExistenciaProducto::create([
                            'producto_id' => $item['producto_id'],
                            'almacen_id'  => $devolucion->almacen_id,
                            'cantidad'    => -$item['cantidad'],
                        ]);
                    }
                }
            });
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
