<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\ReporteController;


// =============================
// DASHBOARD
// =============================
Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');


// =============================
//  ORDENES
// =============================
Route::resource('ordenes', OrdenController::class)->parameters([
    'ordenes' => 'orden'
]);

// Actualizar checklist
Route::put('/ordenes/{orden}/revisiones', [OrdenController::class, 'updateRevisiones'])
    ->name('ordenes.revisiones.update');


// =============================
//  ASESORES
// =============================

// Resource con corrección del parámetro para evitar “asesore”
Route::resource('asesores', AsesorController::class)->parameters([
    'asesores' => 'asesor'
]);

// Nueva ruta para desempeño del asesor
Route::get('/asesores/{asesor}/desempeno', [AsesorController::class, 'desempeno'])
    ->name('asesores.desempeno');


// ============================= REPORTES
// =============================

Route::get('/reportes', [ReporteController::class, 'index'])
    ->name('reportes.index');

Route::post('/reportes/filtrar', [ReporteController::class, 'filtrar'])
    ->name('reportes.filtrar');

// Manejar error si entran por GET a filtrar
Route::get('/reportes/filtrar', function () {
    return redirect()->route('reportes.index')
        ->with('info', 'La búsqueda debe realizarse desde el formulario de reportes.');
});

Route::get('/reportes/pdf/{orden}', [ReporteController::class, 'pdf'])
    ->name('reportes.pdf');


