<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\ConfiguracionEmpresa;
use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Agente;
use App\Models\Almacen;
use App\Models\Cliente;
use App\Models\Devolucion;
use App\Models\DevolucionesDetalles;
use App\Models\UsoCfdi;
use App\Models\Sucursal;
use App\Models\Punto;
use App\Models\DocumentosDetalle;
use App\Models\ExistenciaProducto;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\InventarioService;
use App\Mail\DocumentoMail;
use App\Models\DatosBancario;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;


class DocumentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexCotizacion(Request $request, $sucursal)
    {
        $sucursal = Sucursal::findOrFail($sucursal);
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

    public function indexFacturas(Request $request, $sucursal)
    {
        $sucursal = Sucursal::findOrFail($sucursal);
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
    public function indexRemisiones(Request $request, $sucursal)
    {
        $sucursal = Sucursal::findOrFail($sucursal);
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
    public function create($sucursal, $tipo)
    {
        $proveedores = Cliente::all();
        $productos = Producto::all();
        $almacenes = Almacen::all();
        $usos_cfdi = UsoCfdi::all();
        $agentes = Agente::all();
        $sucursal = Sucursal::findOrFail($sucursal);

        // dd($sucursal);
        return view('documentos.create', [
            'sucursal' => $sucursal,
            'proveedores' => $proveedores,
            'productos' => $productos,
            'almacenes' => $almacenes,
            'tipo' => $tipo,
            'usos' => $usos_cfdi,
            'agentes' => $agentes
        ]);
    }

    public function store($sucursal, Request $request)
    {
        $sucursal = Sucursal::findOrFail($sucursal);

        $productos = collect($request->productos)
            ->filter(fn($p) => !empty($p['producto_id']))
            ->values()
            ->toArray();
        $request->merge([
            'productos' => $productos
        ]);
                        // dd($productos);
        $request->validate([
            'proveedor_id' => 'required',
            'almacen_id' => 'required',
            'sucursal_id' => 'required',
            'user_id' => 'required',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric',
            'impuestos' => 'required|numeric',
            'total' => 'required|numeric',
            'productos' => 'required|array|min:1',
            'tipo' => 'required',
            // DATOS DE PAGO
            'metodo_pago' => 'required',
            'forma_pago' => 'required',
            'uso_cfdi' => 'required',
            //DATOS DE DOMICLIO
            'colonia' => 'required|string|max:100',
            'calle' => 'required|string|max:255',
            'numero_exterior' => 'nullable|string|max:50',
            'agente_id' => 'required',
            'codigo_postal' => 'required|string|max:10',
        ]);
        DB::beginTransaction();
        // EFECTUAR LA COMPRA
        try {
            $sucursal = Sucursal::lockForUpdate()->find($sucursal->id);

            switch ($request->tipo) {
                case 1:
                    $serie = $sucursal->serie_cotizacion;
                    $siguienteFolio = $sucursal->folio_cotizacion + 1;
                    $sucursal->folio_cotizacion = $siguienteFolio;
                    break;

                case 2:
                    $serie = $sucursal->serie_factura;
                    $siguienteFolio = $sucursal->folio_factura + 1;
                    $sucursal->folio_factura = $siguienteFolio;
                    break;

                case 3:
                    $serie = $sucursal->serie_remision;
                    $siguienteFolio = $sucursal->folio_remision + 1;
                    $sucursal->folio_remision = $siguienteFolio;
                    break;

                default:
                    $serie = 'XX';
                    $siguienteFolio = 1;
                    break;
            }
            $documento = Documento::create([
                'sucursal_id' => $request->sucursal_id,
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
                'vigencia' => $request->vigencia,
                'agente_id' => $request->agente_id,
                'observaciones' => $request->observaciones,
                'estado' => 'PENDIENTE',
            ]);
            //  Guardas el nuevo folio en sucursal
            $sucursal->save();

            // ASIGNAR DOMICILIO AL DOCUMENTO
            // if($documento->documento_modelo_id == 2){
            $documento->domicilios()->create([
                'pais' => 'MEXICO',
                'estado' => $request->estado,
                'municipio' => $request->municipio . '',
                'ciudad' => $request->ciudad ?? '',
                'colonia' => $request->colonia,
                'calle' => $request->calle,
                'numero_exterior' => $request->numero_exterior,
                'cp' => $request->codigo_postal
            ]);
            // }


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



        return redirect()
            ->route('documentos.show', ['sucursal' => $sucursal->id, 'documento' => $documento]);
        // ->with('open_pdf', true);
    }

    /**
     * Display the specified resource.
     */
    public function show($sucursal,  $documento)
    {
        $sucursal = Sucursal::findOrFail($sucursal);
        $documento = Documento::findOrFail($documento);
        $usos_cfdi = UsoCfdi::all();
        $agentes = Agente::all();
        $documento->load([
            'cliente',
            'detalles.producto',
            'domicilios',
        ]);
        // dd($documento);
        return view('documentos.show', ['sucursal' => $sucursal, 'documento' => $documento, 'usos' => $usos_cfdi, 'agentes' => $agentes]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( $sucursal,  $documento)
    {
        $documento = Documento::findOrFail($documento);
        $sucursal = Sucursal::findOrFail($sucursal);
        $usos_cfdi = UsoCfdi::all();
        $agentes = Agente::all();
        if ($documento->estatus != 1) {
            return redirect()
                ->route('documentos.show', $documento)
                ->with('error', 'La cotización ya fue surtida');
        }

        // ✅ CARGAR RELACIONES PRIMERO
        $documento->load([
            'cliente.domicilios',
            'detalles.producto',
            'domicilios'
        ]);

        // Calcula el stock
        $documento->detalles->each(function ($d) use ($documento) {
            $d->stock = $d->producto
                ->existencias()
                ->where('almacen_id', $documento->almacen_id)
                ->value('cantidad') ?? 0;
        });

        $agentes = Agente::all();

        return view('documentos.edit', ['sucursal' => $sucursal, 'documento' => $documento, 'usos' => $usos_cfdi, 'agentes' => $agentes]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update($sucursal, Request $request, $documento)
    {
        $documento = Documento::findOrFail($documento);
        $sucursal = Sucursal::findOrFail($sucursal);
        $productos = collect($request->productos)
            ->filter(fn($p) => !empty($p['producto_id']))
            ->values()
            ->toArray();

        $request->merge([
            'productos' => $productos
        ]);

        $request->validate([
            'tipo' => 'required',
            'proveedor_id' => 'required',
            'almacen_id' => 'required',
            'user_id' => 'required',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric',
            'impuestos' => 'required|numeric',
            'total' => 'required|numeric',
            'productos' => 'required|array|min:1',
            'metodo_pago' => 'required',
            'forma_pago' => 'required',
            'uso_cfdi' => 'required',
        ]);

        $documento = DB::transaction(function () use ($request, $documento) {

            /* ================= ACTUALIZAR DOCUMENTO ================= */
            $documento->update([
                'proveedor_id' => $request->proveedor_id,
                'subtotal' => $request->subtotal,
                'impuestos' => $request->impuestos,
                'total' => $request->total,
                'metodo_pago' => $request->metodo_pago,
                'forma_pago' => $request->forma_pago,
                'uso_cfdi' => $request->uso_cfdi,
                'observaciones' => $request->observaciones,
                'estado' => 'PENDIENTE',
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
                $documento->detalles()
                    ->whereIn('id', $detallesParaEliminar)
                    ->delete();
            }

            return $documento;
        });

        return redirect()->route('documentos.show', [
            'sucursal'  => $sucursal->id,
            'documento' => $documento->id,
        ])->with(
            'success',
            match ($documento->documento_modelo_id) {
                1 => 'Cotización',
                2 => 'Factura',
                3 => 'Remisión',
            } . ' ha sido actualizada'
        );
    }
    public function pdf($sucursal,  $documento)
    {
        $documento = Documento::findOrFail($documento);
        $sucursal = Sucursal::findOrFail($sucursal);
        $empresa = ConfiguracionEmpresa::first();
        // Seleccionar los datos bancarios
        $banco = DatosBancario::where('predeterminado', true)->first();

        $documento->load([
            'cliente',
            'detalles.producto'
        ]);

        $pdf = Pdf::loadView('documentos.pdf', compact('documento', 'sucursal', 'banco','empresa'))
            ->setPaper('letter');

        return $pdf->stream("documento_{$documento->serie}-{$documento->folio}.pdf");
    }

    public function pdfTicket($sucursal,  $documento, $mm = 80)
    {
        $sucursal = Sucursal::findOrFail($sucursal);
        $documento = Documento::findOrFail($documento);
        $empresa = ConfiguracionEmpresa::first();
        $documento->load(['cliente', 'detalles.producto']);
        // dd($documento);
        $width = $mm == 58 ? 164 : 227;

        $customPaper = [0, 0, $width, 256];

        $pdf = Pdf::loadView('documentos.pdf_ticket', compact('documento', 'sucursal','empresa'))
            ->setPaper($customPaper);

        return $pdf->stream("Ticket{$mm}_{$documento->serie}-{$documento->folio}.pdf");
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $documento)
    {
        DB::beginTransaction();

        try {
            $documento = Documento::findOrFail($documento);
            // 1️⃣ Eliminar detalles
            $documento->detalles()->delete();

            // 2️⃣ Eliminar compra
            $documento->delete();

            DB::commit();

            return redirect()
                ->route('cotizacion.index')
                ->with('success', 'Documento eliminado correctamente');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Error al eliminar el documento');
        }
    }
    //
    public function convertir( $sucursal, $documento, $tipo)
    {
        $documento = Documento::findOrFail($documento);
        $sucursal = Sucursal::findOrFail($sucursal);
        $documento_convertido = DB::transaction(function () use ($documento, $sucursal, $tipo) {

            if ($tipo == '2') {
                $serie = $sucursal->serie_factura;
            } elseif ($tipo == '3') {
                $serie = $sucursal->serie_remision;
            } else {
                $serie = 'XX';
            }

            $ultimoFolio = Documento::where('serie', $serie)
                ->lockForUpdate()
                ->max('folio');

            $siguienteFolio = $ultimoFolio ? $ultimoFolio + 1 : 1;

            // Crear documento
            $documento_convertido = Documento::create([
                'sucursal_id'         => $documento->sucursal_id,
                'documento_modelo_id' => $tipo,
                'serie'               => $serie,
                'folio'               => $siguienteFolio,
                'fecha'               => now(),
                'cliente_id'          => $documento->cliente_id,
                'almacen_id'          => $documento->almacen_id,
                'user_id'             => $documento->user_id,
                'subtotal'            => $documento->subtotal,
                'impuestos'           => $documento->impuestos,
                'total'               => $documento->total,
                'estatus'             => 1,
                'metodo_pago'         => $documento->metodo_pago,
                'forma_pago'          => $documento->forma_pago,
                'uso_cfdi'            => $documento->uso_cfdi,
                'observaciones'       => $documento->observaciones,
                'agente_id' => $documento->agente_id,
            ]);
            // ASIGNAR DOMICILIO AL DOCUMENTO
            $documento_convertido->domicilios()->create([
                'pais' => 'MEXICO',
                'estado' => $documento->domicilios->first()->estado ?? '',
                'municipio' => $documento->domicilios->first()->municipio ?? '',
                'ciudad' => $documento->domicilios->first()->ciudad ?? '',
                'colonia' => $documento->domicilios->first()->colonia ?? '',
                'calle' => $documento->domicilios->first()->calle ?? '',
                'numero_exterior' => $documento->domicilios->first()->numero_exterior ?? '',
                'cp' => $documento->domicilios->first()->cp ?? '',
            ]);
            // Copiar detalles
            foreach ($documento->detalles as $detalle) {
                $documento_convertido->detalles()->create([
                    'producto_id'   => $detalle->producto_id,
                    'cantidad'      => $detalle->cantidad,
                    'costo_unitario' => $detalle->costo_unitario,
                    'importe'       => $detalle->importe,
                ]);
            }

            // Marcar documento original como convertido
            $documento->update([
                'estatus' => 2,
            ]);

            return $documento_convertido;
        });

        return redirect()
            ->route('documentos.show', [
                'sucursal' => $sucursal,
                'documento' => $documento_convertido
            ])
            ->with(
                'success',
                match ($tipo) {
                    '2' => 'Factura',
                    '3' => 'Remisión',
                } . ' ha sido transformada'
            );
    }


    public function surtir( $sucursal, $documento)
    {
        $sucursal = Sucursal::findOrFail($sucursal);
        $documento = Documento::findOrFail($documento);
        if ($documento->estatus != 1) {
            return back()->with('error', 'La remisión ya fue surtida');
        }

        try {
            DB::transaction(function () use ($documento) {
                // Resta a inventario
                foreach ($documento->detalles as $detalle) {
                    InventarioService::restar(
                        $detalle->producto_id,
                        $documento->almacen_id,
                        $detalle->cantidad
                    );
                }
                //Documento afectado
                $documento->update(['estatus' => 4]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        // EJECUTA PUNTOS
        try {
            $puntos = Punto::firstOrCreate([
                'cliente_id' => $documento->cliente_id
            ]);
            DB::transaction(function () use ($puntos, $documento) {
                $puntos->increment('total_puntos', 10);

                $puntos->movimientos()->create([
                    'puntos_id' => $puntos->id,
                    'documento_id' => $documento->id,
                    'tipo' => 'suma',
                    'concepto' => 'Compra',
                    'puntos' => 10,
                    'referencia' => $documento->serie . $documento->folio,
                ]);
            });
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()
            ->route('documentos.show', ['sucursal' => $sucursal, 'documento' => $documento])
            ->with('success', 'Remisión surtida correctamente');
    }
    public function devolucionEdit( $sucursal,  $documento)
    {
                $sucursal = Sucursal::findOrFail($sucursal);
        $documento = Documento::findOrFail($documento);

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
    public function devolucionUpdate(Request $request,  $sucursal,  $documento)
    {
                $sucursal = Sucursal::findOrFail($sucursal);
        $documento = Documento::findOrFail($documento);

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
        // dd($request->all());
        $devolucion = null;

        try {
            DB::transaction(function () use ($request, $sucursal, $documento, &$devolucion) {

                // Actualizar estatus del documento original
                $documento->update([
                    'estatus' => 5
                ]);

                $serie = $sucursal->serie_devolucion;

                $ultimoFolio = Devolucion::where('serie', $serie)
                    ->lockForUpdate()
                    ->max('folio');

                $siguienteFolio = $ultimoFolio ? $ultimoFolio + 1 : 1;

                $devolucion = Devolucion::create([
                    'documento_id' => $documento->id,
                    'cliente_id'   => $request->proveedor_id,
                    'user_id'      => $request->user_id,
                    'almacen_id'   => $request->almacen_id,
                    'serie'        => $serie,
                    'folio'        => $siguienteFolio,
                    'fecha'        => now()->format('Y-m-d'),
                    'total'        => $request->total,
                    'estatus'      => 5,
                    'observaciones' => $request->observaciones,
                ]);

                /* ================= DETALLES ================= */
                // Arreglo de productos que devolvio
                // $devoluciones = json_decode($request->devoluciones, true);
                $devoluciones = $request->productos;
                foreach ($devoluciones as $item) {
                    if (empty($item['producto_id'])) {
                        continue;
                    }

                    DevolucionesDetalles::create([
                        'devolucion_id'  => $devolucion->id,
                        'producto_id'    => $item['producto_id'],
                        'cantidad'       => $item['cantidad'],
                        // 'costo_unitario' => $item['costo_unitario'],
                        'costo_unitario' => $item['costo'],
                        'importe'        => $item['importe'],
                    ]);

                    $existencia = ExistenciaProducto::where('producto_id', $item['producto_id'])
                        ->where('almacen_id', $devolucion->almacen_id)
                        ->lockForUpdate()
                        ->first();

                    if ($existencia) {
                        $existencia->increment('cantidad', $item['cantidad']);
                    } else {
                        ExistenciaProducto::create([
                            'producto_id' => $item['producto_id'],
                            'almacen_id'  => $devolucion->almacen_id,
                            'cantidad'    => -$item['cantidad'],
                        ]);
                    }
                }
            });
        } catch (\Throwable $e) {
            throw $e;
        }
        $usos_cfdi = UsoCfdi::all();

        return view('devoluciones.show', [
            'sucursal' => $sucursal,
            'documento' => $devolucion,
            'usos' => $usos_cfdi,
        ]);
    }
    // AFECTA INVENTARIO al hacer una factura
    public function surtirFactura( $sucursal,  $documento)
    {
            $sucursal = Sucursal::findOrFail($sucursal);
            $documento = Documento::findOrFail($documento);
        if ($documento->estatus != 1) {
            return back()->with('error', 'Factura ya fue surtida');
        }

        try {
            DB::transaction(function () use ($documento) {
                // Resta a inventario
                foreach ($documento->detalles as $detalle) {
                    InventarioService::restar(
                        $detalle->producto_id,
                        $documento->almacen_id,
                        $detalle->cantidad
                    );
                }
                //Documento afectado
                $documento->update(['estatus' => 4]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
        // EJECUTA PUNTOS
        try {
            $puntos = Punto::firstOrCreate([
                'cliente_id' => $documento->cliente_id
            ]);
            DB::transaction(function () use ($puntos, $documento) {
                $puntos->increment('total_puntos', 10);

                $puntos->movimientos()->create([
                    'puntos_id' => $puntos->id,
                    'documento_id' => $documento->id,
                    'tipo' => 'suma',
                    'concepto' => 'Factura',
                    'puntos' => 10,
                    'referencia' => $documento->serie . $documento->folio,
                ]);
            });
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }


        return redirect()
            ->route('documentos.show', ['sucursal' => $sucursal, 'documento' => $documento])
            ->with('success', 'Factura timbrada correctamente');
    }

    public function timbrarSAT($documento)
    {
        $documento = Documento::findOrFail($documento);

        if ($documento->uuid) {
            return response()->json([
                'message' => 'Este documento ya está timbrado'
            ], 422);
        }
        $empresa = Empresa::where('id', 1)->first();

        $data = $this->buildFacturamaJson($documento, $empresa);
        dd($data);
        $response = Http::withBasicAuth(
            env('FACTURAMA_USER'),
            env('FACTURAMA_PASSWORD')
        )->post(
            env('FACTURAMA_URL') . '/api-lite/2/cfdis',
            $data
        );
        // dd([
        //     'status' => $response->status(),
        //     'body' => $response->body(),
        // ]);

        if (! $response->successful()) {
            return response()->json([
                'error' => 'Error al timbrar',
                'facturama' => $response->body()
            ], 500);
        }

        $result = $response->json(); // ✅
        // dd($result);
        $documento->update([
            'uuid' => $result['Complement']['TaxStamp']['Uuid'],
            'xml' => $result['Xml'],
            'estado' => 'timbrado'
        ]);

        return response()->json([
            'message' => 'Documento timbrado correctamente',
            'uuid' => $documento->uuid
        ]);
    }
    private function buildFacturamaJson( $documento,  $empresa): array
    {
        $documento = Documento::findOrFail($documento);
        // dd($empresa);
        $documento->load([
            'cliente',
            'detalles.producto'
        ]);
        return [
            'Serie' => $documento->serie,
            'Folio' => (string) $documento->folio,
            'Currency' => 'MXN',
            //CP del EMISOR
            'ExpeditionPlace' => $documento->cliente->domicilios->first()->cp,
            'PaymentConditions' => 'CONTADO',
            'PaymentForm' => $documento->forma_pago,
            'PaymentMethod' => $documento->metodo_pago,
            'CfdiType' => 'I',
            'Exportation' => '01',

            // EMISOR
            'Issuer' => [
                'Rfc' => $empresa->rfc,
                'Name' => $empresa->nombre,
                'FiscalRegime' => $empresa->regimen_fiscal,
            ],
            //RECEPTOR
            'Receiver' => [
                'Rfc' => $documento->cliente->rfc,
                'Name' => $documento->cliente->nombre ?? 'PUBLICO EN GENERAL',
                'FiscalRegime' => $documento->cliente->regimen_fiscal,
                'CfdiUse' => $documento->uso_cfdi,
                'TaxZipCode' => $documento->cliente->domicilios->first()->cp,
            ],

            'Items' => $documento->detalles->map(function ($detalle) {
                $subtotal = round($detalle->cantidad * $detalle->costo_unitario, 2);
                $iva = round($subtotal * 0.16, 2);

                return [
                    'ProductCode' => $detalle->producto->clave_sat,
                    'IdentificationNumber' => $detalle->producto->codigo_producto,
                    'Description' => $detalle->producto->nombre,
                    //Unidad de medida
                    'Unit' => 'Pieza',
                    //Clave unidad de medida del sat
                    'UnitCode' => 'H87',
                    'Quantity' => $detalle->cantidad,
                    'UnitPrice' => $detalle->costo_unitario,
                    'Subtotal' => $subtotal,
                    'Taxes' => [
                        [
                            'Total' => $iva,
                            'Name' => 'IVA',
                            'Base' => $subtotal,
                            'Rate' => 0.16,
                            'IsRetention' => false,
                        ]
                    ],
                    'Total' => $subtotal + $iva,
                ];
            })->values()->toArray(),

            'SubTotal' => $documento->subtotal,
            'Total' => $documento->total
        ];
    }

    public function enviarEmail( $sucursal, Request $request,  $documento)
    {
        $sucursal = Sucursal::findOrFail($sucursal);
        $documento = Documento::findOrFail($documento);
        $empresa = ConfiguracionEmpresa::first();

        $documento->load([
            'cliente',
            'detalles.producto'
        ]);

        $request->validate([
            'email' => 'required|email',
        ]);

        Mail::to($request->email)
            ->send(new DocumentoMail($sucursal, $documento,$empresa));
        return redirect()
            ->back()
            ->with('success', '📧 Cotización enviada correctamente');
    }
}
