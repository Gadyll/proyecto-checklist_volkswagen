<?php

use Illuminate\Support\Facades\Route;
use App\Models\Asesor;
use App\Models\Orden;

// Obtener asesores
Route::get('/asesores', fn() => Asesor::orderBy('nombre')->get());

// Obtener todas las órdenes con asesor
Route::get('/ordenes', fn() => Orden::with('asesor')->latest()->get());

// Obtener una orden específica
Route::get('/ordenes/{id}', fn($id) => Orden::with('asesor', 'revisiones')->findOrFail($id));

// Datos de estadísticas internas
Route::get('/estadisticas', function () {
    return [
        'total_asesores' => Asesor::count(),
        'total_ordenes' => Orden::count(),
        'ultimas_ordenes' => Orden::latest()->take(5)->get(['numero_orden', 'fecha']),
    ];
});

