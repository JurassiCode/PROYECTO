<article class="rounded-2xl border border-emerald-800/60 bg-emerald-950/40 shadow-sm ring-1 ring-emerald-500/10">
  <div class="px-5 py-4 border-b border-emerald-800/60 flex items-center justify-between">
    <h2 class="text-emerald-100 font-semibold text-sm tracking-wide">Colocaciones del turno</h2>
    @if (isset($colocaciones) && $colocaciones->count())
      <span class="text-[11px] text-emerald-300/80">Total: {{ $colocaciones->count() }}</span>
    @endif
  </div>

  <div class="p-4">
    @if (isset($colocaciones) && $colocaciones->count())
      <div class="overflow-x-auto rounded-md border border-emerald-800/60">
        <table class="min-w-full text-sm">
          <thead class="bg-emerald-900/50 text-emerald-300/90 text-xs">
            <tr>
              <th class="px-3 py-2 text-left">Jugador</th>
              <th class="px-3 py-2 text-left">Dino</th>
              <th class="px-3 py-2 text-left">Recinto</th>
              <th class="px-3 py-2 text-right">Puntos</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-emerald-800/60">
            @foreach ($colocaciones as $c)
              <tr class="text-emerald-100 hover:bg-emerald-900/30 transition">
                <td class="px-3 py-2">{{ $c->usuario->nombre ?? $c->usuario->nickname }}</td>
                <td class="px-3 py-2">{{ $c->dinoCatalogo->nombre_corto }}</td>
                <td class="px-3 py-2">{{ $c->recintoCatalogo->descripcion }}</td>
                <td class="px-3 py-2 text-right font-semibold text-emerald-300">+{{ $c->pts_obtenidos }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="text-sm text-emerald-200/70">Sin colocaciones todavía.</p>
    @endif
  </div>
</article>
    