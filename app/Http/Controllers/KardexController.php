<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Almacen;

use Illuminate\Http\Request;

class KardexController extends Controller
{
    //
    public function index(){
    $productos = Producto::where('id', '>', 11130)->get();
    $almacenes = Almacen::all();
        return view('kardex.index',['productos' => $productos,'almacenes' => $almacenes]);
    }

public function global(Request $request)
{
    $request->validate([
        'producto_id'   => 'required|integer',
        'movimiento_id' => 'required|in:1,2,3,4',
        'fecha_inicio'  => 'required|date',
        'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
    ]);

    $producto = Producto::findOrFail($request->producto_id);

    $detalles = collect();

    switch ($request->movimiento_id) {
        // Compras
        case '1':
            $detalles = $this->obtenerComprasGlobal(
                $producto,
                $request->fecha_inicio,
                $request->fecha_fin
            );
            break;

        // Traspasos
        case '2':
            $detalles = $this->obtenerTraspasosGlobal(
                $producto,
                $request->fecha_inicio,
                $request->fecha_fin
            );
            break;

        // Documentos (ventas)
        case '3':
            $detalles = $this->obtenerDocumentosGlobal(
                $producto,
                $request->fecha_inicio,
                $request->fecha_fin
            );
            break;

        // Kardex global
        case '4':

            $detalles = collect()
                ->merge(
                    $this->obtenerComprasGlobal(
                        $producto,
                        $request->fecha_inicio,
                        $request->fecha_fin
                    )
                )
                ->merge(
                    $this->obtenerTraspasosGlobal(
                        $producto,
                        $request->fecha_inicio,
                        $request->fecha_fin
                    )
                )
                ->merge(
                    $this->obtenerDocumentosGlobal(
                        $producto,
                        $request->fecha_inicio,
                        $request->fecha_fin
                    )
                );

            break;
    }

    $detalles = $detalles
        ->sortBy('fecha')
        ->values();
    $tipo='Global';

    return view('kardex.show', compact(
        'producto',
        'detalles',
        'tipo'
    ));
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
            'descripcion' => $detalle->compra->proveedor->nombre,
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
            'tipo'        => 'Documento',
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

public function sucursal(Request $request) {
    $request->validate([
        'producto_id'   => 'required|integer',
        'almacen_id' => 'required|integer',
        'movimiento_id' => 'required|in:1,2,3,4',
        'fecha_inicio'  => 'required|date',
        'fecha_fin'     => 'required|date|after_or_equal:fecha_inicio',
    ]);
    // dd($request);
        
    $producto = Producto::findOrFail($request->producto_id);
    $detalles = collect();

    switch ($request->movimiento_id) {
        // Compras
        case '1':
            $detalles = $this->obtenerComprasSucursal(
                $producto,
                $request->fecha_inicio,
                $request->fecha_fin,
                $request->almacen_id,
            );
            break;

        // Traspasos
        case '2':
            $detalles = $this->obtenerTraspasosSucursal(
                $producto,
                $request->fecha_inicio,
                $request->fecha_fin,
                $request->almacen_id,
            );
            break;

        // Documentos (ventas)
        case '3':
            $detalles = $this->obtenerDocumentosSucursal(
                $producto,
                $request->fecha_inicio,
                $request->fecha_fin,
                $request->almacen_id,
            );
            break;

        // Kardex global
        case '4':

            $detalles = collect()
                ->merge(
                    $this->obtenerComprasSucursal(
                        $producto,
                        $request->fecha_inicio,
                        $request->fecha_fin,
                        $request->almacen_id,
                    )
                )
                ->merge(
                    $this->obtenerTraspasosSucursal(
                        $producto,
                        $request->fecha_inicio,
                        $request->fecha_fin,
                        $request->almacen_id,
                    )
                )
                ->merge(
                    $this->obtenerDocumentosSucursal(
                        $producto,
                        $request->fecha_inicio,
                        $request->fecha_fin,
                        $request->almacen_id,
                        $request->almacen_id,
                    )
                );

            break;
    }

    $detalles = $detalles
        ->sortBy('fecha')
        ->values();
    $tipo='Sucursal';

    return view('kardex.show', compact(
        'producto',
        'detalles',
        'tipo'
    ));

}
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
            'descripcion' => $detalle->compra->proveedor->nombre,
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
            ->where('estatus', 4);
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
            'tipo'        => 'Documento',
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

}

