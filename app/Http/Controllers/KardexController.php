<?php

namespace App\Http\Controllers;
use App\Models\Producto;

use Illuminate\Http\Request;

class KardexController extends Controller
{
    //
    public function index(){
    $productos = Producto::where('id', '>', 11130)->get();
        return view('kardex.index',['productos' => $productos]);
    }

public function store(Request $request)
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
            $detalles = $this->obtenerCompras(
                $producto,
                $request->fecha_inicio,
                $request->fecha_fin
            );
            break;

        // Traspasos
        case '2':
            $detalles = $this->obtenerTraspasos(
                $producto,
                $request->fecha_inicio,
                $request->fecha_fin
            );
            break;

        // Documentos (ventas)
        case '3':
            $detalles = $this->obtenerDocumentos(
                $producto,
                $request->fecha_inicio,
                $request->fecha_fin
            );
            break;

        // Kardex global
        case '4':

            $detalles = collect()
                ->merge(
                    $this->obtenerCompras(
                        $producto,
                        $request->fecha_inicio,
                        $request->fecha_fin
                    )
                )
                ->merge(
                    $this->obtenerTraspasos(
                        $producto,
                        $request->fecha_inicio,
                        $request->fecha_fin
                    )
                )
                ->merge(
                    $this->obtenerDocumentos(
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

    return view('kardex.show', compact(
        'producto',
        'detalles'
    ));
}

private function obtenerCompras($producto, $fechaInicio, $fechaFin)
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
            'tipo'        => 'COMPRA',
            'referencia'  => $detalle->compra->id,
            'descripcion' => 'Compra',
            'movimiento'  => null,
            'entrada'     => $detalle->cantidad,
            'salida'      => 0,
        ]);
    }

    return $detalles;
}
private function obtenerTraspasos($producto, $fechaInicio, $fechaFin)
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
            'tipo'        => 'TRASPASO',
            'referencia'  => $detalle->traspaso->id,
            'descripcion' =>
                ($detalle->traspaso->almacenOrigen->nombre ?? '')
                .' → '.
                ($detalle->traspaso->almacenDestino->nombre ?? ''),
            'movimiento'  => $detalle->cantidad,
            'entrada'     => 0,
            'salida'      => 0, // NO afecta saldo global
        ]);
    }

    return $detalles;
}

private function obtenerDocumentos($producto, $fechaInicio, $fechaFin)
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
            'tipo'        => 'DOCUMENTO',
            'referencia'  => $detalle->documento->id,
            'descripcion' => $detalle->documento->cliente->nombre ?? '',
            'movimiento'  => null,
            'entrada'     => 0,
            'salida'      => $detalle->cantidad,
        ]);
    }

    return $detalles;
}
}
