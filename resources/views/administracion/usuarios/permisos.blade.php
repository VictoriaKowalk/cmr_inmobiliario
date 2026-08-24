@extends('layouts.administracion')

@section('titulo', 'Roles y permisos')

@section('contenido')
    <header class="users-heading">
        <div>
            <p class="dashboard-eyebrow">Mi empresa</p>
            <h1>ROLES Y PERMISOS</h1>
            <p>Conocé qué puede hacer cada integrante del equipo dentro del sistema.</p>
        </div>
        <a href="{{ route('administracion.usuarios.listar') }}" class="dashboard-primary-action">Usuarios y equipo <span>→</span></a>
    </header>

    <section class="dashboard-card mb-5">
        <div class="grid gap-4 md:grid-cols-3">
            <article><strong class="text-emerald-200">Administrador</strong><p class="mt-2 text-sm text-slate-400">Control total del sistema, el equipo y la configuración de la inmobiliaria.</p></article>
            <article><strong class="text-sky-200">Supervisor</strong><p class="mt-2 text-sm text-slate-400">Supervisa la operación, los catálogos, las propiedades y el equipo comercial.</p></article>
            <article><strong class="text-violet-200">Asesor</strong><p class="mt-2 text-sm text-slate-400">Trabaja con propiedades y gestiona solamente sus oportunidades y visitas asignadas.</p></article>
        </div>
    </section>

    @php
        $permisos = [
            ['Ver dashboard', true, true, true],
            ['Crear y editar propiedades', true, true, true],
            ['Eliminar y restaurar propiedades', true, true, false],
            ['Gestionar todas las oportunidades', true, true, false],
            ['Gestionar oportunidades asignadas', true, true, true],
            ['Reasignar responsables', true, true, false],
            ['Gestionar todas las visitas', true, true, false],
            ['Gestionar visitas propias', true, true, true],
            ['Ver métricas generales', true, true, false],
            ['Configurar catálogos', true, true, false],
            ['Gestionar usuarios y roles', true, false, false],
            ['Editar datos de la empresa', true, false, false],
        ];
    @endphp

    <section class="dashboard-card overflow-hidden p-0">
        <div class="overflow-x-auto">
            <table class="users-table w-full">
                <thead><tr><th>Funcionalidad</th><th class="text-center">Administrador</th><th class="text-center">Supervisor</th><th class="text-center">Asesor</th></tr></thead>
                <tbody>
                    @foreach ($permisos as [$permiso, $administrador, $supervisor, $asesor])
                        <tr>
                            <td><strong class="text-sm font-semibold leading-relaxed text-slate-100">{{ $permiso }}</strong></td>
                            @foreach ([$administrador, $supervisor, $asesor] as $habilitado)
                                <td class="text-center">
                                    <span class="inline-grid h-7 w-7 place-items-center rounded-full {{ $habilitado ? 'bg-emerald-400/10 text-emerald-200' : 'bg-red-400/10 text-red-300' }}" aria-label="{{ $habilitado ? 'Permitido' : 'No permitido' }}">
                                        @if ($habilitado)
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="m5.5 12.5 4 4 9-9" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true">
                                                <path d="M6.5 6.5 17.5 17.5M17.5 6.5 6.5 17.5" fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="2"/>
                                            </svg>
                                        @endif
                                    </span>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <footer class="border-t border-slate-700 p-4 text-sm text-slate-400">Cada rol tiene permisos definidos para proteger el acceso a la información y a las funciones del sistema.</footer>
    </section>
@endsection
