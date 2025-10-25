<article class="rounded-2xl border border-emerald-800/60 bg-emerald-950/40 shadow-sm ring-1 ring-emerald-500/10">
  <div class="px-5 py-4 border-b border-emerald-800/60 flex items-center justify-between">
    <h2 class="text-emerald-100 font-semibold text-sm tracking-wide">Restricción del dado</h2>
  </div>
  <div class="p-5 flex items-start gap-4">
    <div class="rounded-2xl border border-emerald-800/60 bg-emerald-900/40 w-20 h-20 flex items-center justify-center text-3xl shadow">
      🎲
    </div>
    <div class="flex-1">
      <div class="text-sm font-medium text-emerald-50">{{ $restricSesion['titulo'] }}</div>
      <p class="text-sm text-emerald-200/80 leading-snug">{{ $restricSesion['desc'] }}</p>

      <form method="POST" action="{{ route('partidas.tirarDado', $partida->id) }}" class="mt-4 grid grid-cols-3 gap-2">
        @csrf
        <div class="col-span-2">
          <label class="block text-[11px] text-emerald-300/80 mb-1">¿Quién tira?</label>
          <select name="tirador" class="w-full rounded-md border-emerald-800 bg-emerald-900/60 text-emerald-50 text-sm focus:ring-emerald-500"
                  required @disabled(!empty($partida->dado_restriccion))>
            <option value="">Seleccionar</option>
            @foreach ($datos['jugadores'] as $j)
              <option value="{{ $j['id'] }}">{{ $j['nombre'] }}</option>
            @endforeach
          </select>
        </div>
        <div class="flex items-end">
          <button type="submit"
                  class="w-full rounded-md bg-emerald-600 hover:bg-emerald-500 px-3 py-2 text-xs font-semibold text-white transition
                         disabled:opacity-50"
                  @disabled(!empty($partida->dado_restriccion))>
            Tirar
          </button>
        </div>
      </form>
    </div>
  </div>
</article>
