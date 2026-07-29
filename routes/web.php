<?php

use App\Http\Controllers\EstadoSistemaController;
use App\Http\Controllers\Publico\ConsultaController;
use App\Http\Controllers\Publico\TasacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('administracion.dashboard');
});

Route::get('/estado', [EstadoSistemaController::class, 'mostrar'])
    ->middleware('throttle:30,1')
    ->name('estado-sistema');

Route::get('/contacto', [ConsultaController::class, 'crearGeneral'])
    ->name('publico.consultas.crear');
Route::post('/contacto', [ConsultaController::class, 'guardarGeneral'])
    ->middleware('throttle:formularios-publicos')
    ->name('publico.consultas.guardar');

Route::get('/tasacion', [TasacionController::class, 'crear'])
    ->name('publico.tasaciones.crear');
Route::post('/tasacion', [TasacionController::class, 'guardar'])
    ->middleware('throttle:formularios-publicos')
    ->name('publico.tasaciones.guardar');

Route::get('/propiedades/{propiedad:slug}/consulta', [
    ConsultaController::class,
    'crearPropiedad',
])->name('publico.propiedades.consultas.crear');
Route::post('/propiedades/{propiedad:slug}/consulta', [
    ConsultaController::class,
    'guardarPropiedad',
])->middleware('throttle:formularios-publicos')
    ->name('publico.propiedades.consultas.guardar');

require __DIR__.'/administracion.php';
