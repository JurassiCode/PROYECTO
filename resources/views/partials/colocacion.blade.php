<article class="rounded-2xl border border-emerald-800/60 bg-emerald-950/40 shadow-sm ring-1 ring-emerald-500/10">
  <div class="px-5 py-4 border-b border-emerald-800/60 flex items-center justify-between">
    <h2 class="text-emerald-100 font-semibold text-sm tracking-wide">Agregar colocación</h2>
  </div>
  <form method="POST" action="{{ route('partidas.agregarColocacion', $partida->id) }}" class="p-5 grid grid-cols-3 gap-3">
    @csrf
    <div>
      <label class="block text-[11px] text-emerald-300/80 mb-1">Jugador</label>
      <select name="jugador" class="w-full rounded-md border-emerald-800 bg-emerald-900/60 text-emerald-50 text-sm focus:ring-sky-500" required>
        <option value="">Seleccionar</option>
        @foreach ($datos['jugadores'] as $j)
          <option value="{{ $j['id'] }}">{{ $j['nombre'] }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-[11px] text-emerald-300/80 mb-1">Dinosaurio</label>
      <select name="dino" class="w-full rounded-md border-emerald-800 bg-emerald-900/60 text-emerald-50 text-sm focus:ring-sky-500" required>
        <option value="">Seleccionar</option>
        @foreach ($datos['dinosaurios'] as $d)
          <option value="{{ $d->id }}">{{ $d->nombre_corto }}</option>
        @endforeach
      </select>
    </div>
    <div>
      <label class="block text-[11px] text-emerald-300/80 mb-1">Recinto</label>
      <select name="recinto" class="w-full rounded-md border-emerald-800 bg-emerald-900/60 text-emerald-50 text-sm focus:ring-sky-500" required>
        <option value="">Seleccionar</option>
        @foreach ($datos['recintos'] as $r)
          <option value="{{ $r->id }}">{{ $r->descripcion }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-span-3 flex justify-end">
      <button type="submit" class="rounded-md bg-sky-600 hover:bg-sky-500 px-4 py-2 text-xs font-semibold text-white">
        Guardar
      </button>
    </div>
  </form>
</article>
