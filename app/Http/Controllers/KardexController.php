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

     public function store(Request $request){
        $request->validate([
            'producto_id' => 'required|string',
            'movimiento_id' => 'required|in:1,2,3,4',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date',
        ]); 

        //BUSCAR PRODUCTO
        $producto = Producto::findOrFail($request->producto_id);
        //CARGAR RELACIONES DE SE PRODUCTO
// $detalles = $producto->comprasDetalles()
//                       ->with('compra')
//                       ->get();
    // ENCUENTRA COMPRAS
    $detallesCompras = $producto->comprasDetalles()
    ->whereHas('compra', function ($query) use ($request) {
        $query->whereBetween('fecha', [
            $request->fecha_inicio,
            $request->fecha_fin
        ])
        ->where('estatus', 4);
    })
    ->with('compra')
    ->get();


//      foreach ($detalles as $detalle) {
//      echo "Compra: ".$detalle->traspaso->id."<br>";
//      echo "Fecha: ".$detalle->traspaso->fecha."<br>";
//      echo "Cantidad: ".$detalle->cantidad."<br>";
//      echo "Costo: ".$detalle->costo_unitario."<br>";
//  }
    //ENCUENTRA TRASLADOS
 $detalles = $producto->traspasosDetalles()
    ->whereHas('traspaso', function ($query) use ($request) {
        $query->whereBetween('fecha', [
            $request->fecha_inicio,
            $request->fecha_fin
        ])
        ->where('estatus', 4);
    })
    ->with([
        'traspaso.almacenOrigen',
        'traspaso.almacenDestino',
    ])
    ->get();

//     foreach ($detalles as $detalle) {

//     echo "Fecha: ".$detalle->traspaso->fecha."<br>";

//     echo "Origen: "
//         .$detalle->traspaso->almacenOrigen->nombre."<br>";

//     echo "Destino: "
//         .$detalle->traspaso->almacenDestino->nombre."<br>";

//     echo "Cantidad: ".$detalle->cantidad."<br>";

//     echo "<hr>";
// }

//ENCUENTRA DOCUMENTOS
$detalles = $producto->documentosDetalles()
    ->whereHas('documento', function ($query) use ($request) {
        $query->whereBetween('fecha', [
            $request->fecha_inicio,
            $request->fecha_fin
        ])
        ->where('estatus', 4);
    })
    ->with([
        'documento.cliente',
    ])
    ->get();

    foreach ($detalles as $detalle) {

    echo "Fecha: ".$detalle->documento->fecha."<br>";

    echo "Cliente: "         .$detalle->documento->cliente->nombre."<br>";
    echo "Cantidad: ".$detalle->cantidad."<br>";
   echo "<hr>";
}
        // dd($detalles);
        // dd($producto);
    }
}
