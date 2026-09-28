<?php

use App\Http\Controllers\Administracion\AutenticacionController;
use App\Http\Controllers\Administracion\CaracteristicaController;
use App\Http\Controllers\Administracion\ConsultaController;
use App\Http\Controllers\Administracion\ContactoController;
use App\Http\Controllers\Administracion\CuentaController;
use App\Http\Controllers\Administracion\DashboardController;
use App\Http\Controllers\Administracion\EmpresaController;
use App\Http\Controllers\Administracion\ImagenPropiedadController;
use App\Http\Controllers\Administracion\MetricasComercialesController;
use App\Http\Controllers\Administracion\PropiedadController;
use App\Http\Controllers\Administracion\TasacionController;
use App\Http\Controllers\Administracion\TipoPropiedadController;
use App\Http\Controllers\Administracion\UbicacionController;
use App\Http\Controllers\Administracion\UsuarioController;
use App\Http\Controllers\Administracion\VideoPropiedadController;
use App\Http\Controllers\Administracion\VisitaController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/administracion/ingreso', [
        AutenticacionController::class,
        'mostrarIngreso',
    ])->name('login');

    Route::post('/administracion/ingreso', [
        AutenticacionController::class,
        'ingresar',
    ])->middleware('throttle:ingreso-administracion')
        ->name('administracion.ingresar');
});

Route::prefix('administracion')
    ->name('administracion.')
    ->middleware(['auth', 'usuario.activo'])
    ->group(function (): void {
        Route::get('/', [DashboardController::class, 'mostrar'])
            ->name('dashboard');

        Route::get('/cuenta', [CuentaController::class, 'editar'])
            ->name('cuenta.editar');
        Route::put('/cuenta/contrasenia', [
            CuentaController::class,
            'actualizarContrasenia',
        ])->middleware('throttle:6,1')
            ->name('cuenta.actualizar-contrasenia');

        Route::get('/empresa', [EmpresaController::class, 'editar'])
            ->middleware('rol:administrador')
            ->name('empresa.editar');
        Route::put('/empresa', [EmpresaController::class, 'actualizar'])
            ->middleware('rol:administrador')
            ->name('empresa.actualizar');

        Route::view('/configuracion', 'administracion.configuracion')
            ->middleware('rol:administrador,supervisor')
            ->name('configuracion');

        Route::prefix('consultas')
            ->name('consultas.')
            ->group(function (): void {
                Route::get('/', [ConsultaController::class, 'listar'])
                    ->name('listar');
                Route::get('/{consulta}', [ConsultaController::class, 'mostrar'])
                    ->name('mostrar');
                Route::patch('/{consulta}/seguimiento', [
                    ConsultaController::class,
                    'actualizarSeguimiento',
                ])->name('actualizar-seguimiento');
            });

        Route::get('/contactos', [ContactoController::class, 'listar'])
            ->name('contactos.listar');
        Route::get('/contactos/metricas', [
            MetricasComercialesController::class,
            'mostrarMetricas',
        ])->middleware('rol:administrador,supervisor')->name('contactos.metricas');

        Route::prefix('visitas')->name('visitas.')->group(function (): void {
            Route::get('/', [VisitaController::class, 'listar'])->name('listar');
            Route::get('/crear', [VisitaController::class, 'crear'])->name('crear');
            Route::post('/', [VisitaController::class, 'guardar'])->name('guardar');
            Route::get('/{visita}', [VisitaController::class, 'mostrar'])->name('mostrar');
            Route::patch('/{visita}/estado', [VisitaController::class, 'cambiarEstado'])
                ->name('cambiar-estado');
            Route::patch('/{visita}/resultado', [VisitaController::class, 'registrarResultado'])
                ->name('registrar-resultado');
        });

        Route::prefix('tasaciones')
            ->name('tasaciones.')
            ->group(function (): void {
                Route::get('/', [TasacionController::class, 'listar'])
                    ->name('listar');
                Route::get('/{tasacion}', [TasacionController::class, 'mostrar'])
                    ->name('mostrar');
                Route::patch('/{tasacion}/seguimiento', [
                    TasacionController::class,
                    'actualizarSeguimiento',
                ])->name('actualizar-seguimiento');
            });

        Route::prefix('propiedades')
            ->name('propiedades.')
            ->group(function (): void {
                Route::get('/', [PropiedadController::class, 'listar'])
                    ->name('listar');
                Route::get('/crear', [PropiedadController::class, 'crear'])
                    ->name('crear');
                Route::post('/', [PropiedadController::class, 'guardar'])
                    ->name('guardar');
                Route::get('/{propiedad}/imprimir', [PropiedadController::class, 'imprimir'])
                    ->name('imprimir');
                Route::get('/{propiedad}/pdf', [PropiedadController::class, 'descargarPdf'])
                    ->name('pdf');
                Route::get('/{propiedad}', [PropiedadController::class, 'mostrar'])
                    ->name('mostrar');
                Route::get('/{propiedad}/editar', [
                    PropiedadController::class,
                    'editar',
                ])->name('editar');
                Route::put('/{propiedad}', [
                    PropiedadController::class,
                    'actualizar',
                ])->name('actualizar');
                Route::patch('/{propiedad}/destacada', [
                    PropiedadController::class,
                    'cambiarDestacada',
                ])->name('cambiar-destacada');
                Route::patch('/{propiedad}/operaciones/{operacion}/estado', [
                    PropiedadController::class,
                    'cambiarEstadoOperacion',
                ])->name('operaciones.cambiar-estado');
                Route::put('/{propiedad}/operaciones/{operacion}', [
                    PropiedadController::class,
                    'actualizarOperacion',
                ])->name('operaciones.actualizar');
                Route::delete('/{propiedad}', [
                    PropiedadController::class,
                    'eliminar',
                ])->middleware('rol:administrador,supervisor')->name('eliminar');
                Route::patch('/{propiedad}/restaurar', [
                    PropiedadController::class,
                    'restaurar',
                ])->middleware('rol:administrador,supervisor')->name('restaurar');
                Route::post('/{propiedad}/imagenes', [
                    ImagenPropiedadController::class,
                    'guardar',
                ])->name('imagenes.guardar');
                Route::put('/{propiedad}/imagenes/orden', [
                    ImagenPropiedadController::class,
                    'ordenar',
                ])->name('imagenes.ordenar');
                Route::patch('/{propiedad}/imagenes/{imagen}/portada', [
                    ImagenPropiedadController::class,
                    'marcarComoPortada',
                ])->name('imagenes.portada');
                Route::delete('/{propiedad}/imagenes/{imagen}', [
                    ImagenPropiedadController::class,
                    'eliminar',
                ])->middleware('rol:administrador,supervisor')->name('imagenes.eliminar');
                Route::post('/{propiedad}/videos', [
                    VideoPropiedadController::class,
                    'guardar',
                ])->name('videos.guardar');
                Route::delete('/{propiedad}/videos/{video}', [
                    VideoPropiedadController::class,
                    'eliminar',
                ])->middleware('rol:administrador,supervisor')->name('videos.eliminar');
            });

        Route::prefix('tipos-propiedad')
            ->name('tipos-propiedad.')
            ->middleware('rol:administrador,supervisor')
            ->group(function (): void {
                Route::get('/', [TipoPropiedadController::class, 'listar'])
                    ->name('listar');
                Route::get('/crear', [TipoPropiedadController::class, 'crear'])
                    ->name('crear');
                Route::post('/', [TipoPropiedadController::class, 'guardar'])
                    ->name('guardar');
                Route::patch('/{tipoPropiedad}/estado', [
                    TipoPropiedadController::class,
                    'cambiarEstado',
                ])->name('cambiar-estado');
            });

        Route::prefix('ubicaciones')
            ->name('ubicaciones.')
            ->middleware('rol:administrador,supervisor')
            ->group(function (): void {
                Route::get('/', [UbicacionController::class, 'listar'])
                    ->name('listar');
                Route::get('/buscar', [UbicacionController::class, 'buscar'])
                    ->name('buscar');
                Route::get('/zonas-de-trabajo', [UbicacionController::class, 'zonasDeTrabajo'])
                    ->name('zonas-de-trabajo');
                Route::post('/zonas-de-trabajo', [UbicacionController::class, 'guardarZonaDeTrabajo'])
                    ->name('zonas-de-trabajo.guardar');
                Route::delete('/zonas-de-trabajo/{ubicacion}', [UbicacionController::class, 'eliminarZonaDeTrabajo'])
                    ->name('zonas-de-trabajo.eliminar');
                Route::get('/crear', [UbicacionController::class, 'crear'])
                    ->name('crear');
                Route::post('/', [UbicacionController::class, 'guardar'])
                    ->name('guardar');
                Route::get('/{ubicacion}/editar', [
                    UbicacionController::class,
                    'editar',
                ])->name('editar');
                Route::put('/{ubicacion}', [
                    UbicacionController::class,
                    'actualizar',
                ])->name('actualizar');
                Route::patch('/{ubicacion}/estado', [
                    UbicacionController::class,
                    'cambiarEstado',
                ])->name('cambiar-estado');
            });

        Route::prefix('caracteristicas')
            ->name('caracteristicas.')
            ->middleware('rol:administrador,supervisor')
            ->group(function (): void {
                Route::get('/', [CaracteristicaController::class, 'listar'])
                    ->name('listar');
                Route::get('/crear', [CaracteristicaController::class, 'crear'])
                    ->name('crear');
                Route::post('/', [CaracteristicaController::class, 'guardar'])
                    ->name('guardar');
                Route::get('/{caracteristica}/editar', [
                    CaracteristicaController::class,
                    'editar',
                ])->name('editar');
                Route::put('/{caracteristica}', [
                    CaracteristicaController::class,
                    'actualizar',
                ])->name('actualizar');
                Route::patch('/{caracteristica}/estado', [
                    CaracteristicaController::class,
                    'cambiarEstado',
                ])->name('cambiar-estado');
            });

        Route::prefix('usuarios')
            ->name('usuarios.')
            ->middleware('rol:administrador')
            ->group(function (): void {
                Route::get('/permisos', [UsuarioController::class, 'mostrarPermisos'])
                    ->name('permisos');
                Route::get('/', [UsuarioController::class, 'listar'])
                    ->name('listar');
                Route::get('/crear', [UsuarioController::class, 'crear'])
                    ->name('crear');
                Route::post('/', [UsuarioController::class, 'guardar'])
                    ->name('guardar');
                Route::get('/{usuario}/editar', [
                    UsuarioController::class,
                    'editar',
                ])->name('editar');
                Route::put('/{usuario}', [
                    UsuarioController::class,
                    'actualizar',
                ])->name('actualizar');
                Route::patch('/{usuario}/estado', [
                    UsuarioController::class,
                    'cambiarEstado',
                ])->name('cambiar-estado');
            });

        Route::post('/salir', [
            AutenticacionController::class,
            'cerrarSesion',
        ])->name('salir');
    });
