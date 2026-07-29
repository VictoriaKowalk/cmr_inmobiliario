<?php

use App\Http\Middleware\AgregarCabecerasSeguridad;
use App\Http\Middleware\VerificarUsuarioActivo;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            AgregarCabecerasSeguridad::class,
        ]);

        $middleware->alias([
            'usuario.activo' => VerificarUsuarioActivo::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (
            PostTooLargeException $error,
            Request $request
        ) {
            return back()->withErrors([
                'imagenes' => 'El conjunto de imágenes es demasiado pesado. El máximo total permitido es 200 MB.',
            ]);
        });
    })->create();
