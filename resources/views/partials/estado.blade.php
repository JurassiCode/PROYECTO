<article class="rounded-2xl border border-emerald-800/60 bg-emerald-950/40 shadow-sm ring-1 ring-emerald-500/10">
  <div class="px-5 py-4 border-b border-emerald-800/60 flex items-center justify-between">
    <h2 class="text-emerald-100 font-semibold text-sm tracking-wide">{{ __('Game status') }}</h2>
    <span class="text-[11px] text-emerald-300/80">{{ __('In progress') }}</span>
  </div>
  <div class="p-5 grid grid-cols-3 gap-3 text-center">
    <div class="rounded-xl bg-emerald-900/40 border border-emerald-800/60 p-3">
      <div class="text-[11px] text-emerald-300/80">{{ __('Phase') }}</div>
      <div class="text-lg font-semibold text-emerald-50">{{ $datos['fase'] }}</div>
    </div>
    <div class="rounded-xl bg-emerald-900/40 border border-emerald-800/60 p-3">
      <div class="text-[11px] text-emerald-300/80">{{ __('Turn') }}</div>
      <div class="text-lg font-semibold text-emerald-50">{{ $datos['turno'][0] }} / {{ $datos['turno'][1] }}</div>
    </div>
    <div class="rounded-xl bg-emerald-900/40 border border-emerald-800/60 p-3">
      <div class="text-[11px] text-emerald-300/80">{{ __('Round') }}</div>
      <div class="text-lg font-semibold text-emerald-50">{{ $datos['ronda'][0] }} / {{ $datos['ronda'][1] }}</div>
    </div>
  </div>
</article>
