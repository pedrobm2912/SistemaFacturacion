<?php

use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // productos
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');

    Route::get('/cotizacion', [CotizacionController::class, 'getCotizaciones'])->name('cotizaciones.index');
    Route::get('/cotizacion/proceso', [CotizacionController::class, 'index'])->name('proceso.cotizacion');
    Route::post('/cotizacion/proceso', [CotizacionController::class, 'store'])->name('procesar.cotizacion');
    Route::patch('/cotizacion/{cotizacion_id}', [CotizacionController::class, 'changeStateCotizacion'])->name('changeState.cotizacion');

    Route::get('/facturas', [FacturaController::class, 'getFacturas'])->name('facturas.index');
    Route::get('/facturas/{cotizacion_id}', [FacturaController::class, 'index'])->name('proceso.factura');
    Route::post('/facturas', [FacturaController::class, 'cotiToFact'])->name('cotizacion.factura');
});

require __DIR__.'/auth.php';
