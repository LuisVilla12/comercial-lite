<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CodigoPostalController;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Obtener codigo postal

// // VER PROVEDORES PARA UNA COMPRA
// Route::get('proveedores/buscar', function (Request $r) {
// $q = $r->input('q', '');
// return Cliente::where('tipo', 3) // proveedor
//     ->where('activo', 1)
//     ->where(function ($query) use ($q) {
//         $query->where('nombre', 'like', "%{$q}%")
//             ->orWhere('codigo', 'like', "%{$q}%");
//     })
//     ->select('id', 'nombre', 'codigo')
//     ->limit(10)
//     ->get();
// });

// //Busqueda de productos para compras
// Route::get('productos/buscar', function () {
//     $q = request('q', '');

//     if (strlen($q) < 2) return [];

//     return Producto::where('estatus_producto', 1)
//         ->where(function ($query) use ($q) {
//             $query->where('clave_producto', 'like', "%{$q}%")
//                 ->orWhere('codigo_producto', 'like', "%{$q}%")
//                 ->orWhere('nombre_producto', 'like', "%{$q}%");
//         })
//         ->select(
//             'id',
//             'nombre_producto as nombre',
//             'codigo_producto as codigo',
//             'clave_producto as clave',
//             'precio1 as costo'
//         )
//         ->limit(10)
//         ->get();
// });

// Route::get('productos-existencias/buscar', function () {
//     $q = request('q');
//     $almacenId = request('almacen');
//     if (!$almacenId) {
//         return [];
//     }
//     return Producto::where('estatus_producto', 1)
//         ->where(function ($query) use ($q) {
//             $query->where('clave_producto', 'like', "%{$q}%")
//                 ->orWhere('codigo_producto', 'like', "%{$q}%")
//                 ->orWhere('nombre_producto', 'like', "%{$q}%");
//         })
//         ->leftJoin('existencia_productos', function ($join) use ($almacenId) {
//             $join->on('productos.id', '=', 'existencia_productos.producto_id')
//                 ->where('existencia_productos.almacen_id', $almacenId);
//         })
//         ->select(
//             'productos.id',
//             'productos.nombre_producto as nombre',
//             'productos.codigo_producto as codigo',
//             'productos.clave_producto as clave',
//             'productos.precio1 as costo',
//             'productos.precio2 as costo2',
//             'productos.precio3 as costo3',
//             'productos.precio4 as costo4',
//             'productos.precio5 as costo5',
//             DB::raw('COALESCE(existencia_productos.cantidad, 0) as stock')
//         )
//         ->limit(10)
//         ->get();
// });


// VENTAS

//Busqueda de productos para ventas


Route::get('/debug/stock', function () {

    $productoId = 4374;
    $almacenId  = 3;

    $cantidad = DB::table('existencia_productos')
        ->where('producto_id', $productoId)
        ->where('almacen_id', $almacenId)
        ->value('cantidad') ?? 0;

    return [
        'producto_id' => $productoId,
        'almacen_id'  => $almacenId,
        'cantidad'       => $cantidad,
    ];
});
