<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use App\Models\Timbre;
use App\Models\ConfiguracionEmpresa;
use App\Jobs\DescargarFacturaAPI;
use App\Jobs\EnviarDocumentoMail;
use App\Models\Caja;
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
use App\Models\DatosBancario;
// SERVICIO FACTURA
use App\Services\FacturaApiService;
use Illuminate\Support\Str;
use Carbon\Carbon;



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
            ->when($request->estatus === '1', function ($q) {
                $q->where('estatus', 1);
            })
            ->when($request->estatus === '2', function ($q) {
                $q->where('estatus', 2);
            })
            ->when($request->estatus === '3', function ($q) {
                $q->where('estatus', 3);
            })
            ->when($request->estatus === '4', function ($q) {
                $q->where('estatus', 4);
            })

            ->orderBy('folio', 'desc')
            ->paginate($request->cantidad ?? 15)
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
            ->when($request->estatus === '1', function ($q) {
                $q->where('estatus', 1);
            })
            ->when($request->estatus === '2', function ($q) {
                $q->where('estatus', 2);
            })
            ->when($request->estatus === '3', function ($q) {
                $q->where('estatus', 3);
            })
            ->when($request->estatus === '4', function ($q) {
                $q->where('estatus', 4);
            })
            ->orderBy('folio', 'desc')
            ->paginate($request->cantidad ?? 15)
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
            ->when($request->estatus === '1', function ($q) {
                $q->where('estatus', 1);
            })
            ->when($request->estatus === '2', function ($q) {
                $q->where('estatus', 2);
            })
            ->when($request->estatus === '3', function ($q) {
                $q->where('estatus', 3);
            })
            ->when($request->estatus === '4', function ($q) {
                $q->where('estatus', 4);
            })
            ->orderBy('folio', 'desc')
            ->paginate($request->cantidad ?? 15)
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
        // OBTENER SUCURSAL
        $sucursal = Sucursal::findOrFail($sucursal);
        //OBTENER CAJA
        $caja = Caja::where('user_id', auth()->id())->where('estado', 'abierta')->first();
        // OBTENER PRODUCTOS
        $productos = collect($request->productos)
            ->filter(fn($p) => !empty($p['producto_id']))
            ->values()
            ->toArray();
        $request->merge([
            'productos' => $productos
        ]);

        $request->validate([
            'proveedor_id' => 'required',
            'almacen_id' => 'required',
            'sucursal_id' => 'required',
            'user_id' => 'required',
            'fecha' => 'required|date',
            'subtotal' => 'required|numeric',
            'impuestos' => 'required|numeric',
            'descuentos' => 'required|numeric',
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
            'codigo_postal' => 'required|string|max:6',
        ]);

        DB::beginTransaction();
        // REALIZAR EL DOCUMENTO
        try {
            //BUSCAR SUCURSAL
            $sucursal = Sucursal::lockForUpdate()->findOrFail($request->sucursal_id);
            //BUSCAR CLIENTE
            $cliente = Cliente::findOrFail($request->proveedor_id);

            switch ($request->tipo) {
                case 1:
                    $serie = $sucursal->serie_cotizacion;
                    $folioAsignado = $sucursal->folio_cotizacion; // Usamos el folio actual
                    $sucursal->increment('folio_cotizacion');    // Guarda e incrementa (+1) en un solo paso
                    break;

                case 2:
                    $serie = $sucursal->serie_factura;
                    $folioAsignado = $sucursal->folio_factura;
                    $sucursal->increment('folio_factura');
                    break;

                case 3:
                    $serie = $sucursal->serie_remision;
                    $folioAsignado = $sucursal->folio_remision;
                    $sucursal->increment('folio_remision');
                    break;

                default:
                    $serie = 'XX';
                    $folioAsignado = 1;
                    break;
            }

            // Guardar inmediatamente el nuevo consecutivo
            $sucursal->save();

            $documento = Documento::create([
                'sucursal_id' => $request->sucursal_id,
                'documento_modelo_id' => $request->tipo,
                'serie' => $serie,
                'folio' => $folioAsignado,
                'fecha' => $request->fecha,
                'cliente_id' => $cliente->id,
                'almacen_id' => $request->almacen_id,
                'user_id' => $request->user_id,
                'subtotal' => $request->subtotal,
                'impuestos' => $request->impuestos,
                'descuentos' => $request->descuentos,
                'total' => $request->total,
                'estatus' => 1,
                'metodo_pago' => $request->metodo_pago,
                'forma_pago' => $request->forma_pago,
                'uso_cfdi' => $request->uso_cfdi,
                'vigencia' => $request->vigencia,
                'agente_id' => $request->agente_id,
                'caja_id' => $caja->id ?? null,
                'observaciones' => $request->observaciones,
                'estado' => 'PENDIENTE',
            ]);
            //  Guardas el nuevo folio en sucursal
            // $sucursal->save();

            // ASIGNAR DOMICILIO AL DOCUMENTO
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

            //ASIGNA SALDOS PENDIENTES
            if ($documento->metodo_pago != 'PPD') {
                $documento->update(['saldo_pendiente' => 0]);
            } else {
                $documento->update(['saldo_pendiente' => $request->total]);
                //AUMENTAR EN EL SALDO DEL CLIENTE PENDIENTE
                $cliente->update(['saldo' => $cliente->saldo + $request->total]);
            }
            //ASIGNAR UN CODIGO UNICO PARA LAS REMISIONES
            if ($documento->documento_modelo_id == 3) {
                $codigo_unico = 'REM-' . Str::upper(Str::random(10));
                $documento->update(['codigo_unico' => $codigo_unico]);
            }

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
                    'descuento' => $item['descuento'],
                ]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }


        flash()
            ->option('timeout', 2000)
            ->success(match ($request->tipo) {
                '1' => 'Cotización',
                '2' => 'Factura',
                '3' => 'Remisión',
            } . ' ha sido registrada');

        return redirect()
            ->route('documentos.show', ['sucursal' => $sucursal->id, 'documento' => $documento]);
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
    public function edit($sucursal,  $documento)
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
        try {
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
                if ($request->metodo_pago != 'PPD') {
                    $documento->update(['saldo_pendiente' => 0]);
                } else {
                    $documento->update(['saldo_pendiente' => $request->total]);
                }

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
                            'descuento' => $producto['descuento'],
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
        } catch (\Exception $e) {
            return redirect()->back()->with('error', ($e->getMessage()));
        }
    }
    public function pdf($sucursal,  $documento, FacturaApiService $facturama)
    {
        $documento = Documento::findOrFail($documento);
        $sucursal = Sucursal::findOrFail($sucursal);
        $empresa = ConfiguracionEmpresa::first();

        //CARGAR RELACIONES
        $documento->load([
            'cliente',
            'detalles.producto'
        ]);

        // Seleccionar los datos bancarios
        $banco = DatosBancario::where('predeterminado', true)->first();

        if ($documento->documento_modelo_id == 2 and $documento->estatus == 4) {
            // LEER EL XML
            $xml = $facturama->leerXml($documento->uuid);
            //OBTENER LA INFORMACION NECESARIA
            $datosXML = $facturama->extraerTimbreCfdi($xml);
            //GENERAR LA URL
            $urlQr = $facturama->generarUrl($datosXML, $documento->total);
            // GENERAR QR
            $qr = $facturama->generarQrPng($urlQr);

            $pdf = Pdf::loadView('documentos.pdf_factura', compact('documento', 'sucursal', 'banco', 'empresa', 'datosXML', 'qr'))
                ->setPaper('letter');
        } elseif ($documento->documento_modelo_id == 2  and $documento->estatus == 1) {
            $datosXML = '';
            $qr = '';
            $pdf = Pdf::loadView('documentos.pdf_factura', compact('documento', 'sucursal', 'banco', 'empresa', 'datosXML', 'qr'))
                ->setPaper('letter');
        } else {
            $pdf = Pdf::loadView('documentos.pdf', compact('documento', 'sucursal', 'banco', 'empresa'))
                ->setPaper('letter');
        }
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

        $customPaper = [0, 0, $width, 350];

        $pdf = Pdf::loadView('documentos.pdf_ticket', compact('documento', 'sucursal', 'empresa'))
            ->setPaper($customPaper);

        return $pdf->stream("Ticket{$mm}_{$documento->serie}-{$documento->folio}.pdf");
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($documento)
    {
        try {
            DB::beginTransaction();
            $documento = Documento::findOrFail($documento);
            // Eliminar detalles
            $documento->detalles()->delete();
            // Eliminar documento
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
    public function convertir($sucursal, $documento, $tipo)
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
                'descuentos'           => $documento->descuentos,
                'total'               => $documento->total,
                'estatus'             => 1,
                'metodo_pago'         => $documento->metodo_pago,
                'forma_pago'          => $documento->forma_pago,
                'uso_cfdi'            => $documento->uso_cfdi,
                'saldo_pendiente'            => 0,
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
            if ($documento_convertido->documento_modelo_id == 3) {
                $codigo_unico = 'REM-' . Str::upper(Str::random(10));
                $documento->update(['codigo_unico' => $codigo_unico]);
            }
            // Copiar detalles
            foreach ($documento->detalles as $detalle) {
                $documento_convertido->detalles()->create([
                    'producto_id'   => $detalle->producto_id,
                    'cantidad'      => $detalle->cantidad,
                    'costo_unitario' => $detalle->costo_unitario,
                    'importe'       => $detalle->importe,
                    'descuento'       => $detalle->descuento,
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


    public function surtir($sucursal, $documento)
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
    public function devolucionEdit($sucursal,  $documento)
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
    public function surtirFactura($sucursal,  $documento)
    {
        $sucursal = Sucursal::findOrFail($sucursal);
        $documento = Documento::findOrFail($documento);
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
    }


    public function enviarEmail($sucursal, Request $request,  $documento, FacturaApiService $facturama)
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
        //EJECUTA LA COLA PARA ENVIAR EL CORREO
        EnviarDocumentoMail::dispatch(
            $empresa->id,
            $sucursal->id,
            $documento->id,
            $request->email
        );
        return redirect()
            ->back()
            ->with('success', '📧 Documento enviado correctamente');
    }

    //FUNCION PARA TIMBRAR
    public function timbrar($sucursal, $documento, FacturaApiService $facturama)
    {
        $documento = Documento::with(['cliente', 'detalles.producto'])->findOrFail($documento);
        $empresa = ConfiguracionEmpresa::first();
        // GENERA EL JSON PARA ENVIAR
        $payload = $facturama->buildPayload($documento, $empresa);
        try {
            //REALIZA EL TIMBRADO
            $response = $facturama->crearCfdi($payload);
            $uuid = $response['uuid'] ?? null;
            // OBTENER ID de FACTURA
            $facturaID = $response['id'] ?? null;

            // COLA PARA DESCARGAR XML
            dispatch(new DescargarFacturaAPI($facturaID, $uuid));

            // 4. ACTUALIZAR BD
            $documento->update([
                'facturama_id' => $facturaID,
                'uuid' => $uuid,
                'estatus' => '4',
                'cadena_original' => $response['stamp']['complement_string'] ?? null,
            ]);

            //CONTEO DE  TIMBRES
            $timbre = Timbre::first();
            $timbre->update([
                'utilizados' => $timbre->utilizados + 1
            ]);

            //AFECTAR EXISTENCIA
            $this->surtirFactura($sucursal, $documento->id);

            return redirect()
                ->back()
                ->with('success', '📧 La factura fue timbrada correctamente');
        } catch (\Throwable $e) {
            flash()
                ->option('position', 'top-right')
                ->option('timeout', 5000)
                ->option('direction', 'top')
                ->error($e->getMessage());
            return back();
        }
    }
    //FUNCION PARA CANCELAR FACTURA
    public function cancelar(Request $request, $sucursal, $documento, FacturaApiService $facturaApi)
    {
        $documento = Documento::find($documento);
        $request->validate([
            'motivo' => 'required',
            'uuid_sustitucion' => 'nullable'
        ]);
        try {
            $resultado = $facturaApi->cancelarCfdi(
                $documento->facturama_id,
                $request->motivo,
            );
            $documento->update([
                'estatus' => 3,
                'motivo_cancelacion' => $request->motivo,
                'fecha_cancelacion' => Carbon::parse(
                    $resultado['canceled_at']
                ),
                'uuid_cancelado' => $resultado['uuid'],
                'id_cancelado' => $resultado['id'],
                'cancellation_status' => $resultado['cancellation']['status']
            ]);

            flash()
                ->success('La factura fue cancelada correctamente.');

            return back();
        } catch (\Throwable $e) {

            flash()
                ->error($e->getMessage());

            return back();
        }
    }

    // //CONSTRUIR JSON PARA ENVIAR
    // private function buildPayload($documento, $empresa)
    // {
    //     $receiver = [
    //         "Rfc" => $documento->cliente->rfc,
    //         "Name" => $documento->cliente->nombre,
    //         "CfdiUse" => $documento->uso_cfdi,
    //         "FiscalRegime" => $documento->cliente->regimen_fiscal,
    //         "TaxZipCode" => $documento->cliente->domicilios->first()?->cp,
    //     ];

    //     // VALIDAR QUE SEA PUBLICO EN GENERAL
    //     if ($documento->cliente->rfc === 'XAXX010101000') {
    //         $receiver['Name'] = 'PUBLICO EN GENERAL';
    //         $receiver['CfdiUse'] = 'S01';
    //         $receiver['FiscalRegime'] = '616';
    //         $receiver['TaxZipCode'] = $empresa->cp;
    //     }

    //     $payload = [
    //         "Currency" => "MXN",
    //         "ExpeditionPlace" => $empresa->cp,
    //         "CfdiType" => "I",
    //         "PaymentForm" => $documento->forma_pago,   // 01, 03, etc
    //         "PaymentMethod" => $documento->metodo_pago, // PUE / PPD
    //         "Date"  =>  now()->format('Y-m-d\TH:i:s'),
    //         "Folio" =>  $documento->folio,

    //         "Receiver" => $receiver,

    //         "Items" => $documento->detalles->map(function ($d) {
    //             return [
    //                 "ProductCode" => $d->producto->clave_sat,
    //                 "IdentificationNumber" => $d->producto->codigo_producto,
    //                 "Description" => $d->producto->nombre_producto,
    //                 "Unit" => $d->producto->unidad->descripcion,
    //                 "UnitCode" => $d->producto->unidad->clave,
    //                 "UnitPrice" => $d->costo_unitario,
    //                 "Quantity" => $d->cantidad,
    //                 "Subtotal" => $d->importe,
    //                 "TaxObject" => "02",
    //                 "Taxes" => [
    //                     [
    //                         "Name" => "IVA",
    //                         "Rate" => 0.16,
    //                         "Base" => $d->importe,
    //                         "Total" => round($d->importe * 0.16, 2),
    //                         "IsRetention" => false
    //                     ]
    //                 ],

    //                 "Total" => round($d->importe * 1.16, 2),
    //             ];
    //         })->toArray(),
    //     ];
    //     // SI ES PUBLICO EN GENERAL
    //     if ($documento->cliente->rfc === 'XAXX010101000') {
    //         $payload['GlobalInformation'] = [
    //             "Periodicity" => "04",
    //             "Months" => now()->format('m'),
    //             "Year" => now()->year,
    //         ];
    //     }
    //     return $payload;
    // }
}
