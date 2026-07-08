<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MedioPagoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClasificacionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\DomicilioController;
use App\Http\Controllers\CodigoPostalController;
use App\Http\Controllers\ConfiguracionEmpresaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MaximoMinimoController;
use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\DocumentoController;
use App\Http\Controllers\SesionesController;
use App\Http\Controllers\ExistenciaProductoController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\TraspasoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\PuntosController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\DatosBancarioController;
use App\Http\Controllers\AgenteController;
use App\Http\Controllers\AjustesAlmacenController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\CertificadoController;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\FacturacionController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\ProductoUbicacionController;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
// MODELOS PARA CONSULTA
use App\Models\Cliente;
use App\Models\Documento;
use App\Models\Producto;
use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/auth.php';


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified', 'tenant'])->name('dashboard');
// listado de empresas
Route::get('/empresas/listado/{user}', [EmpresaController::class, 'listado'])->middleware('auth')->name('empresas.list');
Route::post('/empresas/listado', [EmpresaController::class, 'set'])->middleware('auth')->name('empresas.select');

Route::get('/facturacion-en-linea', [FacturacionController::class, 'create'])->name('facturas.online');
Route::post('/facturacion-en-linea', [FacturacionController::class, 'store'])->name('facturas.online.store');

Route::get('/test-email', function () {
    Mail::to('luisjivl.01@gmail.com')->send(new TestMail());
    return 'Correo enviado correctamente';
})->middleware('auth');;

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

    //Empresas
    Route::get('/empresas', [EmpresaController::class, 'index'])->name('empresas.index');
    Route::get('/empresas/create', [EmpresaController::class, 'create'])->name('empresas.create');
    Route::post('/empresas', [EmpresaController::class, 'store'])->name('empresas.store');
    Route::get('/empresas/{empresa}/edit', [EmpresaController::class, 'edit'])->name('empresas.edit');
    Route::put('/empresas/{empresa}/edit', [EmpresaController::class, 'update'])->name('empresas.update');
    Route::get('/empresas/{empresa}', action: [EmpresaController::class, 'show'])->name('empresas.show');
});

Route::middleware(['auth', 'tenant'])->group(function () {
    //CONFIGURACION DE LA EMPRESA
    Route::get('/configuracion-empresa', action: [ConfiguracionEmpresaController::class, 'show'])->name('configuracion-empresa.show');
    Route::get('/configuracion-empresa/edit', action: [ConfiguracionEmpresaController::class, 'edit'])->name('configuracion-empresa.edit');
    Route::put('/configuracion-empresa/{empresa}/edit', [ConfiguracionEmpresaController::class, 'update'])->name('configuracion-empresa.update');
    Route::get('/empresa/dashboard', action: [ConfiguracionEmpresaController::class, 'dashboard'])->name('configuracion-empresa.dashboard');

    Route::get('/certificados-sat', action: [CertificadoController::class, 'create'])->name('certificados-empresa.create');
    Route::post('/certificados-sat', action: [CertificadoController::class, 'store'])->name('certificados-empresa.store');
    Route::get('/certificados', action: [CertificadoController::class, 'show'])->name('certificados-empresa.show');

    //PAGOS
    Route::get('/pagos', action: [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/create', action: [PagoController::class, 'create'])->name('pagos.create');
    Route::post('/pagos', action: [PagoController::class, 'store'])->name('pagos.store');
    Route::get('/pagos/{documento}', action: [PagoController::class, 'show'])->name('pagos.show');
    Route::get('/pagos/{documento}/edit', action: [PagoController::class, 'edit'])->name('pagos.edit');
    Route::put('/pagos/{documento}', action: [PagoController::class, 'update'])->name('pagos.update');
    Route::delete('/pagos/{documento}', action: [PagoController::class, 'destroy'])->name('pagos.destroy');
    Route::get('/pagos/{documento}/pdf', action: [PagoController::class, 'pdf'])->name('pagos.pdf');

    //GASTOS DE CAJA
    Route::get('/gastos', action: [GastoController::class, 'index'])->name('gastos.index');
    Route::get('/gastos/create', action: [GastoController::class, 'create'])->name('gastos.create');
    Route::post('/gastos/create', action: [GastoController::class, 'store'])->name('gastos.store');
    Route::get('/gastos/{gasto}', action: [GastoController::class, 'show'])->name('gastos.show');
    // Route::delete('/gastos/{gasto}', action: [GastoController::class, 'destroy'])->name('gastos.show');

    // KARDEX MENU
    Route::get('/kardex', action: [KardexController::class, 'index'])->name('kardex.index');
    Route::get('/kardex/global', action: [KardexController::class, 'indexGlobal'])->name('kardexGlobal.index');
    Route::get('/kardex/sucursal', action: [KardexController::class, 'indexSucursal'])->name('kardexSucursal.index');
    //OBTENCION DE KARDEX
    Route::post('/kardex/global', action: [KardexController::class, 'global'])->name('kardex.global');
    Route::post('/kardex/sucursal', action: [KardexController::class, 'sucursal'])->name('kardex.sucursal');
    Route::get('/kardex/pdf', [KardexController::class, 'pdf'])->name('kardex.pdf');

    //METODO DE PAGO
    Route::get('/metodos-pago', action: [MedioPagoController::class, 'index'])->name('metodos.index');
    Route::get('/metodos-pago/create', action: [MedioPagoController::class, 'create'])->name('metodos.create');
    Route::post('/metodos-pago', [MedioPagoController::class, 'store'])->name('metodos.store');
    Route::get('/metodos-pago/{medio}', action: [MedioPagoController::class, 'show'])->name('metodos.show');
    Route::get('/metodos-pago/{medio}/edit', [MedioPagoController::class, 'edit'])->name('metodos.edit');
    Route::put('/metodos-pago/{medio}', [MedioPagoController::class, 'update'])->name('metodos.update');
    Route::delete('/metodos-pago/{medio}', action: [MedioPagoController::class, 'destroy'])->name('metodos.destroy');



    //SESIONES
    Route::get('/sesiones-activas', action: [SesionesController::class, 'index'])->name('sesiones.index');
    Route::delete('/cerrar-sesion/{session}', action: [SesionesController::class, 'destroy'])->name('sesiones.destroy');
    Route::delete('/cerrar-sesion/todas', action: [SesionesController::class, 'destroyAll'])->name('sesiones.destroyAll');
    //Sucursales
    Route::get('/sucursales', [SucursalController::class, 'index'])->name('sucursales.index');
    Route::get('/sucursales/create', [SucursalController::class, 'create'])->name('sucursales.create');
    Route::post('/sucursales', [SucursalController::class, 'store'])->name('sucursales.store');
    Route::get('/sucursales/{sucursal}/edit', [SucursalController::class, 'edit'])->name('sucursales.edit');
    Route::put('/sucursales/{sucursal}', [SucursalController::class, 'update'])->name('sucursales.update');
    Route::get('/sucursales/{sucursal}', action: [SucursalController::class, 'show'])->name('sucursales.show');

    Route::get('/sucursales/{sucursal}/conceptos', action: [SucursalController::class, 'conceptos'])->name('sucursales.conceptos');

    //Auditorias
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
    Route::get('/auditoria/{id}', [AuditoriaController::class, 'show'])->name('auditoria.show');

    //AGENTES
    Route::get('/agentes', [AgenteController::class, 'index'])->name('agentes.index');
    Route::get('/agentes/create', [AgenteController::class, 'create'])->name('agentes.create');
    Route::post('/agentes', [AgenteController::class, 'store'])->name('agentes.store');
    Route::get('/agentes/{agente}', [AgenteController::class, 'show'])->name('agentes.show');
    Route::get('/agentes/{agente}/edit', [AgenteController::class, 'edit'])->name('agentes.edit');
    Route::put('/agentes/{agente}', [AgenteController::class, 'update'])->name('agentes.update');
    Route::delete('/agentes/{agente}', [AgenteController::class, 'destroy'])->name('agentes.destroy');

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

    //MAXIMOS Y MINIMOS
    Route::get('/productos/{producto}/maximos-minimos', [MaximoMinimoController::class, 'create'])->name('maxmin.create');
    Route::post('/productos/maximos-minimos', [MaximoMinimoController::class, 'store'])->name('maxmin.store');
    Route::delete('/productos/{producto}/maximos-minimos/{minimomaximo}', [MaximoMinimoController::class, 'destroy'])->name('maxmin.destroy');
    //Ubicaciones
    Route::get('/productos/{producto}/ubicacion', [ProductoUbicacionController::class, 'create'])->name('productoubicacion.create');
    Route::post('/productos/ubicacion', [ProductoUbicacionController::class, 'store'])->name('productoubicacion.store');
    Route::get('/productos/{producto}/{productoUbicacion}/edit', [ProductoUbicacionController::class, 'edit'])->name('productoubicacion.edit');
    Route::put('/productos/{productoUbicacion}/update', [ProductoUbicacionController::class, 'update'])->name('productoubicacion.update');
    Route::delete('/productos/{producto}/ubicacion/{productoUbicacion}', [ProductoUbicacionController::class, 'destroy'])->name('productoubicacion.destroy');

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

    // DATOS BANCARIOS
    Route::get('/bancos', [DatosBancarioController::class, 'index'])->name('bancos.index');
    Route::get('/bancos/create', [DatosBancarioController::class, 'create'])->name('bancos.create');
    Route::post('/bancos', [DatosBancarioController::class, 'store'])->name('bancos.store');
    Route::get('/bancos/{banco}', [DatosBancarioController::class, 'show'])->name('bancos.show');
    Route::get('/bancos/{banco}/edit', [DatosBancarioController::class, 'edit'])->name('bancos.edit');
    Route::put('/bancos/{banco}', [DatosBancarioController::class, 'update'])->name('bancos.update');
    Route::delete('/bancos/{banco}', [DatosBancarioController::class, 'destroy'])->name('bancos.destroy');
    Route::put('/bancos/{banco}/predeterminado', [DatosBancarioController::class, 'predeterminado'])->name('bancos.predeterminado');

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
    Route::get('/inventario/pdf', [ExistenciaProductoController::class, 'pdf'])->name('existencias.pdf');
    //ALERTAS STOCK
    Route::get('/validacion/inventario', [ExistenciaProductoController::class, 'validacion'])->name('existencias.validacion');
    Route::get('validacion/inventario/pdf', [ExistenciaProductoController::class, 'validacionPdf'])->name('validacion.pdf');

    // TODO:AGREGAR VALIDACION SE SUCURSAL
    // Route::prefix('sucursales/{sucursal}')->middleware('check.sucursal')->group(function () {

    Route::get('/cajas/{caja}/cerrar', [CajaController::class, 'edit'])->name(name: 'cajas.edit');
    Route::put('/cajas/{caja}', action: [CajaController::class, 'update'])->name('cajas.update');
    Route::get('/cajas/{caja}/show', [CajaController::class, 'show'])->name('cajas.show');
    Route::get('/cajas/{caja}/pdf', [CajaController::class, 'pdf'])->name('cajas.pdf');
    Route::get('/cajas', action: [CajaController::class, 'index'])->name('cajas.index');

    Route::prefix('sucursales/{sucursal}')->group(function () {
        Route::get('/cajas/crear', action: [CajaController::class, 'create'])->name('cajas.create');
        Route::post('/cajas/crear', action: [CajaController::class, 'store'])->name('cajas.store');

        //Documentos
        Route::get('/cotizacion', action: [DocumentoController::class, 'indexCotizacion'])->name('cotizaciones.index');
        Route::get('/facturas', action: [DocumentoController::class, 'indexFacturas'])->name('facturas.index');
        Route::get('/remisiones', action: [DocumentoController::class, 'indexRemisiones'])->name('remisiones.index');
        Route::get('/documentos/create/{tipo}', [DocumentoController::class, 'create'])->name('documentos.create');
        Route::post('/documentos', action: [DocumentoController::class, 'store'])->name('documentos.store');
        Route::get('/documentos/{documento}/edit', [DocumentoController::class, 'edit'])->name(name: 'documentos.edit');
        Route::get('/documentos/{documento}', [DocumentoController::class, 'show'])->name('documentos.show');
        Route::put('/documentos/{documento}', action: [DocumentoController::class, 'update'])->name('documentos.update');
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
        //ENVIO
        Route::post('/documentos/{documento}/enviar-email', [DocumentoController::class, 'enviarEmail'])->name('documentos.enviarEmail');

        //TIMBRAR
        Route::post('/documentos/{documento}/timbrar', [DocumentoController::class, 'timbrar'])->name(name: 'documentos.timbrar');
        //CANCELAR
        Route::delete('/documentos/{documento}/cancelar', [DocumentoController::class, 'cancelar'])->name(name: 'documentos.cancelar');
    });

    Route::delete('/documentos/{documento}', action: [DocumentoController::class, 'destroy'])->name('documentos.destroy');


    //Devoluciones
    Route::get('/devoluciones/{documento}', [DevolucionController::class, 'create'])->name('devoluciones.create');
    Route::post('/devoluciones/{documento}', [DevolucionController::class, 'store'])->name('devoluciones.store');


    //AJUSTES DE ALMACEN
    Route::get('/ajustes-almacen/{tipo}', action: [AjustesAlmacenController::class, 'index'])->name('ajustes-almacen.index');
    Route::get('/ajustes-almacen/{tipo}/create', action: [AjustesAlmacenController::class, 'create'])->name('ajustes-almacen.create');
    Route::post('/ajustes-almacen', action: [AjustesAlmacenController::class, 'store'])->name('ajustes-almacen.store');
    Route::get('/ajustes-almacen/detalles/{ajuste}', action: [AjustesAlmacenController::class, 'show'])->name('ajustes-almacen.show');
    Route::post('/ajustes-almacen/detalles/{ajuste}', action: [AjustesAlmacenController::class, 'surtir'])->name('ajustes-almacen.surtir');
    Route::delete('/ajustes-almacen/{ajuste}', action: [AjustesAlmacenController::class, 'destroy'])->name('ajustes-almacen.destroy');
    Route::get('/ajustes-almacen/{ajuste}/pdf', action: [AjustesAlmacenController::class, 'pdf'])->name('ajustes-almacen.pdf');



    //Traspasos
    Route::get('/traspasos', action: [TraspasoController::class, 'index'])->name('traspasos.index');
    Route::get('/traspasos/create', action: [TraspasoController::class, 'create'])->name('traspasos.create');
    Route::post('/traspasos', action: [TraspasoController::class, 'store'])->name('traspasos.store');
    Route::get('/traspasos/{traspaso}', [TraspasoController::class, 'show'])->name('traspasos.show');
    Route::get('/traspasos/{traspaso}/edit', [TraspasoController::class, 'edit'])->name('traspasos.edit');
    Route::put('/traspasos/{traspaso}', action: [TraspasoController::class, 'update'])->name('traspasos.update');
    Route::post('/traspasos/{traspaso}/surtir', [TraspasoController::class, 'surtir'])->name('traspasos.surtir');
    Route::delete('/traspasos/{traspaso}', action: [TraspasoController::class, 'destroy'])->name('traspasos.destroy');
    Route::get('/traspasos/{traspaso}/pdf', [TraspasoController::class, 'pdf'])->name('traspasos.pdf');


    //Reportes
    Route::get('/reportes', [ReportesController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/select', [ReportesController::class, 'select'])->name('reportes.select');
    Route::get('/reportes/create', [ReportesController::class, 'create'])->name('reportes.create');
    Route::get('/reportes/conceptos/export', [ReportesController::class, 'exportConceptos'])->name('reportes.conceptos.export');
    Route::get('/reportes/productos/export', [ReportesController::class, 'exportProductos'])->name('reportes.productos.export');
    Route::get('/reportes/descargar/{archivo}', [ReportesController::class, 'descargar'])->name('reportes.descargar');

    //Puntos
    Route::get('/puntos', action: [PuntosController::class, 'index'])->name('puntos.index');

    //RUTAS PARA CONSUMIR API
    Route::get('codigos-postales/{cp}', [CodigoPostalController::class, 'buscar']);

    // BUSCAR CLIENTES
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


    //BUSCAR PROVEEDORES
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

    Route::get('buscar/productos', function () {
        $q = request('q', '');
        if (strlen($q) < 2) return [];
        return Producto::where('estatus_producto', 1)
            ->where(function ($query) use ($q) {
                $query->where('clave_producto', 'like', "%{$q}%")
                    ->orWhere('codigo_producto', 'like', "%{$q}%")
                    ->orWhere('nombre_producto', 'like', "%{$q}%");
            })
            ->select(
                'id',
                'nombre_producto as nombre',
                'codigo_producto as codigo',
                'clave_producto as clave',
                'precio1 as costo'
            )
            ->limit(10)
            ->get();
    });

    Route::get('buscar/facturas/pendientes', function () {
        $cliente = request('cliente_id');
        return Documento::where('cliente_id', $cliente)
            ->where('metodo_pago', 'PPD')
            ->where('estatus', 1) // Factura timbrada
            ->select(
                'id',
                'fecha',
                'serie',
                'folio',
                'saldo_pendiente',
                'total',
            )
            ->orderBy('fecha')
            ->get();
    });

    Route::get('productos-existencias/buscar', function () {
        $q = request('q');
        $almacenId = request('almacen');
        if (!$almacenId) {
            return [];
        }
        //SABER QUE SUCURSAL ESTA SOLICITANDO EL PRECIO
        // $sucursal = auth()->user()->sucursal ?? 1;
        // $sucursal=Sucursal::findOrFail( $sucursal );
        // $listaPrecio = $sucursal->precio_predeterminado;

        $productos = Producto::where('estatus_producto', 1)
            ->where(function ($query) use ($q) {
                $query->where('clave_producto', 'like', "%{$q}%")
                    ->orWhere('codigo_producto', 'like', "%{$q}%")
                    ->orWhere('nombre_producto', 'like', "%{$q}%");
            })
            ->leftJoin('existencia_productos', function ($join) use ($almacenId) {
                $join->on('productos.id', '=', 'existencia_productos.producto_id')
                    ->where('existencia_productos.almacen_id', $almacenId);
            })
            ->select(
                'productos.id',
                'productos.nombre_producto as nombre',
                'productos.codigo_producto as codigo',
                'productos.clave_producto as clave',
                'productos.precio1 as costo',
                'productos.precio2 as costo2',
                'productos.precio3 as costo3',
                'productos.precio4 as costo4',
                'productos.precio5 as costo5',
                'productos.impuesto1 as iva',
                DB::raw('COALESCE(existencia_productos.cantidad, 0) as stock')
            )
            ->limit(10)
            ->get();

        // DETERMINA EL PRECIO DEFAULT
        // $productos->each(function ($producto) use ($listaPrecio) {
        //      $campo = $listaPrecio == 1
        //          ? 'costo'
        //          : 'costo' . $listaPrecio;
        //      $producto->precio_default = $producto->{$campo};
        //  });

        return $productos;
    });



    //TEST DE AUTENTICACION EN FACTURAMA
    Route::get('/test-facturama', function () {

        $response = Http::withBasicAuth(
            env('FACTURAMA_USER'),
            env('FACTURAMA_PASSWORD')
        )->get(
            env('FACTURAMA_URL') . '/api-lite/Catalogs/CfdiTypes'
        );
        return [
            'status' => $response->status(),
            'headers' => $response->headers(),
            'body' => $response->body(),
        ];
    });

    //FACTURA DE PRUEBA
    Route::get('/factura-prueba', function () {
        $payload = [
            // "Serie" => "R",
            "Currency" => "MXN",
            "ExpeditionPlace" => "91130",

            "CfdiType" => "I",

            "PaymentForm" => "03",   // Transferencia
            "PaymentMethod" => "PUE", // Pago en una sola exhibición

            "Receiver" => [
                "Rfc" => "XAXX010101000",
                "Name" => "PUBLICO EN GENERAL",
                "CfdiUse" => "S01",
                "FiscalRegime" => "616",
                "TaxZipCode" => "91130"
            ],
            "Items" => [
                [
                    "ProductCode" => "01010101",
                    "IdentificationNumber" => "1",
                    "Description" => "Producto prueba",
                    "Unit" => "Pieza",
                    "UnitCode" => "H87",
                    "UnitPrice" => 100,
                    "Quantity" => 1,
                    "Subtotal" => 100,
                    "Total" => 100,
                    "TaxObject" => "01"
                ]
            ],
            "GlobalInformation" => [
                "Periodicity" => "04",
                "Months" => "06",
                "Year" => 2026
            ]
        ];
        //RESPUESTA
        $response = Http::withBasicAuth(
            env('FACTURAMA_USER'),
            env('FACTURAMA_PASSWORD')
        )->post(
            env('FACTURAMA_URL') . '/3/cfdis',
            $payload
        );

        dd(
            $response->status(), //201 es que jalo
            $response['Id'],
            $response['Complement']['TaxStamp']['Uuid'] ?? null,
        );
    });

    // Route::get('/test-xml', function () {

    //     $id = 'JKbUkkTmzFjxuZMrSHF4PA2';

    //     $response = Http::withBasicAuth(
    //         env('FACTURAMA_USER'),
    //         env('FACTURAMA_PASSWORD')
    //     )->get(
    //         env('FACTURAMA_URL') . "/cfdi/xml/issued/{$id}"
    //     );

    //     dd(
    //         $response->status(),
    //         $response->body(),
    //         $response->json()
    //     );
    // });
});
