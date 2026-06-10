<?php

namespace App\Http\Controllers;

use App\Models\AjustesAlmacen;
use App\Models\Agente;
use App\Models\AjustesAlmacenDetalles;
use App\Models\Almacen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\InventarioService;

class AjustesAlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $tipo)
    {
        $ajustes = AjustesAlmacen::where('tipo', $tipo)
            ->when($request->search, function ($q, $search) {
                $q->where(function ($query) use ($search) {
                    $query->where('id', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('ajustes-almacen.index', compact('ajustes', 'tipo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipo)
    {
        //
        $agentes = Agente::all();
        $almacenes = Almacen::all();
        return view('ajustes-almacen.create', compact('agentes', 'almacenes', 'tipo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // VALIDAR DATOS DEL AJUSTE
        $data = $request->validate([
            'agente_id' => 'required|exists:agentes,id',
            'almacen_id' => 'required|exists:almacens,id',
            'tipo' => 'required',
            'fecha' => 'required|date',
            'observaciones' => 'nullable|string',
        ]);
        //VALIDAR DATOS DE LOS PRODUCTOS
        $productos = collect($request->productos)
            ->filter(fn($p) => !empty($p['producto_id']))
            ->values()
            ->toArray();
        $request->merge([
            'productos' => $productos
        ]);

        DB::beginTransaction();
        try {
            //Registra el ajuste
            $ajuste = AjustesAlmacen::create($data);
            DB::commit();
            //CREAR LOS REGISTROS EN LA TABLA DE DETALLES
            foreach ($request->productos as $item) {
                // Evitar filas vacías (la fila extra de Alpine)
                if (empty($item['producto_id'])) {
                    continue;
                }
                AjustesAlmacenDetalles::create([
                    'ajustes_almacen_id' => $ajuste->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                ]);
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect()->route('ajustes-almacen.show', $ajuste)->with('success', 'Ajusto de almacén creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AjustesAlmacen $ajuste)
    {
        // dd($ajuste);
        $ajuste->load([
            'almacen',
            'detalles.producto',
        ]);
        return view('ajustes-almacen.show', ['ajuste' => $ajuste]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AjustesAlmacen $ajustesAlmacen)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AjustesAlmacen $ajustesAlmacen)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AjustesAlmacen $ajuste)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Eliminar detalles
            $ajuste->detalles()->delete();

            // 2️⃣ Eliminar compra
            $ajuste->delete();

            DB::commit();

            return redirect()
                ->route('ajustes-almacen.index', $ajuste->tipo)
                ->with('success', 'Documento eliminado correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Error al eliminar el documento');
        }
    }

    public function surtir(AjustesAlmacen $ajuste)
    {
        if ($ajuste->estatus != 1) {
            return back()->with('error', 'El ajuste ya fue surtida');
        }

        try {
            //SI ES ENTRADA SUMA
            if ($ajuste->tipo == 1) {
                DB::transaction(function () use ($ajuste) {
                    // Resta a inventario
                    foreach ($ajuste->detalles as $detalle) {
                        InventarioService::sumar(
                            $detalle->producto_id,
                            $ajuste->almacen_id,
                            $detalle->cantidad
                        );
                    }
                });
                // SI ES SALIDA RESTA
            } elseif ($ajuste->tipo == 2) {
                DB::transaction(function () use ($ajuste) {
                    // Resta a inventario
                    foreach ($ajuste->detalles as $detalle) {
                        InventarioService::restar(
                            $detalle->producto_id,
                            $ajuste->almacen_id,
                            $detalle->cantidad
                        );
                    }
                });
            }
            //Documento afectado
            $ajuste->update(['estatus' => 4]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

           return redirect()
            ->route('ajustes-almacen.show', $ajuste)
            ->with('success', 'El ajuste fue surtido correctamente');

    }
}
