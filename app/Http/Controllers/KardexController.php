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
    $detalles = $producto->comprasDetalles()
    ->whereHas('compra', function ($query) use ($request) {
        $query->whereBetween('fecha', [
            $request->fecha_inicio,
            $request->fecha_fin
        ]);
    })
    ->with('compra')
    ->get();


     foreach ($detalles as $detalle) {
     echo "Compra: ".$detalle->compra->id."<br>";
     echo "Fecha: ".$detalle->compra->fecha."<br>";
     echo "Cantidad: ".$detalle->cantidad."<br>";
     echo "Costo: ".$detalle->costo_unitario."<br>";
 }
        // dd($detalles);
        // dd($producto);
    }
}
