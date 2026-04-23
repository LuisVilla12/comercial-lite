<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasificacionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DomicilioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\ExistenciaProductoController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\TraspasoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PuntosController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\AuditoriaController;

require __DIR__ . '/auth.php';


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/test-email', function () {
//     Mail::to('luisjivl.01@gmail.com')->send(new TestMail());
//     return 'Correo enviado correctamente';
// })->middleware('auth');;

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Solo administrador puede entrar
Route::middleware(['auth', 'admin'])->group(function () {
    //Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/{usuario}', [UserController::class, 'show'])->name('usuarios.show');
    Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    Route::get('/usuarios/{usuario}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');

    //Sucursales
    Route::get('/sucursales', [SucursalController::class, 'index'])->name('sucursales.index');
    Route::get('/sucursales/create', [SucursalController::class, 'create'])->name('sucursales.create');
    Route::post('/sucursales', [SucursalController::class, 'store'])->name('sucursales.store');
    Route::get('/sucursales/{sucursal}/edit', [SucursalController::class, 'edit'])->name('sucursales.edit');
    Route::put('/sucursales/{sucursal}', [SucursalController::class, 'update'])->name('sucursales.update');
    Route::get('/sucursales/{sucursal}', action: [SucursalController::class, 'show'])->name('sucursales.show');

    //Empresas
    Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/create', [EmpresaController::class, 'create'])->name('empresas.create');
    Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::get('/empresas/{empresa}/edit', [EmpresaController::class, 'edit'])->name('empresas.edit');
    Route::put('/empresas/{empresa}/edit', [EmpresaController::class, 'update'])->name('empresas.update');
    Route::get('/empresas/{empresa}', action: [EmpresaController::class, 'show'])->name('empresas.show');

});

Route::middleware('auth')->group(function () {
//Auditorias
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
    Route::get('/auditoria/{id}', [AuditoriaController::class, 'show'])->name('auditoria.show');


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
    // Route::get('/clientes/{cliente}/domicilios/create', [DomicilioController::class, 'create'])->name('domicilios.create');
    // Route::post('/clientes/{cliente}/domicilios', [DomicilioController::class, 'store'])->name('domicilios.store');
    Route::delete('/clientes/{cliente}/domicilios/{domicilio}', [DomicilioController::class, 'destroy'])->name('domicilios.destroy');

    Route::get('/{modeloTipo}/{id}/domicilios/create/', [DomicilioController::class, 'create'])->where('modeloTipo', 'clientes|sucursales|empresas|documentos')->name('domicilios.create');
    Route::post('/{modeloTipo}/{id}/domicilios', [DomicilioController::class, 'store'])->where('modeloTipo', 'clientes|sucursales|empresas|documentos')->name('domicilios.store');
    Route::get('/{modeloTipo}/domicilios/{domicilio}/edit', [DomicilioController::class, 'edit'])->where('modeloTipo', 'cliente|sucursal|empresa|documento')->name('domicilios.edit');
    Route::put('/{modeloTipo} /domicilios/{domicilio}', [DomicilioController::class, 'update'])->name('domicilios.update');


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
    Route::get('/compras', action: [CompraController::class, 'index'])->name('compras.index');
    Route::get('/compras/create', [CompraController::class, 'create'])->name('compras.create');
    Route::post('/compras', [CompraController::class, 'store'])->name('compras.store');
    Route::get('/compras/{compra}', [CompraController::class, 'show'])->name('compras.show');
    Route::get('/compras/{compra}/edit', [CompraController::class, 'edit'])->name('compras.edit');
    Route::put('/compras/{compra}', [CompraController::class, 'update'])->name('compras.update');
    Route::delete('/compras/{compra}', action: [CompraController::class, 'destroy'])->name('compras.destroy');
    Route::post('/compras/{compra}', [CompraController::class, 'surtir'])->name('compras.surtir');

    //Existencias
    Route::get('/inventario', [ExistenciaProductoController::class, 'index'])->name('existencias.index');

    Route::prefix('sucursales/{sucursal}')->middleware('check.sucursal')->group(function () {
        //Documentos
        Route::get('/cotizacion', action: [DocumentoController::class, 'indexCotizacion'])->name('cotizaciones.index');
        Route::get('/facturas', action: [DocumentoController::class, 'indexFacturas'])->name('facturas.index');
        Route::get('/remisiones', action: [DocumentoController::class, 'indexRemisiones'])->name('remisiones.index');
        Route::get('/documentos/create/{tipo}', [DocumentoController::class, 'create'])->name('documentos.create');
        Route::post('/documentos', action: [DocumentoController::class, 'store'])->name('documentos.store');
        Route::get('/documentos/{documento}/edit', [DocumentoController::class, 'edit'])->name(name: 'documentos.edit');
        Route::get('/documentos/{documento}', [DocumentoController::class, 'show'])->name('documentos.show');
        Route::put('/documentos/{documento}', action: [DocumentoController::class, 'update'])->name('documentos.update');
        Route::post('/documentos/{documento}/timbrar', [DocumentoController::class, 'timbrar'])->name(name: 'documentos.timbrar');
        Route::post('/documentos/{documento}/surtir', [DocumentoController::class, 'surtir'])->name(name: 'documentos.surtir');

        Route::get('/devolucion/{documento}', [DocumentoController::class, 'devolucionEdit'])->name(name: 'devolucion.edit');
        Route::put('/devolucion/{documento}', action: [DocumentoController::class, 'devolucionUpdate'])->name(name: 'devolucion.update');
        Route::post('/documentos/{documento}/convertir/{tipo}', action: [DocumentoController::class, 'convertir'])->name('convertir');
        // Devoluciones
        Route::get('/devoluciones', action: [DevolucionController::class, 'index'])->name('devoluciones.index');
        Route::get('/devoluciones/{documento}/show', action: [DevolucionController::class, 'show'])->name('devoluciones.show');
        // PDFs
        Route::get('/documentos/{documento}/ticket/{mm}', [DocumentoController::class, 'pdfTicket'])->name('documentos.pdfTicket');
        Route::get('/documentos/{documento}/pdf', [DocumentoController::class, 'pdf'])->name('documentos.pdf');

    });

    Route::delete('/documentos/{documento}', action: [DocumentoController::class, 'destroy'])->name('documentos.destroy');


    //Envio por correo
    Route::post('/documentos/{documento}/enviar-correo', [DocumentoController::class, 'enviarCorreo'])->name('documentos.enviarCorreo');
    // Route::get('/documentos/{documento}/enviar-correo', [DocumentoController::class, 'enviarCorreo'])->name('documentos.enviarCorreo');

    Route::post('/documentos/{documento}/timbrar', [DocumentoController::class, 'timbrarSAT'])->name('timbrarSAT');


    //Devoluciones
    Route::get('/devoluciones/{documento}', [DevolucionController::class, 'create'])->name('devoluciones.create');
    Route::post('/devoluciones/{documento}', [DevolucionController::class, 'store'])->name('devoluciones.store');

    //Traspasos
    Route::get('/traspasos', action: [TraspasoController::class, 'index'])->name('traspasos.index');
    Route::get('/traspasos/create', action: [TraspasoController::class, 'create'])->name('traspasos.create');
    Route::post('/traspasos', action: [TraspasoController::class, 'store'])->name('traspasos.store');
    Route::get('/traspasos/{traspaso}', [TraspasoController::class, 'show'])->name('traspasos.show');
    Route::get('/traspasos/{traspaso}/edit', [TraspasoController::class, 'edit'])->name('traspasos.edit');
    Route::put('/traspasos/{traspaso}', action: [TraspasoController::class, 'update'])->name('traspasos.update');
    Route::post('/traspasos/{traspaso}/surtir', [TraspasoController::class, 'surtir'])->name('traspasos.surtir');
    Route::delete('/traspasos/{traspaso}', action: [TraspasoController::class, 'destroy'])->name('traspasos.destroy');
    Route::post('/traspasos/{traspaso}', [TraspasoController::class, 'pdf'])->name('traspasos.pdf');

    //Reportes
    Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/conceptos/export', [ReportesController::class, 'exportConceptos'])->name('reportes.conceptos.export');
    Route::get('/reportes/productos/export', [ReportesController::class, 'exportProductos'])->name('reportes.productos.export');

    //Puntos
    Route::get('/puntos', action: [PuntosController::class, 'index'])->name('puntos.index');
});

