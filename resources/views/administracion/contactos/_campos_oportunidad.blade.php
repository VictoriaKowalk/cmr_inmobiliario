<div>
    <label for="estado_seguimiento" class="mb-2 block text-sm font-medium">Etapa del embudo</label>
    <select id="estado_seguimiento" name="estado_seguimiento"
            class="h-11 w-full border border-neutral-300 bg-white px-3 text-sm">
        @foreach ($estadosSeguimiento as $estadoSeguimiento)
            <option value="{{ $estadoSeguimiento->value }}"
                    @selected(old('estado_seguimiento', $oportunidad->estado_seguimiento->value) === $estadoSeguimiento->value)>
                {{ $estadoSeguimiento->etiqueta() }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label for="responsable_id" class="mb-2 block text-sm font-medium">Asesor responsable</label>
    <select id="responsable_id" name="responsable_id"
            class="h-11 w-full border border-neutral-300 bg-white px-3 text-sm">
        <option value="">Sin asignar</option>
        @foreach ($responsables as $responsable)
            <option value="{{ $responsable->id }}"
                    @selected((string) old('responsable_id', $oportunidad->responsable_id) === (string) $responsable->id)>
                {{ $responsable->nombreCompleto() }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label for="prioridad" class="mb-2 block text-sm font-medium">Prioridad</label>
    <select id="prioridad" name="prioridad"
            class="h-11 w-full border border-neutral-300 bg-white px-3 text-sm">
        @foreach ($prioridades as $prioridad)
            <option value="{{ $prioridad->value }}"
                    @selected(old('prioridad', $oportunidad->prioridad->value) === $prioridad->value)>
                {{ $prioridad->etiqueta() }}
            </option>
        @endforeach
    </select>
</div>

<div>
    <label for="proxima_tarea" class="mb-2 block text-sm font-medium">Próxima tarea</label>
    <input id="proxima_tarea" name="proxima_tarea"
           value="{{ old('proxima_tarea', $oportunidad->proxima_tarea) }}"
           placeholder="Ej.: llamar para coordinar visita"
           class="h-11 w-full border border-neutral-300 px-3 text-sm">
</div>

<div>
    <label for="proxima_tarea_en" class="mb-2 block text-sm font-medium">Fecha de la próxima tarea</label>
    <input id="proxima_tarea_en" type="datetime-local" name="proxima_tarea_en"
           value="{{ old('proxima_tarea_en', $oportunidad->proxima_tarea_en?->format('Y-m-d\TH:i')) }}"
           class="h-11 w-full border border-neutral-300 px-3 text-sm">
</div>

<div>
    <label for="motivo_cierre" class="mb-2 block text-sm font-medium">Motivo de cierre o pérdida</label>
    <textarea id="motivo_cierre" name="motivo_cierre" rows="3"
              class="w-full border border-neutral-300 px-3 py-2 text-sm">{{ old('motivo_cierre', $oportunidad->motivo_cierre) }}</textarea>
    <p class="mt-1 text-xs text-neutral-500">Obligatorio cuando la oportunidad se marca como perdida o cerrada.</p>
</div>

<div>
    <label for="notas_internas" class="mb-2 block text-sm font-medium">Notas internas</label>
    <textarea id="notas_internas" name="notas_internas" rows="6"
              class="w-full border border-neutral-300 px-3 py-2 text-sm outline-none focus:border-emerald-700">{{ old('notas_internas', $oportunidad->notas_internas) }}</textarea>
</div>
