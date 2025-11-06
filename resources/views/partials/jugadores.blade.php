<section class="rounded-2xl border border-emerald-800/60 bg-gradient-to-b from-emerald-950/70 to-emerald-900/30 shadow-inner ring-1 ring-emerald-500/10 p-5 relative overflow-hidden">
  <div class="absolute inset-0 opacity-10 bg-[url('/images/pattern_dinos.svg')] bg-repeat bg-[length:320px]"></div>
  
  <div class="flex items-center justify-between mb-4 relative z-10">
    <h2 class="text-sm font-semibold text-emerald-200 tracking-wide flex items-center gap-2">
      👥 {{ __('Active players') }}
    </h2>
    <span class="text-xs text-emerald-400/70">{{ count($datos['jugadores']) }} / 6</span>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 relative z-10">
    @foreach ($datos['jugadores'] as $p)
      @php
        $palette = [
          'emerald' => 'from-emerald-600/20 to-emerald-900/40 ring-emerald-500/40 text-emerald-100',
          'sky'     => 'from-sky-600/20 to-sky-900/40 ring-sky-500/40 text-sky-100',
          'purple'  => 'from-purple-600/20 to-purple-900/40 ring-purple-500/40 text-purple-100',
          'rose'    => 'from-rose-600/20 to-rose-900/40 ring-rose-500/40 text-rose-100',
          'amber'   => 'from-amber-600/20 to-amber-900/40 ring-amber-500/40 text-amber-100',
          'teal'    => 'from-teal-600/20 to-teal-900/40 ring-teal-500/40 text-teal-100',
        ];
        $avatar = $palette[$p['color']] ?? 'from-gray-600/20 to-gray-900/40 ring-gray-500/40 text-gray-100';
        $stats = $estadoJugadores[$p['id']] ?? ['hand' => $p['hand'], 'placed' => $p['placed']];
      @endphp

      <article class="relative rounded-xl border border-emerald-800/60 bg-gradient-to-br {{ $avatar }} p-4 ring-1 ring-inset shadow-md transition-transform hover:scale-[1.02] hover:ring-emerald-400/40">
        <div class="flex items-center justify-between mb-3">
          <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-emerald-700/30 ring-2 ring-emerald-400/50 flex items-center justify-center font-semibold text-sm">
              {{ strtoupper(substr($p['nombre'], 0, 1)) }}
            </div>
            <h3 class="text-sm font-semibold truncate">{{ $p['nombre'] }}</h3>
          </div>
          <span class="text-xs font-mono text-emerald-300/80">#{{ $loop->iteration }}</span>
        </div>

        <div class="grid grid-cols-3 gap-3 text-center">
          <div class="rounded-lg bg-emerald-950/30 border border-emerald-800/50 p-2">
            <dt class="text-[10px] text-emerald-400/80">{{ __('In hand') }}</dt>
            <dd class="text-sm font-semibold text-emerald-100">{{ $stats['hand'] }}</dd>
          </div>
          <div class="rounded-lg bg-emerald-950/30 border border-emerald-800/50 p-2">
            <dt class="text-[10px] text-emerald-400/80">{{ __('Placed') }}</dt>
            <dd class="text-sm font-semibold text-emerald-100">{{ $stats['placed'] }}</dd>
          </div>
          <div class="rounded-lg bg-emerald-950/30 border border-emerald-800/50 p-2">
            <dt class="text-[10px] text-emerald-400/80">{{ __('Points') }}</dt>
            <dd class="text-lg font-extrabold text-emerald-300 drop-shadow-md animate-[pulse_2s_infinite]">{{ $p['score'] }}</dd>
          </div>
        </div>
      </article>
    @endforeach
  </div>
</section>
