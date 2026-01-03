<?php
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DomicilioController;
use App\Http\Controllers\CodigoPostalController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

// RUTAS DE PRODUCTOS
