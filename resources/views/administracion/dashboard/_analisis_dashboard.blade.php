@if(($seccion ?? null) !== 'secundario')
<section class="dashboard-analysis-section" aria-labelledby="actividad-comercial-title">
    <div class="dashboard-analysis-section__heading">
        <p class="dashboard-eyebrow">Análisis comercial</p>
        <h2 id="actividad-comercial-title">Actividad comercial y embudo</h2>
        <p>Contactos y visitas dentro del período seleccionado.</p>
    </div>
    <div class="dashboard-analysis-main">
        <article class="dashboard-card">
            <h3>Actividad comercial</h3>
            <div class="dashboard-analysis-bars" aria-label="Actividad diaria de contactos y visitas">
                @foreach($tendenciaDiaria as $dia)
                    <div class="dashboard-analysis-bars__day" title="{{ $dia['fecha']->format('d/m') }}: {{ $dia['contactos'] }} contactos y {{ $dia['visitas'] }} visitas">
                        <i style="height: {{ $dia['contactos'] ? max(5, round($dia['contactos'] * 100 / $maximoTendencia)) : 1 }}%"></i>
                        <b style="height: {{ $dia['visitas'] ? max(5, round($dia['visitas'] * 100 / $maximoTendencia)) : 1 }}%"></b>
                    </div>
                @endforeach
            </div>
            <div class="dashboard-analysis-legend"><span><i></i> Contactos</span><span><b></b> Visitas</span><small>{{ $tendenciaDiaria->first()['fecha']->format('d/m') }} — {{ $tendenciaDiaria->last()['fecha']->format('d/m') }}</small></div>
        </article>
        <article class="dashboard-card">
            <h3>Embudo comercial</h3>
            <div class="dashboard-analysis-funnel">
                @foreach($embudoComercial as $fila)
                    <div><span>{{ $fila['etapa'] }}</span><strong>{{ $fila['cantidad'] }}</strong><i><b style="width: {{ $fila['cantidad'] ? max(8, round($fila['cantidad'] * 100 / $maximoEmbudo)) : 4 }}%"></b></i></div>
                @endforeach
            </div>
        </article>
    </div>
</section>
@endif

@if(($seccion ?? null) !== 'actividad')
<section class="dashboard-analysis-section" aria-labelledby="analisis-secundario-title">
    <div class="dashboard-analysis-section__heading">
        <p class="dashboard-eyebrow">Decisiones</p>
        <h2 id="analisis-secundario-title">Análisis secundario</h2>
        <p>Origen, demanda, rendimiento del equipo y oportunidades perdidas.</p>
    </div>
    <div class="dashboard-analysis-secondary">
        <article class="dashboard-card"><h3>Origen de los contactos</h3><div class="dashboard-analysis-list">@foreach($canales as $canal => $cantidad)<div><span>{{ $canal }}</span><strong>{{ $cantidad }}</strong></div>@endforeach</div></article>
        <article class="dashboard-card"><h3>Propiedades con mayor demanda</h3><div class="dashboard-analysis-list">@forelse($demandaPropiedades as $fila)<div><span>{{ $fila['propiedad']?->titulo ?? 'Propiedad eliminada' }}</span><strong>{{ $fila['consultas'] }}</strong></div>@empty<p class="dashboard-analysis-empty">No hubo consultas asociadas a propiedades.</p>@endforelse</div></article>
        <article class="dashboard-card"><h3>Rendimiento por asesor</h3><div class="dashboard-analysis-list">@foreach($rendimientoAsesores as $fila)<div><span>{{ $fila['asesor']->nombreCompleto() }}</span><strong>{{ $fila['ganadas'] }} ganadas</strong></div>@endforeach</div></article>
        <article class="dashboard-card"><h3>Motivos de pérdida</h3><div class="dashboard-analysis-list">@forelse($motivosPerdida as $motivo => $cantidad)<div><span>{{ $motivo }}</span><strong>{{ $cantidad }}</strong></div>@empty<p class="dashboard-analysis-empty">No se registraron oportunidades perdidas.</p>@endforelse</div></article>
    </div>
</section>
@endif
