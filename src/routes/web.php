<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReporteController;

Route::get('/', fn() => redirect()->route('dashboard.index'));

// Dashboard principal
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// CRUD Asesores
Route::resource('asesores', AsesorController::class)
    ->parameters(['asesores' => 'asesor'])
    ->except(['show']);

// CRUD Órdenes
Route::resource('ordenes', OrdenController::class)
    ->parameters(['ordenes' => 'orden']);

// Actualizar checklist
Route::put('ordenes/{orden}/revisiones', [OrdenController::class, 'updateRevisiones'])
    ->name('ordenes.revisiones.update');


Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
Route::post('reportes/filtrar', [ReporteController::class, 'filtrar'])->name('reportes.filtrar');
Route::get('reportes/orden/{id}/pdf', [ReporteController::class, 'exportarPDF'])->name('reportes.pdf');


