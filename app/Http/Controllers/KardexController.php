<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Almacen;
use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class KardexController extends Controller
{
    //
    public function index(){
    // $productos = Producto::where('codigo_producto', '=', 'CE101045LH**')->get();
    $almacenes = Almacen::all();
        return view('kardex.index',['almacenes' => $almacenes]);
    }

    public function indexGlobal(){
    // $productos = Producto::where('codigo_producto', '=', 'CE101045LH**')->get();
    $almacenes = Almacen::all();
        return view('kardex.global',['almacenes' => $almacenes]);
    }

    public function indexSucursal(){
    // $productos = Producto::where('codigo_producto', '=', 'CE101045LH**')->get();
    $almacenes = Almacen::all();
        return view('kardex.sucursal',['almacenes' => $almacenes]);
    }

public function pdf(Request $request)
{
    $producto = Producto::findOrFail($request->producto_id);
    if($request->almacen_id){
    $detalles = $this->generarKardexSucursal(
        $producto,
        $request->movimiento_id,
        $request->fecha_inicio,
        $request->fecha_fin,
        $request->almacen_id
    );
    }else{
    $detalles = $this->generarKardexGlobal(
        $producto,
        $request->movimiento_id,
        $request->fecha_inicio,
        $request->fecha_fin
    );
    }
    $tipo = 'Global';

    $pdf = Pdf::loadView(
        'kardex.pdf',
        compact('producto', 'detalles', 'tipo')
    );

    return $pdf->stream('kardex.pdf');
}

// public function global(Request $request)
// {
//     $request->validate([
//         'producto_id'   => 'required|integer',
//         'movimiento_id' => 'required|in:1,2,3,4,5',
//         'fecha_inicio'  => 'required|date',
//         'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
//     ]);

//     $producto = Producto::findOrFail($request->producto_id);

//     $detalles = collect();

//     switch ($request->movimiento_id) {
//         // Compras
//         case '1':
//             $detalles = $this->obtenerComprasGlobal(
//                 $producto,
//                 $request->fecha_inicio,
//                 $request->fecha_fin
//             );
//             break;


//         // Traspasos
//         case '2':
//             $detalles = $this->obtenerTraspasosGlobal(
//                 $producto,
//                 $request->fecha_inicio,
//                 $request->fecha_fin
//             );
//             break;

//         // Documentos (ventas)
//         case '3':
//             $detalles = $this->obtenerDocumentosGlobal(
//                 $producto,
//                 $request->fecha_inicio,
//                 $request->fecha_fin
//             );
//             break;
//         case '4':
//             $detalles = $this->obtenerAjusteInventarioGlobal(
//                 $producto,
//                 $request->fecha_inicio,
//                 $request->fecha_fin
//             );
//             break;
//         // Kardex global
//         case '5':

//             $detalles = collect()
//                 ->merge(
//                     $this->obtenerComprasGlobal(
//                         $producto,
//                         $request->fecha_inicio,
//                         $request->fecha_fin
//                     )
//                 )
//                 ->merge(
//                     $this->obtenerTraspasosGlobal(
//                         $producto,
//                         $request->fecha_inicio,
//                         $request->fecha_fin
//                     )
//                 )
//                  ->merge(
//                     $this->obtenerAjusteInventarioGlobal(
//                         $producto,
//                         $request->fecha_inicio,
//                         $request->fecha_fin
//                     )
//                 )
//                 ->merge(
//                     $this->obtenerDocumentosGlobal(
//                         $producto,
//                         $request->fecha_inicio,
//                         $request->fecha_fin
//                     )
//                 );

//             break;
//     }

//     $detalles = $detalles
//         ->sortBy('fecha')
//         ->values();
//     $tipo='Global';

//     return view('kardex.show', compact(
//         'producto',
//         'detalles',
//         'tipo'
//     ));
// }

public function global(Request $request)
{
    $request->validate([
        'producto_id'   => 'required|integer',
        'movimiento_id' => 'required|in:1,2,3,4,5',
        'fecha_inicio'  => 'required|date',
        'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
    ]);

    $producto = Producto::findOrFail($request->producto_id);

    $detalles = $this->generarKardexGlobal(
        $producto,
        $request->movimiento_id,
        $request->fecha_inicio,
        $request->fecha_fin
    );

    $tipo = 'Global';

    return view('kardex.show', compact(
        'producto',
        'detalles',
        'tipo'
    ));
}

public function sucursal(Request $request)
{
    // dd($request);
    $request->validate([
        'producto_id'   => 'required|integer',
        'almacen_id' => 'required|integer',
        'movimiento_id' => 'required|in:1,2,3,4,5',
        'fecha_inicio'  => 'required|date',
        'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
    ]);
    $producto = Producto::findOrFail($request->producto_id);

    $detalles = $this->generarKardexSucursal(
        $producto,
        $request->movimiento_id,
        $request->fecha_inicio,
        $request->fecha_fin,
        $request->almacen_id,
    );

    $tipo = 'Sucursal';

    return view('kardex.show', compact(
        'producto',
        'detalles',
        'tipo'
    ));
}

private function generarKardexGlobal(
    Producto $producto,
    $movimientoId,
    $fechaInicio,
    $fechaFin
) {
    $detalles = collect();

    switch ($movimientoId) {
        case '1':
            $detalles = $this->obtenerComprasGlobal($producto, $fechaInicio, $fechaFin);
            break;

        case '2':
            $detalles = $this->obtenerTraspasosGlobal($producto, $fechaInicio, $fechaFin);
            break;

        case '3':
            $detalles = $this->obtenerDocumentosGlobal($producto, $fechaInicio, $fechaFin);
            break;

        case '4':
            $detalles = $this->obtenerAjusteInventarioGlobal($producto, $fechaInicio, $fechaFin);
            break;

        case '5':
            $detalles = collect()
                ->merge($this->obtenerComprasGlobal($producto, $fechaInicio, $fechaFin))
                ->merge($this->obtenerTraspasosGlobal($producto, $fechaInicio, $fechaFin))
                ->merge($this->obtenerAjusteInventarioGlobal($producto, $fechaInicio, $fechaFin))
                ->merge($this->obtenerDocumentosGlobal($producto, $fechaInicio, $fechaFin));
            break;
    }

    return $detalles
        ->sortBy('fecha')
        ->values();
}

private function generarKardexSucursal(
    Producto $producto,
    $movimientoId,
    $fechaInicio,
    $fechaFin,
    $almacen_id,
) {
    $detalles = collect();

    switch ($movimientoId) {
        case '1':
            $detalles = $this->obtenerComprasSucursal($producto, $fechaInicio, $fechaFin,$almacen_id);
            break;

        case '2':
            $detalles = $this->obtenerTraspasosSucursal($producto, $fechaInicio, $fechaFin,$almacen_id);
            break;

        case '3':
            $detalles = $this->obtenerDocumentosSucursal($producto, $fechaInicio, $fechaFin,$almacen_id);
            break;

        case '4':
            $detalles = $this->obtenerAjusteInventarioSucursal($producto, $fechaInicio, $fechaFin,$almacen_id);
            break;

        case '5':
            $detalles = collect()
                ->merge($this->obtenerComprasSucursal($producto, $fechaInicio, $fechaFin,$almacen_id))
                ->merge($this->obtenerTraspasosSucursal($producto, $fechaInicio, $fechaFin,$almacen_id))
                ->merge($this->obtenerDocumentosSucursal($producto, $fechaInicio, $fechaFin,$almacen_id))
                ->merge($this->obtenerAjusteInventarioSucursal($producto, $fechaInicio, $fechaFin,$almacen_id));
            break;
    }

    return $detalles
        ->sortBy('fecha')
        ->values();
}
private function obtenerAjusteInventarioGlobal($producto, $fechaInicio, $fechaFin)
{
    $detalles = collect();
    $ajustes = $producto->AjustesDetalles()
        ->whereHas('ajuste', function ($query) use ($fechaInicio, $fechaFin) {
            $query->whereBetween('fecha', [
                $fechaInicio,
                $fechaFin
            ])
            ->where('estatus', 4);
        })
        ->with('ajuste')
        ->get();
    // dd($ajustes);
    foreach ($ajustes as $detalle) {
        $detalles->push([
            'fecha'       => $detalle->ajuste->fecha,
            'serie'        => $detalle->ajuste->serie,
            'tipo'        => $detalle->ajuste->tipo=='1'?'Entrada Almacen':'Salida Almacen',
            'referencia'  => $detalle->ajuste->id,
            'descripcion' => $detalle->ajuste->almacen->nombre,
            'id'  => $detalle->ajuste->id,
            'movimiento'  => null,
            'entrada'     => $detalle->ajuste->tipo=='1'? $detalle->cantidad:0,
            'salida'      => $detalle->ajuste->tipo=='2'? $detalle->cantidad:0,
        ]);
    }

    return $detalles;
}
private function obtenerComprasGlobal($producto, $fechaInicio, $fechaFin)
{
    $detalles = collect();

    $compras = $producto->comprasDetalles()
        ->whereHas('compra', function ($query) use ($fechaInicio, $fechaFin) {

            $query->whereBetween('fecha', [
                $fechaInicio,
                $fechaFin
            ])
            ->where('estatus', 4);

        })
        ->with('compra')
        ->get();

    foreach ($compras as $detalle) {

        $detalles->push([
            'fecha'       => $detalle->compra->fecha,
            'serie'        => $detalle->compra->serie,
            'tipo'        => 'Compra',
            'referencia'  => $detalle->compra->folio,
            'descripcion' => $detalle->compra->proveedor->nombre ."(" . $detalle->compra->almacen->nombre .")",
            'id'  => $detalle->compra->id,
            'movimiento'  => null,
            'entrada'     => $detalle->cantidad,
            'salida'      => 0,
        ]);
    }

    return $detalles;
}
private function obtenerTraspasosGlobal($producto, $fechaInicio, $fechaFin)
{
    $detalles = collect();

    $traspasos = $producto->traspasosDetalles()
        ->whereHas('traspaso', function ($query) use ($fechaInicio, $fechaFin) {

            $query->whereBetween('fecha', [
                $fechaInicio,
                $fechaFin
            ])
            ->where('estatus', 4);

        })
        ->with([
            'traspaso.almacenOrigen',
            'traspaso.almacenDestino',
        ])
        ->get();

    foreach ($traspasos as $detalle) {

        $detalles->push([
            'fecha'       => $detalle->traspaso->fecha,
            'serie'        => $detalle->traspaso->serie,
            'tipo'        => 'Traspaso',
            'referencia'  => $detalle->traspaso->folio,
            'descripcion' =>
                ($detalle->traspaso->almacenOrigen->nombre ?? '')
                .' → '.
                ($detalle->traspaso->almacenDestino->nombre ?? ''),
            'id'  => $detalle->traspaso->id,
            'movimiento'  => $detalle->cantidad,
            'entrada'     => 0,
            'salida'      => 0, // NO afecta saldo global
        ]);
    }

    return $detalles;
}

private function obtenerDocumentosGlobal($producto, $fechaInicio, $fechaFin)
{
    $detalles = collect();

    $documentos = $producto->documentosDetalles()
        ->whereHas('documento', function ($query) use ($fechaInicio, $fechaFin) {

            $query->whereBetween('fecha', [
                $fechaInicio,
                $fechaFin
            ])
            ->where('estatus', 4);

        })
        ->with('documento.cliente')
        ->get();

    foreach ($documentos as $detalle) {

        $detalles->push([
            'fecha'       => $detalle->documento->fecha,
            'tipo'        => 'Venta',
            'serie'        => $detalle->documento->serie,
            'sucursal'  => $detalle->documento->sucursal_id,
            'referencia'  => $detalle->documento->folio,
            'id'  => $detalle->documento->id,
            'descripcion' => $detalle->documento->cliente->nombre ?? '',
            'movimiento'  => null,
            'entrada'     => 0,
            'salida'      => $detalle->cantidad,
        ]);
    }

    return $detalles;
}

// public function sucursal(Request $request) {
//     $request->validate([
//         'producto_id'   => 'required|integer',
//         'almacen_id' => 'required|integer',
//         'movimiento_id' => 'required|in:1,2,3,4,5',
//         'fecha_inicio'  => 'required|date',
//         'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
//     ]);
//     // dd($request);

//     $producto = Producto::findOrFail($request->producto_id);
//     $detalles = collect();

//     switch ($request->movimiento_id) {
//         // Compras
//         case '1':
//             $detalles = $this->obtenerComprasSucursal(
//                 $producto,
//                 $request->fecha_inicio,
//                 $request->fecha_fin,
//                 $request->almacen_id,
//             );
//             break;

//         // Traspasos
//         case '2':
//             $detalles = $this->obtenerTraspasosSucursal(
//                 $producto,
//                 $request->fecha_inicio,
//                 $request->fecha_fin,
//                 $request->almacen_id,
//             );
//             break;

//         // Documentos (ventas)
//         case '3':
//             $detalles = $this->obtenerDocumentosSucursal(
//                 $producto,
//                 $request->fecha_inicio,
//                 $request->fecha_fin,
//                 $request->almacen_id,
//             );
//             break;
//         case '4':
//             $detalles = $this->obtenerAjusteInventarioSucursal(
//                 $producto,
//                 $request->fecha_inicio,
//                 $request->fecha_fin,
//                 $request->almacen_id,
//             );
//             break;
//         // Kardex global
//         case '5':

//             $detalles = collect()
//                 ->merge(
//                     $this->obtenerComprasSucursal(
//                         $producto,
//                         $request->fecha_inicio,
//                         $request->fecha_fin,
//                         $request->almacen_id,
//                     )
//                 )
//                 ->merge(
//                     $this->obtenerTraspasosSucursal(
//                         $producto,
//                         $request->fecha_inicio,
//                         $request->fecha_fin,
//                         $request->almacen_id,
//                     )
//                 )
//                 ->merge(
//                     $this->obtenerDocumentosSucursal(
//                         $producto,
//                         $request->fecha_inicio,
//                         $request->fecha_fin,
//                         $request->almacen_id,
//                     )
//                 );

//             break;
//     }

//     $detalles = $detalles
//         ->sortBy('fecha')
//         ->values();
//     $tipo='Sucursal';

//     return view('kardex.show', compact(
//         'producto',
//         'detalles',
//         'tipo'
//     ));

// }
private function obtenerComprasSucursal($producto, $fechaInicio, $fechaFin, $almacen_id)
{
    $detalles = collect();

    $compras = $producto->comprasDetalles()
        ->whereHas('compra', function ($query) use ($fechaInicio, $fechaFin,$almacen_id) {

            $query->whereBetween('fecha', [
                $fechaInicio,
                $fechaFin
            ])
            ->where('estatus', 4)
            ->where('almacen_id', $almacen_id);

        })
        ->with('compra')
        ->get();

    foreach ($compras as $detalle) {

        $detalles->push([
            'fecha'       => $detalle->compra->fecha,
            'serie'        => $detalle->compra->serie,
            'tipo'        => 'Compra',
            'id'       => $detalle->compra->id,
            'referencia'  => $detalle->compra->folio,
            'descripcion' => $detalle->compra->proveedor->nombre ."(" . $detalle->compra->almacen->nombre .")",
            'movimiento'  => null,
            'entrada'     => $detalle->cantidad,
            'salida'      => 0,
        ]);
    }

    return $detalles;
}
private function obtenerTraspasosSucursal($producto, $fechaInicio, $fechaFin,$almacen_id){
    $detalles = collect();

    $traspasos = $producto->traspasosDetalles()
        ->whereHas('traspaso', function ($query) use ($fechaInicio, $fechaFin,$almacen_id) {

            $query->whereBetween('fecha', [
                $fechaInicio,
                $fechaFin
            ])
            ->where('estatus', 4)
            ->where(function ($q) use ($almacen_id) {
                $q->where('almacen_origen_id', $almacen_id)
                  ->orWhere('almacen_destino_id', $almacen_id);
            });
        })
        ->with([
            'traspaso.almacenOrigen',
            'traspaso.almacenDestino',
        ])
        ->get();

    foreach ($traspasos as $detalle) {

    $entrada = 0;
    $salida = 0;

    if ($detalle->traspaso->almacen_destino_id == $almacen_id) {
        $entrada = $detalle->cantidad;
    }

    if ($detalle->traspaso->almacen_origen_id == $almacen_id) {
        $salida = $detalle->cantidad;
    }

    $detalles->push([
        'fecha'       => $detalle->traspaso->fecha,
        'tipo'        => 'Traspaso',
        'id'       => $detalle->traspaso->id,
        'serie'        => $detalle->traspaso->serie,
        'referencia'  => $detalle->traspaso->folio,
        'descripcion' =>
            $detalle->traspaso->almacenOrigen->nombre
            .' → '.
            $detalle->traspaso->almacenDestino->nombre,
        'movimiento'  => $detalle->cantidad,
        'entrada'     => $entrada,
        'salida'      => $salida,
    ]);
}

    return $detalles;
}

private function obtenerDocumentosSucursal($producto, $fechaInicio, $fechaFin,$almacen_id)
{
    $detalles = collect();

    $documentos = $producto->documentosDetalles()
        ->whereHas('documento', function ($query) use ($fechaInicio, $fechaFin,$almacen_id) {

            $query->whereBetween('fecha', [
                $fechaInicio,
                $fechaFin
            ])
            ->where('estatus', 4)
            ->where('almacen_id', $almacen_id);

        })
        ->with('documento.cliente')
        ->get();

    foreach ($documentos as $detalle) {

        $detalles->push([
            'fecha'       => $detalle->documento->fecha,
            'serie'        => $detalle->documento->serie,
            'id'       => $detalle->documento->id,
            'tipo'        => 'Venta',
            'referencia'  => $detalle->documento->folio,
            'descripcion' => $detalle->documento->cliente->nombre ?? '',
            'sucursal'  => $detalle->documento->sucursal_id,
            'movimiento'  => null,
            'entrada'     => 0,
            'salida'      => $detalle->cantidad,
        ]);
    }

    return $detalles;
}
private function obtenerAjusteInventarioSucursal($producto, $fechaInicio, $fechaFin,$almacen_id)
{
    $detalles = collect();
    $ajustes = $producto->AjustesDetalles()
        ->whereHas('ajuste', function ($query) use ($fechaInicio, $fechaFin,$almacen_id) {
            $query->whereBetween('fecha', [
                $fechaInicio,
                $fechaFin
            ])
            ->where('estatus', 4)
            ->where('almacen_id', $almacen_id);
        })
        ->with('ajuste')
        ->get();
    // dd($ajustes);
    foreach ($ajustes as $detalle) {
        $detalles->push([
            'fecha'       => $detalle->ajuste->fecha,
            'serie'        => $detalle->ajuste->serie,
            'tipo'        => $detalle->ajuste->tipo=='1'?'Entrada Almacen':'Salida Almacen',
            'referencia'  => $detalle->ajuste->id,
            'descripcion' => $detalle->ajuste->almacen->nombre,
            'id'  => $detalle->ajuste->id,
            'movimiento'  => null,
            'entrada'     => $detalle->ajuste->tipo=='1'? $detalle->cantidad:0,
            'salida'      => $detalle->ajuste->tipo=='2'? $detalle->cantidad:0,
        ]);
    }

    return $detalles;
}
}

