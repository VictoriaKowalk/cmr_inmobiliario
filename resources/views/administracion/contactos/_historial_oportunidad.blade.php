<section class="border border-neutral-200 bg-white p-5 shadow-sm sm:p-6">
    <h2 class="font-semibold">Historial comercial</h2>
    <div class="mt-5 space-y-4">
        @forelse ($oportunidad->historialOportunidad as $evento)
            <article class="border-l-2 border-emerald-200 pl-4">
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <p class="text-sm font-semibold">{{ $evento->descripcion }}</p>
                    <time class="text-xs text-neutral-500">{{ $evento->created_at->format('d/m/Y H:i') }}</time>
                </div>
                <p class="mt-1 text-xs text-neutral-500">
                    {{ $evento->usuario?->nombreCompleto() ?? 'Sistema' }}
                </p>
                @if ($evento->cambios)
                    <ul class="mt-2 space-y-1 text-xs text-neutral-600">
                        @foreach ($evento->cambios as $campo => $valores)
                            <li><span class="font-semibold">{{ ucfirst($campo) }}:</span> {{ $valores[0] }} → {{ $valores[1] }}</li>
                        @endforeach
                    </ul>
                @endif
            </article>
        @empty
            <p class="text-sm text-neutral-500">Todavía no hay movimientos registrados.</p>
        @endforelse
    </div>
</section>
