<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\ReporteController;



Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
Route::post('/reportes/filtrar', [ReporteController::class, 'filtrar'])->name('reportes.filtrar');
Route::get('/reportes/pdf/{orden}', [ReporteController::class, 'pdf'])->name('reportes.pdf');






/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// =============================
// 🏠 DASHBOARD
// =============================
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');


// =============================
// 📄 ÓRDENES DE REPARACIÓN
// =============================

// Resource con ajuste de parámetro para evitar "ordene"
Route::resource('ordenes', OrdenController::class)->parameters([
    'ordenes' => 'orden'
]);

// Ruta para actualizar revisiones del checklist
Route::put('/ordenes/{orden}/revisiones', [OrdenController::class, 'updateRevisiones'])
    ->name('ordenes.revisiones.update');


// =============================
// 👤 ASESORES
// =============================
Route::resource('asesores', AsesorController::class);


// =============================
// 📊 REPORTES
// =============================
Route::get('/reportes', [ReporteController::class, 'index'])
    ->name('reportes.index');

// Descargar PDF de una orden
Route::get('/reportes/pdf/{orden}', [ReporteController::class, 'pdf'])
    ->name('reportes.pdf');

    // Evita error si alguien entra a reportes/filtrar por GET
Route::get('/reportes/filtrar', function () {
    return redirect()->route('reportes.index')
        ->with('info', 'La búsqueda debe realizarse desde el formulario de reportes.');
});
