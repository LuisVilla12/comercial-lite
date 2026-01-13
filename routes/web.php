<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasificacionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DomicilioController;
use App\Http\Controllers\CodigoPostalController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\ExistenciaProductoController;
use App\Models\Cliente;
use App\Models\ExistenciaProducto;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Usuarios
Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
require __DIR__.'/auth.php';

//COMPRAS
//Busqueda de proveedores para compras
Route::get('/proveedores/buscar', function (Request $r) {
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

Route::get('/clientes/buscar', function (Request $r) {
    $q = $r->input('q', '');
return Cliente::where('tipo', 1) // proveedor
    ->where('activo', 1)
    ->where(function ($query) use ($q) {
        $query->where('nombre', 'like', "%{$q}%")
    ->orWhere('codigo', 'like', "%{$q}%");
    })
    ->select('id', 'nombre', 'codigo')
    ->limit(10)
    ->get();
});

// RUTAS DE CLIENTES
Route::get('/clientes', [ClienteController::class, 'indexClientes'])->name('clientes.index');
Route::get('/proveedores', action: [ClienteController::class, 'indexProveedores'])->name('proveedores.index');
Route::get('/clientes/create/{tipo}', [ClienteController::class, 'create'])->name('clientes.create');
Route::post('/clientes', action: [ClienteController::class, 'store'])->name('clientes.store');
Route::get('/clientes/{cliente}/{tipo}', [ClienteController::class, 'show'])->name('clientes.show');
Route::get('/clientes/{cliente}/{tipo}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
Route::put('/clientes/{cliente}', [ClienteController::class, 'update'])->name('clientes.update');
Route::delete('/clientes/{cliente}', [ClienteController::class, 'destroy'])->name('clientes.destroy');



// RUTAS DE DOMICILIOS
Route::get('/clientes/{cliente}/domicilios/create', [DomicilioController::class, 'create'])
    ->name('domicilios.create');

Route::post('/clientes/{cliente}/domicilios', [DomicilioController::class, 'store'])
    ->name('domicilios.store');

    Route::get('/clientes/{cliente}/domicilios/{domicilio}/edit', [DomicilioController::class, 'edit'])
    ->name('domicilios.edit');

      Route::put('/clientes/{cliente}/domicilios/{domicilio}', [DomicilioController::class, 'update'])
    ->name('domicilios.update');

    Route::delete('/clientes/{cliente}/domicilios/{domicilio}',
[DomicilioController::class, 'destroy']
)->name('domicilios.destroy');

//Busqueda de productos para compras y ventas
Route::get('/productos/buscar', function () {
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

Route::get('/productos2/buscar', function () {
    $q = request('q');

    return Producto::where('estatus_producto', 1)
        ->where(function ($query) use ($q) {
            $query->where('nombre_producto', 'like', "%{$q}%")
                  ->orWhere('codigo_producto', 'like', "%{$q}%");
        })
        ->withSum(['existencias as stock' => function ($q) {
            $q->where('almacen_id', 1);
        }], 'cantidad')
        ->select(
            'id',
            'nombre_producto as nombre',
            'codigo_producto as codigo',
            'precio1 as costo'
        )
        ->limit(10)
        ->get();
});

Route::get('/debug/stock', function () {

    $productoId = 2758; // un producto que sepas que existe
    $almacenId  = 1;

    $stock = DB::table('existencia_productos')
        ->where('producto_id', $productoId)
        ->where('almacen_id', $almacenId)
        ->sum('cantidad');

    return [
        'producto_id' => $productoId,
        'almacen_id'  => $almacenId,
        'stock'       => $stock,
    ];
});


// RUTAS DE PRODUCTOS
Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
Route::get('/productos/{producto}', [ProductoController::class, 'show'])->name('productos.show');
Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');

//Rutas clasificaciones
Route::get('/clasificaciones', [ClasificacionController::class, 'index'])->name('clasificaciones.index');
Route::get('/clasificaciones/create', [ClasificacionController::class, 'create'])->name('clasificaciones.create');
Route::post('/clasificaciones', [ClasificacionController::class, 'store'])->name('clasificaciones.store');
Route::get('/clasificaciones/{clasificacion}', [ClasificacionController::class, 'show'])->name('clasificaciones.show');
Route::get('/clasificaciones/{clasificacion}/edit', [ClasificacionController::class, 'edit'])->name('clasificaciones.edit');
Route::put('/clasificaciones/{clasificacion}', [ClasificacionController::class, 'update'])->name('clasificaciones.update');
Route::delete('/clasificaciones/{clasificacion}', [ClasificacionController::class, 'destroy'])->name('clasificaciones.destroy');

//Almacenes
Route::get('/almacenes', [AlmacenController::class, 'index'])->name('almacenes.index');
Route::get('/almacenes/create', [AlmacenController::class, 'create'])->name('almacenes.create');
Route::post('/almacenes', [AlmacenController::class, 'store'])->name('almacenes.store');
Route::get('/almacenes/{almacen}', [AlmacenController::class, 'show'])->name('almacenes.show');
Route::get('/almacenes/{almacen}/edit', [AlmacenController::class, 'edit'])->name('almacenes.edit');
Route::put('/almacenes/{almacen}', [AlmacenController::class, 'update'])->name('almacenes.update');
Route::delete('/almacenes/{almacen}', [AlmacenController::class, 'destroy'])->name('almacenes.destroy');

//Compras
Route::get('/compras', [CompraController::class, 'index'])->name('compras.index');
Route::get('/compras/create', [CompraController::class, 'create'])->name('compras.create');
Route::post('/compras', [CompraController::class, 'store'])->name('compras.store');
Route::get('/compras/{compra}', [CompraController::class, 'show'])->name('compras.show');
Route::get('/compras/{compra}/edit', [CompraController::class, 'edit'])->name('compras.edit');
Route::put('/compras/{compra}', [CompraController::class, 'update'])->name('compras.update');
Route::delete('/compras/{compra}', [CompraController::class, 'destroy'])->name('compras.destroy');
Route::post('/compras/{compra}', [CompraController::class, 'surtir'])->name('compras.surtir');

//Existencias
Route::get('/inventario', [ExistenciaProductoController::class, 'index'])->name('existencias.index');

//Cotización
Route::get('/cotizacion/create', [DocumentoController::class, 'create'])->name('cotizacion.create');
