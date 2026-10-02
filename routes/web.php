<?php

use App\Http\Controllers\EstadoSistemaController;
use App\Http\Controllers\Publico\ConsultaController;
use App\Http\Controllers\Publico\TasacionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Sitio público
|--------------------------------------------------------------------------
|
| Esta primera integración conserva el diseño del tema como vistas Blade.
| El catálogo y los formularios se vincularán al CRM en la siguiente etapa.
|
*/
Route::view('/', 'web-publica.temas.inmobiliaria.inicio')->name('publico.inicio');
Route::view('/propiedades', 'web-publica.temas.inmobiliaria.propiedades.listar')
    ->name('publico.propiedades.listar');
Route::view('/propiedades/ejemplo', 'web-publica.temas.inmobiliaria.propiedades.mostrar')
    ->name('publico.propiedades.ejemplo');
Route::view('/nosotros', 'web-publica.temas.inmobiliaria.nosotros')->name('publico.nosotros');

Route::get('/estado', [EstadoSistemaController::class, 'mostrar'])
    ->middleware('throttle:30,1')
    ->name('estado-sistema');

Route::get('/contacto', [ConsultaController::class, 'crearGeneral'])
    ->name('publico.consultas.crear');
Route::view('/contacto/enviado', 'web-publica.temas.inmobiliaria.contacto-enviado')
    ->name('publico.consultas.enviado');
Route::post('/contacto', [ConsultaController::class, 'guardarGeneral'])
    ->middleware('throttle:formularios-publicos')
    ->name('publico.consultas.guardar');

Route::get('/tasacion', [TasacionController::class, 'crear'])
    ->name('publico.tasaciones.crear');
Route::view('/tasacion/enviado', 'web-publica.temas.inmobiliaria.tasaciones-enviado')
    ->name('publico.tasaciones.enviado');
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
