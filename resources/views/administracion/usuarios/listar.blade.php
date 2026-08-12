@extends('layouts.administracion')
@section('titulo', 'Usuarios')
@section('contenido')
    <header class="users-heading">
        <div><h1>USUARIOS</h1><p>Creá usuarios, cambiá sus contraseñas.</p></div>
        <a href="{{ route('administracion.usuarios.crear') }}" class="users-new-button"><span aria-hidden="true">+</span> Nuevo usuario</a>
    </header>

    @if ($errors->has('usuario'))
        <div class="company-form-errors" role="alert"><strong>No se pudo actualizar el usuario</strong><p>{{ $errors->first('usuario') }}</p></div>
    @endif

    <section class="users-list-card">
        <header><div><h2>LISTADO DE USUARIOS</h2><p>{{ $usuarios->total() }} {{ $usuarios->total() === 1 ? 'usuario registrado' : 'usuarios registrados' }}</p></div></header>
        <form method="GET" class="users-filters">
            <div class="users-search"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/></svg><input name="buscar" value="{{ $busqueda }}" placeholder="Buscar por nombre o correo" aria-label="Buscar usuarios"></div>
            <select name="estado" aria-label="Filtrar por estado"><option value="todos">Todos los estados</option><option value="activos" @selected($estado === 'activos')>Activos</option><option value="inactivos" @selected($estado === 'inactivos')>Inactivos</option></select>
            <button>Filtrar</button>
            @if ($busqueda !== '' || $estado !== 'todos')<a href="{{ route('administracion.usuarios.listar') }}">Limpiar</a>@endif
        </form>
        <div class="users-table-wrap">
            <table class="users-table">
                <thead><tr><th>Nombre</th><th>Email</th><th>Estado</th><th class="text-right">Acciones</th></tr></thead>
                <tbody>
                    @forelse ($usuarios as $usuario)
                        <tr>
                            <td><div class="users-name"><span>{{ mb_strtoupper(mb_substr($usuario->nombre, 0, 1)) }}{{ $usuario->apellido ? mb_strtoupper(mb_substr($usuario->apellido, 0, 1)) : '' }}</span><strong>{{ $usuario->nombreCompleto() }}</strong></div></td>
                            <td class="users-email">{{ $usuario->email }}</td>
                            <td><span @class(['users-status', 'is-active' => $usuario->activo, 'is-inactive' => ! $usuario->activo])><i></i>{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</span></td>
                            <td class="text-right"><a href="{{ route('administracion.usuarios.editar', $usuario) }}" class="users-edit-link"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 20 4-.8L19 8.2a2.1 2.1 0 0 0-3-3L4.8 16.4 4 20ZM14.5 6.5l3 3"/></svg>Editar</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="users-empty">No se encontraron usuarios.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($usuarios->hasPages())<footer>{{ $usuarios->links() }}</footer>@endif
    </section>
@endsection
