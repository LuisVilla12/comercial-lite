<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CodigoPostalController;
use App\Models\Almacen;
use App\Models\User;
use App\Models\Cliente;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Obtener codigo postal
Route::get('codigos-postales/{cp}', [CodigoPostalController::class, 'buscar']);

//COMPRAS
//Busqueda de proveedores para compras
Route::get('proveedores/buscar', function (Request $r) {
    $q = $r->input('q', '');
    return Cliente::where('tipo', 3) // proveedor
        ->where('activo', 1)
        ->where(function ($query) use ($q) {
            $query->where('nombre', 'like', "%{$q}%")
                ->orWhere('codigo', 'like', "%{$q}%");
        })
        ->select('id', 'nombre', 'codigo')
        ->limit(10)
        ->get();
});
//  Busqueda de clientes para compras
// Route::get('clientes/buscar', function (Request $r) {
//     $q = $r->input('q', '');
//     return Cliente::where('tipo', 1) // proveedor
//         ->where('activo', 1)
//         ->where(function ($query) use ($q) {
//             $query->where('nombre', 'like', "%{$q}%")
//                 ->orWhere('codigo', 'like', "%{$q}%");
//         })
//         ->with('domicilios:id,cliente_id,calle,numero_interior,numero_exterior,cp,ciudad,colonia')
//         ->select('id', 'nombre', 'rfc', 'codigo')
//         ->limit(10)
//         ->get();
// });
Route::get('clientes/buscar', function (Request $r) {
    $q = $r->input('q', '');
    return Cliente::where('tipo', 1) // proveedor
        ->where('activo', 1)
        ->where(function ($query) use ($q) {
            $query->where('nombre', 'like', "%{$q}%")
                  ->orWhere('codigo', 'like', "%{$q}%");
        })
        ->with([
            'domicilios:id,domiciliable_id,domiciliable_type,calle,numero_interior,numero_exterior,cp,ciudad,colonia'
        ])
        ->select('id', 'nombre', 'rfc', 'codigo')
        ->limit(10)
        ->get();
});

//Busqueda de productos para compras
Route::get('productos/buscar', function () {
    $q = request('q', '');

    if (strlen($q) < 2) return [];

    return Producto::where('estatus_producto', 1)
        ->where(function ($query) use ($q) {
            $query->where('nombre_producto', 'like', "%{$q}%")
                ->orWhere('codigo_producto', 'like', "%{$q}%");
        })
        ->select(
            'id',
            'nombre_producto as nombre',
            'codigo_producto as codigo',
            'precio1 as costo'
        )
        ->limit(10)
        ->get();
});
//Busqueda de productos para ventas
Route::get('productos-existencias/buscar', function () {
    $q = request('q');
    $almacenId = request('almacen');
    if (!$almacenId) {
        return [];
    }
    return Producto::where('estatus_producto', 1)
        ->where(function ($query) use ($q) {
            $query->where('nombre_producto', 'like', "%{$q}%")
                ->orWhere('codigo_producto', 'like', "%{$q}%");
        })
        ->leftJoin('existencia_productos', function ($join) use ($almacenId) {
            $join->on('productos.id', '=', 'existencia_productos.producto_id')
                ->where('existencia_productos.almacen_id', $almacenId);
        })
        ->select(
            'productos.id',
            'productos.nombre_producto as nombre',
            'productos.codigo_producto as codigo',
            'productos.precio1 as costo',
            DB::raw('COALESCE(existencia_productos.cantidad, 0) as stock')
        )
        ->limit(10)
        ->get();
});

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
