<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ficha {{ $propiedad->codigo_interno }} - {{ $propiedad->titulo }}</title>
    <style>
        * { box-sizing: border-box; }
        @page { margin: 18mm 16mm; }
        body { margin: 0; color: #172b3a; font-family: Arial, sans-serif; font-size: 10pt; line-height: 1.45; }
        .header { display: table; width: 100%; padding-bottom: 14px; border-bottom: 2px solid #08bec4; }
        .brand, .code { display: table-cell; vertical-align: middle; }
        .brand { color: #00aeb9; font-size: 20pt; font-weight: 800; letter-spacing: .06em; }
        .code { color: #66788a; font-size: 9pt; text-align: right; }
        h1 { margin: 18px 0 4px; font-size: 23pt; line-height: 1.14; }
        .address { margin: 0; color: #425969; font-size: 12pt; font-weight: bold; }
        .location { margin: 2px 0 0; color: #718594; }
        .summary { margin: 14px 0 18px; color: #425969; font-weight: bold; }
        .summary span { display: inline-block; margin-right: 11px; }
        .summary span:not(:last-child)::after { content: '·'; margin-left: 11px; color: #a2b2bd; }
        .hero { width: 100%; max-height: 280px; margin: 0 0 18px; object-fit: cover; }
        .section { margin-top: 18px; padding-top: 13px; border-top: 1px solid #dbe6ee; page-break-inside: avoid; }
        h2 { margin: 0 0 10px; font-size: 13pt; }
        h3 { margin: 0 0 7px; color: #425969; font-size: 10pt; }
        .grid { display: table; width: 100%; table-layout: fixed; border-collapse: collapse; }
        .col { display: table-cell; width: 33.33%; padding-right: 14px; vertical-align: top; }
        .label { color: #718594; }
        .value { font-weight: bold; }
        .row { margin: 0 0 6px; }
        .description { white-space: pre-line; }
        .list { margin: 0; padding-left: 18px; columns: 3; column-gap: 28px; }
        .list li { margin: 0 0 5px; color: #425969; }
        .list li::marker { color: #008f98; }
        .footer { margin-top: 24px; padding-top: 10px; border-top: 1px solid #dbe6ee; color: #718594; font-size: 8pt; text-align: center; }
        @media print { body { print-color-adjust: exact; -webkit-print-color-adjust: exact; } }
    </style>
</head>
<body>
    @php
        $portada = $propiedad->imagenes->first();
        $portadaSrc = $portada && ! $esPdf ? $portada->obtenerUrlPublica() : null;
        $operacion = $propiedad->operaciones->firstWhere('estado.value', 'publicada') ?? $propiedad->operaciones->first();
        $caracteristicas = $propiedad->caracteristicas;
        $grupos = [
            'Servicios' => $caracteristicas->where('categoria', \App\Enums\CategoriaCaracteristica::SERVICIO),
            'Ambientes y espacios' => $caracteristicas->where('categoria', \App\Enums\CategoriaCaracteristica::AMBIENTE),
            'Amenities' => $caracteristicas->where('categoria', \App\Enums\CategoriaCaracteristica::AMENITY),
            'Observaciones' => $caracteristicas->where('categoria', \App\Enums\CategoriaCaracteristica::OBSERVACION),
            'Preferencia de lote' => $caracteristicas->where('categoria', \App\Enums\CategoriaCaracteristica::PREFERENCIA_LOTE),
        ];
        $cartel = $caracteristicas->firstWhere('categoria', \App\Enums\CategoriaCaracteristica::CARTEL);
    @endphp

    <header class="header">
        <div class="brand">NODDO</div>
        <div class="code">Ficha de propiedad · Código {{ $propiedad->codigo_interno }}</div>
    </header>

    <h1>{{ $propiedad->titulo }}</h1>
    @if ($propiedad->direccion)<p class="address">{{ $propiedad->direccion }}</p>@endif
    <p class="location">{{ $propiedad->ubicacion->nombre_completo }}</p>

    <div class="summary">
        <span>{{ $propiedad->tipoPropiedad->nombre }}</span>
        @if ($operacion)<span>{{ ucfirst(str_replace('_', ' ', $operacion->tipo_operacion->value)) }} · {{ ucfirst($operacion->estado->value) }}</span>@endif
        @if ($operacion?->precio)<span>{{ $operacion->moneda?->value }} {{ number_format((float) $operacion->precio, 2, ',', '.') }}</span>@endif
        @if ($propiedad->ambientes)<span>{{ $propiedad->ambientes }} ambientes</span>@endif
        @if ($propiedad->superficie_total)<span>{{ $propiedad->superficie_total }} m²</span>@endif
    </div>

    @if ($portadaSrc)<img src="{{ $portadaSrc }}" class="hero" alt="{{ $propiedad->titulo }}">@endif

    <section class="section">
        <h2>Características</h2>
        <div class="grid">
            <div class="col">
                @if ($propiedad->antiguedad !== null)<p class="row"><span class="label">Antigüedad:</span> <span class="value">{{ $propiedad->antiguedad }}</span></p>@endif
                @if ($propiedad->ambientes !== null)<p class="row"><span class="label">Cant. ambientes:</span> <span class="value">{{ $propiedad->ambientes }}</span></p>@endif
            </div>
            <div class="col">
                @if ($propiedad->dormitorios !== null)<p class="row"><span class="label">Cant. dormitorios:</span> <span class="value">{{ $propiedad->dormitorios }}</span></p>@endif
                @if ($propiedad->banios !== null)<p class="row"><span class="label">Cant. baños:</span> <span class="value">{{ $propiedad->banios }}</span></p>@endif
            </div>
            <div class="col">
                @if ($propiedad->cocheras !== null)<p class="row"><span class="label">Cant. cocheras:</span> <span class="value">{{ $propiedad->cocheras }}</span></p>@endif
                @if ($propiedad->orientacion)<p class="row"><span class="label">Orientación:</span> <span class="value">{{ ucfirst($propiedad->orientacion) }}</span></p>@endif
            </div>
        </div>
    </section>

    <section class="section">
        <h2>Superficies</h2>
        <div class="grid">
            <div class="col"><p class="row"><span class="label">Total:</span> <span class="value">{{ $propiedad->superficie_total ?: 'No informada' }}@if($propiedad->superficie_total) m²@endif</span></p></div>
            <div class="col"><p class="row"><span class="label">Cubierta:</span> <span class="value">{{ $propiedad->superficie_cubierta ?: 'No informada' }}@if($propiedad->superficie_cubierta) m²@endif</span></p></div>
            <div class="col"><p class="row"><span class="label">Descubierta:</span> <span class="value">{{ $propiedad->superficie_descubierta ?: 'No informada' }}@if($propiedad->superficie_descubierta) m²@endif</span></p></div>
        </div>
    </section>

    @if ($operacion || $propiedad->expensas)
        <section class="section">
            <h2>Operaciones</h2>
            <div class="grid">
                <div class="col">@if($operacion)<p class="row"><span class="label">Tipo de operación:</span> <span class="value">{{ ucfirst(str_replace('_', ' ', $operacion->tipo_operacion->value)) }}</span></p><p class="row"><span class="label">Estado:</span> <span class="value">{{ ucfirst($operacion->estado->value) }}</span></p>@endif</div>
                <div class="col">@if($operacion)<p class="row"><span class="label">Precio:</span> <span class="value">{{ $operacion->precio ? $operacion->moneda?->value.' '.number_format((float) $operacion->precio, 2, ',', '.') : 'Consultar' }}</span></p>@endif</div>
                <div class="col"><p class="row"><span class="label">Expensas:</span> <span class="value">{{ $propiedad->expensas ? $propiedad->expensas_moneda?->value.' '.number_format((float) $propiedad->expensas, 2, ',', '.') : 'No informadas' }}</span></p></div>
            </div>
        </section>
    @endif

    @if ($propiedad->descripcion)
        <section class="section"><h2>Descripción</h2><p class="description">{{ $propiedad->descripcion }}</p></section>
    @endif

    @foreach ($grupos as $titulo => $items)
        @if ($items->isNotEmpty())
            <section class="section"><h2>{{ $titulo }}</h2><ul class="list">@foreach($items as $item)<li>{{ $item->nombre }}</li>@endforeach</ul></section>
        @endif
    @endforeach
    @if ($cartel)
        <section class="section"><h2>Cartel</h2><p>{{ $cartel->nombre }}</p></section>
    @endif

    <footer class="footer">Ficha generada desde NODDO · {{ now()->format('d/m/Y') }}</footer>
    @if (! $esPdf)<script>window.addEventListener('load', () => window.print());</script>@endif
</body>
</html>
