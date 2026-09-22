<?php

namespace App\Http\Controllers\Administracion;

use App\Enums\RolUsuario;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\GuardarUsuarioRequest;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function listar(Request $solicitud): View
    {
        $busqueda = trim((string) $solicitud->query('buscar'));
        $estado = $solicitud->query('estado', 'todos');

        $usuarios = Usuario::query()
            ->when($busqueda !== '', fn ($consulta) => $consulta
                ->where(fn ($subconsulta) => $subconsulta
                    ->where('nombre', 'like', "%{$busqueda}%")
                    ->orWhere('apellido', 'like', "%{$busqueda}%")
                    ->orWhere('email', 'like', "%{$busqueda}%")))
            ->when($estado === 'activos', fn ($consulta) => $consulta
                ->where('activo', true))
            ->when($estado === 'inactivos', fn ($consulta) => $consulta
                ->where('activo', false))
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->paginate(15)
            ->withQueryString();

        return view('administracion.usuarios.listar', compact(
            'usuarios',
            'busqueda',
            'estado'
        ));
    }

    public function crear(): View
    {
        return view('administracion.usuarios.crear');
    }

    public function guardar(GuardarUsuarioRequest $solicitud): RedirectResponse
    {
        $datos = $solicitud->validated();
        $datos['contrasenia'] = Hash::make($datos['contrasenia']);
        $datos['activo'] = true;
        Usuario::query()->create($datos);

        return redirect()->route('administracion.usuarios.listar')
            ->with('estado', 'El administrador se creó correctamente.');
    }

    public function editar(Usuario $usuario): View
    {
        return view('administracion.usuarios.editar', compact('usuario'));
    }

    public function actualizar(
        GuardarUsuarioRequest $solicitud,
        Usuario $usuario
    ): RedirectResponse {
        $datos = $solicitud->safe()->except([
            'contrasenia',
            'contrasenia_confirmation',
        ]);

        if ($solicitud->filled('contrasenia')) {
            $datos['contrasenia'] = Hash::make(
                $solicitud->string('contrasenia')->toString()
            );
        }

        if ($usuario->esAdministrador()
            && $datos['rol'] !== RolUsuario::ADMINISTRADOR->value
            && Usuario::query()->where('activo', true)->where('rol', RolUsuario::ADMINISTRADOR)->count() <= 1) {
            return back()->withErrors([
                'rol' => 'Debe quedar al menos un administrador activo.',
            ])->withInput();
        }

        $usuario->update($datos);

        return redirect()->route('administracion.usuarios.listar')
            ->with('estado', 'El administrador se actualizó correctamente.');
    }

    public function cambiarEstado(
        Request $solicitud,
        Usuario $usuario
    ): RedirectResponse {
        if ($solicitud->user()->is($usuario)) {
            return back()->withErrors([
                'usuario' => 'No podés desactivar tu propia cuenta.',
            ]);
        }

        if ($usuario->activo && $usuario->esAdministrador()
            && Usuario::query()->where('activo', true)->where('rol', RolUsuario::ADMINISTRADOR)->count() <= 1) {
            return back()->withErrors([
                'usuario' => 'Debe quedar al menos un administrador activo.',
            ]);
        }

        $usuario->update(['activo' => ! $usuario->activo]);

        return back()->with(
            'estado',
            $usuario->activo
                ? 'El usuario quedó activo.'
                : 'El usuario quedó inactivo.'
        );
    }

    public function mostrarPermisos(): View
    {
        return view('administracion.usuarios.permisos');
    }
}
