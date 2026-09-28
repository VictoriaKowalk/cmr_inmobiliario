@extends('layouts.administracion')
@section('titulo', 'Roles y permisos')
@section('contenido')
    <header class="permissions-heading"><div><p>Configuración</p><h1>Roles y permisos</h1><span>Conocé qué puede hacer cada integrante del equipo dentro del sistema.</span></div><a href="{{ route('administracion.usuarios.listar') }}">Volver a usuarios y equipo</a></header>
    <section class="permissions-roles" aria-label="Resumen de roles">
        <article class="is-admin"><i>A</i><div><h2>Administrador</h2><p>Control total del sistema, el equipo y la configuración de la inmobiliaria.</p></div></article>
        <article class="is-supervisor"><i>S</i><div><h2>Supervisor</h2><p>Supervisa la operación, los catálogos, las propiedades y el equipo comercial.</p></div></article>
        <article class="is-advisor"><i>As</i><div><h2>Asesor</h2><p>Trabaja con propiedades y gestiona sus oportunidades y visitas asignadas.</p></div></article>
    </section>
    @php $permisos = [
        ['Ver dashboard', true, true, true], ['Crear y editar propiedades', true, true, true], ['Eliminar y restaurar propiedades', true, true, false], ['Gestionar todas las oportunidades', true, true, false], ['Gestionar oportunidades asignadas', true, true, true], ['Reasignar responsables', true, true, false], ['Gestionar todas las visitas', true, true, false], ['Gestionar visitas propias', true, true, true], ['Ver métricas generales', true, true, false], ['Configurar catálogos', true, true, false], ['Gestionar usuarios y roles', true, false, false], ['Editar datos de la empresa', true, false, false],
    ]; @endphp
    <section class="permissions-card"><header><div><h2>Matriz de accesos</h2><p>Compará qué acciones puede realizar cada rol.</p></div><span><i></i> Permitido <i class="is-denied"></i> No permitido</span></header>
        <div class="permissions-table-wrap"><table class="permissions-table"><thead><tr><th>Funcionalidad</th><th>Administrador</th><th>Supervisor</th><th>Asesor</th></tr></thead><tbody>
            @foreach ($permisos as [$permiso, $administrador, $supervisor, $asesor])<tr><td><strong>{{ $permiso }}</strong></td>@foreach ([$administrador, $supervisor, $asesor] as $habilitado)<td><span @class(['permissions-check', 'is-allowed' => $habilitado, 'is-denied' => ! $habilitado]) aria-label="{{ $habilitado ? 'Permitido' : 'No permitido' }}">{{ $habilitado ? '✓' : '—' }}</span></td>@endforeach</tr>@endforeach
        </tbody></table></div>
        <footer>Los permisos están definidos por rol para proteger el acceso a la información y a las funciones del sistema.</footer>
    </section>
@endsection
