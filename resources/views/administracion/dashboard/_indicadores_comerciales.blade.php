<section class="mb-8">
    <div class="mb-4 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-medium text-emerald-700">Inteligencia comercial</p>
            <h2 class="mt-1 text-xl font-semibold">Indicadores del período</h2>
            <p class="mt-1 text-sm text-neutral-600">{{ $desde->format('d/m/Y') }} al {{ $hasta->format('d/m/Y') }}</p>
        </div>
        <form method="GET" class="flex flex-wrap items-end gap-2">
            <div><label class="mb-1 block text-xs font-medium">Desde</label><input type="date" name="desde" value="{{ $desde->toDateString() }}" class="h-10 border border-neutral-300 bg-white px-3 text-sm"></div>
            <div><label class="mb-1 block text-xs font-medium">Hasta</label><input type="date" name="hasta" value="{{ $hasta->toDateString() }}" class="h-10 border border-neutral-300 bg-white px-3 text-sm"></div>
            <button class="h-10 bg-neutral-900 px-4 text-sm font-semibold text-white">Aplicar</button>
        </form>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
        <article class="border border-neutral-200 bg-white p-5 shadow-sm"><p class="text-sm text-neutral-600">Contactos recibidos</p><p class="mt-2 text-3xl font-semibold">{{ $contactosPeriodo }}</p><p class="mt-2 text-xs text-neutral-500">{{ $consultasPeriodo }} consultas · {{ $tasacionesPeriodo }} tasaciones</p></article>
        <article class="border border-neutral-200 bg-white p-5 shadow-sm"><p class="text-sm text-neutral-600">Primera respuesta</p><p class="mt-2 text-3xl font-semibold">{{ $promedioPrimeraRespuestaMinutos === null ? '—' : ($promedioPrimeraRespuestaMinutos < 60 ? $promedioPrimeraRespuestaMinutos.' min' : number_format($promedioPrimeraRespuestaMinutos / 60, 1, ',', '.').' h') }}</p><p class="mt-2 text-xs text-neutral-500">Promedio hasta marcar como contactada</p></article>
        <article class="border border-neutral-200 bg-white p-5 shadow-sm"><p class="text-sm text-neutral-600">Consulta → visita</p><p class="mt-2 text-3xl font-semibold">{{ number_format($conversionConsultaVisita, 1, ',', '.') }}%</p><p class="mt-2 text-xs text-neutral-500">{{ $visitasPeriodo }} visitas en el período</p></article>
        <article class="border border-neutral-200 bg-white p-5 shadow-sm"><p class="text-sm text-neutral-600">Visita → operación</p><p class="mt-2 text-3xl font-semibold">{{ number_format($conversionVisitaOperacion, 1, ',', '.') }}%</p><p class="mt-2 text-xs text-neutral-500">{{ $operacionesGanadas }} oportunidades ganadas</p></article>
        <article class="border border-neutral-200 bg-white p-5 shadow-sm"><p class="text-sm text-neutral-600">Tiempo de publicación</p><p class="mt-2 text-3xl font-semibold">{{ $promedioPublicacionDias === null ? '—' : number_format($promedioPublicacionDias, 1, ',', '.').' días' }}</p><p class="mt-2 text-xs text-neutral-500">Desde la carga hasta publicar</p></article>
    </div>

    <div class="mt-4 grid gap-4 xl:grid-cols-[1.35fr_1fr]">
        <article class="border border-neutral-200 bg-white p-5 shadow-sm">
            <div class="flex flex-wrap items-start justify-between gap-3">
                <div><h3 class="font-semibold">Actividad comercial</h3><p class="mt-1 text-xs text-neutral-500">Últimos {{ $tendenciaDiaria->count() }} días del período</p></div>
                <div class="flex gap-4 text-xs"><span class="flex items-center gap-1"><span class="size-2 bg-emerald-600"></span>Contactos</span><span class="flex items-center gap-1"><span class="size-2 bg-sky-500"></span>Visitas</span></div>
            </div>
            <div class="mt-5 flex h-52 min-w-0 items-end gap-1 overflow-x-auto border-b border-l border-neutral-200 px-2 pt-4">
                @foreach($tendenciaDiaria as $dia)
                    <div class="group relative flex h-full min-w-4 flex-1 items-end justify-center gap-px" title="{{ $dia['fecha']->format('d/m') }} · {{ $dia['contactos'] }} contactos · {{ $dia['visitas'] }} visitas">
                        <div class="w-1/2 min-w-1 bg-emerald-600 transition hover:bg-emerald-700" style="height: {{ $dia['contactos'] ? max(5, round($dia['contactos'] * 100 / $maximoTendencia)) : 1 }}%"></div>
                        <div class="w-1/2 min-w-1 bg-sky-500 transition hover:bg-sky-600" style="height: {{ $dia['visitas'] ? max(5, round($dia['visitas'] * 100 / $maximoTendencia)) : 1 }}%"></div>
                    </div>
                @endforeach
            </div>
            <div class="mt-2 flex justify-between text-xs text-neutral-500"><span>{{ $tendenciaDiaria->first()['fecha']->format('d/m') }}</span><span>{{ $tendenciaDiaria->last()['fecha']->format('d/m') }}</span></div>
        </article>

        <article class="border border-neutral-200 bg-white p-5 shadow-sm">
            <h3 class="font-semibold">Embudo comercial</h3>
            <p class="mt-1 text-xs text-neutral-500">Avance de los contactos dentro del proceso</p>
            <div class="mt-5 space-y-3">
                @foreach($embudoComercial as $indice => $fila)
                    @php
                        $ancho = $fila['cantidad'] ? max(12, round($fila['cantidad'] * 100 / $maximoEmbudo)) : 4;
                        $coloresEmbudo = ['bg-emerald-800', 'bg-emerald-700', 'bg-emerald-600', 'bg-emerald-500', 'bg-emerald-400'];
                    @endphp
                    <div>
                        <div class="mb-1 flex justify-between text-xs"><span>{{ $fila['etapa'] }}</span><span class="font-semibold">{{ $fila['cantidad'] }}</span></div>
                        <div class="h-7 bg-neutral-100"><div class="h-7 {{ $coloresEmbudo[$indice] }}" style="width: {{ $ancho }}%"></div></div>
                    </div>
                @endforeach
            </div>
        </article>
    </div>

    <div class="mt-4 grid gap-4 xl:grid-cols-2">
        <article class="border border-neutral-200 bg-white p-5 shadow-sm">
            <h3 class="font-semibold">Origen de los contactos</h3>
            @php
                $porcentajeGeneral = $contactosPeriodo ? round($canales['Consulta general'] * 100 / $contactosPeriodo) : 0;
                $porcentajePropiedad = $contactosPeriodo ? round($canales['Consulta por propiedad'] * 100 / $contactosPeriodo) : 0;
            @endphp
            <div class="mt-5 grid items-center gap-6 sm:grid-cols-[180px_1fr]">
                <div class="relative mx-auto size-40 rounded-full" style="background: conic-gradient(#059669 0 {{ $porcentajeGeneral }}%, #0ea5e9 {{ $porcentajeGeneral }}% {{ $porcentajeGeneral + $porcentajePropiedad }}%, #f59e0b {{ $porcentajeGeneral + $porcentajePropiedad }}% 100%)">
                    <div class="absolute inset-7 flex flex-col items-center justify-center rounded-full bg-white"><span class="text-3xl font-semibold">{{ $contactosPeriodo }}</span><span class="text-xs text-neutral-500">contactos</span></div>
                </div>
                <div class="space-y-3">
                    @foreach($canales as $canal => $cantidad)
                        @php
                            $porcentaje = $contactosPeriodo ? round($cantidad * 100 / $contactosPeriodo) : 0;
                            $coloresCanal = ['bg-emerald-600', 'bg-sky-500', 'bg-amber-500'];
                        @endphp
                        <div class="flex items-center justify-between gap-4 text-sm"><span class="flex items-center gap-2"><span class="size-3 {{ $coloresCanal[$loop->index] }}"></span>{{ $canal }}</span><span class="font-semibold">{{ $cantidad }} · {{ $porcentaje }}%</span></div>
                    @endforeach
                </div>
            </div>
        </article>
        <article class="border border-neutral-200 bg-white p-5 shadow-sm">
            <h3 class="font-semibold">Propiedades con mayor demanda</h3>
            <div class="mt-4 divide-y">
                @forelse($demandaPropiedades as $fila)
                    <div class="py-3"><div class="mb-2 flex items-center justify-between gap-4"><div><p class="text-sm font-semibold">{{ $fila['propiedad']?->titulo ?? 'Propiedad eliminada' }}</p><p class="text-xs text-neutral-500">{{ $fila['propiedad']?->codigo_interno }}</p></div><span class="text-lg font-semibold">{{ $fila['consultas'] }}</span></div><div class="h-2 bg-neutral-100"><div class="h-2 bg-violet-500" style="width: {{ round($fila['consultas'] * 100 / $maximoDemanda) }}%"></div></div></div>
                @empty
                    <p class="py-6 text-sm text-neutral-500">No hubo consultas asociadas a propiedades.</p>
                @endforelse
            </div>
        </article>
        <article class="border border-neutral-200 bg-white p-5 shadow-sm">
            <h3 class="font-semibold">Rendimiento por asesor</h3>
            <div class="mt-4 space-y-4">@foreach($rendimientoAsesores as $fila)<div><div class="mb-2 flex justify-between text-sm"><span class="font-medium">{{ $fila['asesor']->nombreCompleto() }}</span><span>{{ $fila['ganadas'] }} ganadas</span></div><div class="space-y-1"><div class="h-2 bg-neutral-100"><div class="h-2 bg-neutral-500" style="width: {{ round($fila['oportunidades'] * 100 / $maximoAsesor) }}%"></div></div><div class="h-2 bg-neutral-100"><div class="h-2 bg-sky-500" style="width: {{ round($fila['visitas'] * 100 / $maximoAsesor) }}%"></div></div><div class="h-2 bg-neutral-100"><div class="h-2 bg-emerald-600" style="width: {{ round($fila['ganadas'] * 100 / $maximoAsesor) }}%"></div></div></div><div class="mt-1 flex gap-3 text-[10px] text-neutral-500"><span>{{ $fila['oportunidades'] }} oportunidades</span><span>{{ $fila['visitas'] }} visitas</span><span>{{ $fila['ganadas'] }} ganadas</span></div></div>@endforeach</div>
        </article>
        <article class="border border-neutral-200 bg-white p-5 shadow-sm">
            <h3 class="font-semibold">Motivos de pérdida</h3>
            <div class="mt-4 divide-y">
                @forelse($motivosPerdida as $motivo => $cantidad)
                    <div class="flex justify-between gap-4 py-3 text-sm"><span>{{ $motivo }}</span><span class="font-semibold">{{ $cantidad }}</span></div>
                @empty
                    <p class="py-6 text-sm text-neutral-500">No se registraron oportunidades perdidas.</p>
                @endforelse
            </div>
        </article>
    </div>
</section>
