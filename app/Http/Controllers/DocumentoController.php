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
    public function index(Request $request)
    {
        $search = $request->get('search');
        $documentos = Documento::when($search, function ($query, $search) {
            $query->where('serie', 'like', "%{$search}%")->orWhere('folio', 'like', "%{$search}%");
        })
            ->paginate(10)
            ->withQueryString();
        return view('cotizacion.index', compact('documentos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Cliente::all();
        $productos = Producto::all();
        $almacenes = Almacen::all();
        return view('cotizacion.create', [
            'proveedores' =>  $proveedores,
            'productos' => $productos,
            'almacenes' => $almacenes,
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
            $serie = 'CT'; // o lo que definas
            $ultimoFolio = Documento::where('serie', $serie)
                ->lockForUpdate()
                ->max('folio');
            $siguienteFolio = $ultimoFolio ? $ultimoFolio + 1 : 1;

            $documento = Documento::create([
                'documento_modelo_id' => 1,
                'serie'        => $serie,
                'folio'        => $siguienteFolio,
                'fecha'        => $request->fecha,
                'cliente_id' => $request->proveedor_id,
                'almacen_id'   => $request->almacen_id,
                'user_id'      => $request->user_id,
                'subtotal'     => $request->subtotal,
                'impuestos' => $request->impuestos,
                'total' => $request->total,
                'estatus'      => 1,
            ]);

            DB::commit();
            // CREAR DETALLES DOCUMENTOS
            foreach ($request->productos as $item) {
                // Evitar filas vacías (la fila extra de Alpine)
                if (empty($item['producto_id'])) {
                    continue;
                }
                DocumentosDetalle::create([
                    'documento_id'   => $documento->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad'    => $item['cantidad'],
                    'costo_unitario' => $item['costo'],
                    'importe'     => $item['importe'],
                ]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        // return redirect()->route('cotizacion.index')
        //     ->with('success', 'Cotización creada correctamente.');
       return redirect()
    ->route('cotizacion.show', $documento)
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
        return view('cotizacion.show', compact('documento'));
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
        //
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
}
