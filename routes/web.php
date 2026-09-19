<?php

use App\Http\Controllers\AlmacenController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\MateriaPrimaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProduccionController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas Web · Sistema Taller Textil
|--------------------------------------------------------------------------
*/

// --- Autenticación ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')->name('logout');

Route::get('/', fn () => redirect()->route('dashboard'));

// --- Área autenticada ---
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- Operaciones ---
    Route::resource('pedidos', PedidoController::class);
    Route::resource('produccion', ProduccionController::class)->except('show')->parameters(['produccion' => 'produccion']);

    // --- Comercial ---
    Route::get('/ventas/{venta}/comprobante', [VentaController::class, 'comprobante'])->name('ventas.comprobante');
    Route::get('/ventas/{venta}/pdf', [VentaController::class, 'pdf'])->name('ventas.pdf');
    Route::get('/compras/export', [CompraController::class, 'export'])->name('compras.export');
    Route::get('/compras/{compra}/comprobante', [CompraController::class, 'comprobante'])->name('compras.comprobante');
    Route::get('/compras/{compra}/pdf', [CompraController::class, 'pdf'])->name('compras.pdf');
    Route::resource('ventas', VentaController::class);
    Route::resource('compras', CompraController::class);

    // --- Datos maestros (CRUD completo) ---
    Route::resource('clientes', ClienteController::class)->except('show');
    Route::resource('proveedores', ProveedorController::class)->except('show');
    Route::resource('productos', ProductoController::class)->except('show');
    Route::resource('materiaprima', MateriaPrimaController::class)->except('show')->parameters(['materiaprima' => 'materiaprima']);
    Route::resource('empleados', EmpleadoController::class)->except('show');
    Route::resource('usuarios', UsuarioController::class)->except('show')->middleware('admin');

    // --- Almacén / Kardex ---
    Route::get('/almacen', [AlmacenController::class, 'index'])->name('almacen.index');
    Route::get('/almacen/nuevo', [AlmacenController::class, 'create'])->name('almacen.create');
    Route::post('/almacen', [AlmacenController::class, 'store'])->name('almacen.store');
    Route::delete('/almacen/{movimiento}', [AlmacenController::class, 'destroy'])->name('almacen.destroy');

    // --- Reportes ---
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/ventas/export', [ReporteController::class, 'exportVentas'])->name('reportes.ventas.export');

    // --- Configuración ---
    Route::get('/configuracion', [ConfiguracionController::class, 'index'])->name('configuracion.index');
    Route::put('/configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');
});
