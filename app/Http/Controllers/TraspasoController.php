<?php

namespace App\Http\Controllers;

use App\Models\Traspaso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Almacen;
use App\Models\TraspasoDetalle;
use App\Services\InventarioService;
use Barryvdh\DomPDF\Facade\Pdf;

class TraspasoController extends Controller
{
    public function index(Request $request){
    $search = $request->get('search');
        $traspasos = Traspaso::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('serie', 'like', "%{$search}%")
                        ->orWhere('folio', 'like', "%{$search}%");
                });
            })->orderBy('folio', 'desc')
            ->paginate(10)
            ->withQueryString();
        return view('traspasos.index', compact(var_name: 'traspasos'));
    }

    public function create()
    {
        $almacenes=Almacen::all();
        return view('traspasos.create', ['almacenes'=>$almacenes]);
    }
    public function store(Request $request)
    {
        // Validar productos
        $productos = collect($request->productos)
            ->filter(fn($p) => !empty($p['producto_id']))
            ->values()
            ->toArray();
        $request->merge([
            'productos' => $productos
        ]);

        $request->validate([
            'almacen_origen_id'=>'required|exists:almacens,id',
            'almacen_destino_id'=>'required|exists:almacens,id|different:almacen_origen_id',
            'user_id' => 'required|exists:users,id',
            'productos' => 'required|array|min:1',
            'fecha' => 'required|date',
        ]);
        //  dd(vars: $request);
        DB::beginTransaction();
        try{
            $serie = 'TL';
            $ultimoFolio = Traspaso::where('serie', $serie)
                ->lockForUpdate()
                ->max('folio');
            $siguienteFolio = $ultimoFolio ? $ultimoFolio + 1 : 1;

            $trapaso = Traspaso::create([
                'serie' => $serie,
                'folio' => $siguienteFolio,
                'fecha' => $request->fecha,
                'almacen_origen_id' => $request->almacen_origen_id,
                'almacen_destino_id' => $request->almacen_destino_id,
                'user_id' => $request->user_id,
                'estatus' => 1,
            ]);
            DB::commit();
              // CREAR DETALLES DOCUMENTOS
            foreach ($request->productos as $item) {
                // Evitar filas vacías (la fila extra de Alpine)
                if (empty($item['producto_id'])) {
                    continue;
                }
                TraspasoDetalle::create([
                    'traspaso_id' => $trapaso->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    // 'costo_unitario' => $item['costo'],
                    // 'importe' => $item['importe'],
                ]);
            }
        }catch(\Throwable $e){
            DB::rollBack();
            throw $e;
        }
        return redirect()->route('traspasos.index')->with('success', 'Traspaso creado correctamente.');
    }

    public function show(Traspaso $traspaso){
        $traspaso->load([
            'almacenOrigen',
            'almacenDestino',
            'detalles.producto'
        ]);
        return view('traspasos.show', compact('traspaso'));

    }

public function edit(Traspaso $traspaso){
        if ($traspaso->estatus != 1) {
            return redirect()
                ->route('traspasos.show', $traspaso)
                ->with('error', 'El traspaso ya fue surtida');
        }
        $almacenes=Almacen::all();
        $traspaso->load([
            'almacenOrigen',
            'almacenDestino',
            'detalles.producto'
        ]);

        // Calcula el stock
        $traspaso->detalles->each(function ($d) use ($traspaso) {
            $d->stock = $d->producto
                ->existencias()
                ->where('almacen_id', $traspaso->almacen_origen_id)
                ->value('cantidad') ?? 0;
        });

        return view('traspasos.edit', ['traspaso'  => $traspaso,'almacenes' => $almacenes,]);
    }
public function update(Request $request, Traspaso $traspaso)
    {
    // Validar productos
        $productos = collect($request->productos)
            ->filter(fn($p) => !empty($p['producto_id']))
            ->values()
            ->toArray();
        $request->merge([
            'productos' => $productos
        ]);

        $request->validate([
            'almacen_origen_id'=>'required|exists:almacens,id',
            'almacen_destino_id'=>'required|exists:almacens,id|different:almacen_origen_id',
            'user_id' => 'required|exists:users,id',
            'productos' => 'required|array|min:1',
            'fecha' => 'required|date',
        ]);
        try{
            DB::transaction(function () use ($request, $traspaso) {

                /* ================= ACTUALIZAR COMPRA ================= */
                $traspaso->update([
                    'almacen_origen_id' => $request->almacen_origen_id,
                    'almacen_destino_id' => $request->almacen_destino_id,
                ]);

                /* ================= DETALLES ================= */
                $detallesExistentes = $traspaso->detalles()->pluck('id')->toArray();
                $detallesEnFormulario = [];

                foreach ($request->productos as $producto) {

                    $detalle = $traspaso->detalles()->updateOrCreate(
                        [
                            'id' => $producto['detalle_id'] ?? null
                        ],
                        [
                            'producto_id' => $producto['producto_id'],
                            'cantidad' => $producto['cantidad'],
                            // 'costo_unitario' => $producto['costo'],
                            // 'importe' => $producto['cantidad'] * $producto['costo'],
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
                    $traspaso->detalles()->whereIn('id', $detallesParaEliminar)->delete();
                }
            });
        }catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect()
            ->route('traspasos.index')
            ->with('success', "El traspaso a sido actualizada");
    }
    public function destroy(Traspaso $traspaso)
    {
        DB::beginTransaction();
        try {
            $traspaso->detalles()->delete();
            $traspaso->delete();
            DB::commit();

            return redirect()
                ->route('traspasos.index')
                ->with('success', 'Traspaso eliminado correctamente');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Error al eliminar el Traspaso');
        }
    }

     public function surtir(Traspaso $traspaso)
{
    if ($traspaso->estatus != 1) {
        return back()->with('error', 'El traspaso ya fue surtida');
    }

    try {
        //RESTAR al almacen ORIGEN
        DB::transaction(function () use ($traspaso) {
            foreach ($traspaso->detalles as $detalle) {
                InventarioService::restar(
                    $detalle->producto_id,
                    $traspaso->almacen_origen_id,
                    $detalle->cantidad
                );
            }

            $traspaso->update(['estatus' => 2]);
        });
        //SUMAR AL ALMACEN DESTINO
        DB::transaction(function () use ($traspaso) {
            foreach ($traspaso->detalles as $detalle) {
                InventarioService::sumar(
                    $detalle->producto_id,
                    $traspaso->almacen_destino_id,
                    $detalle->cantidad
                );
            }

            $traspaso->update(['estatus' => 2]);
        });
    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }

    return redirect()
        ->route('traspasos.show', $traspaso)
        ->with('success', 'Traspaso surtido correctamente');
}

    public function pdf(Traspaso $traspaso)
    {
      $traspaso->load([
            'almacenOrigen',
            'almacenDestino',
            'detalles.producto'
        ]);

        $pdf = Pdf::loadView('traspasos.pdf', compact('traspaso'))
            ->setPaper('letter');

        return $pdf->stream("Cotizacion_{$traspaso->serie}-{$traspaso->folio}.pdf");
    }

}
