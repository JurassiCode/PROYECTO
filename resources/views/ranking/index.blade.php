@extends('layouts.publicLayout')

@section('title', 'Ranking de Jugadores')

@section('content')
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-800 to-gray-900 text-white py-20">
  <!-- 🌿 Patrón de fondo -->
  <div class="absolute inset-0 opacity-10 bg-repeat" style="background-image: url(/images/pattern_dinos.svg);"></div>

  <div class="relative mx-auto max-w-6xl px-6 lg:px-8 text-center mb-12">
    <h1 class="text-5xl font-extrabold mb-4 drop-shadow-md">
      🏆 Ranking de Jugadores 🏆
    </h1>
    <p class="text-emerald-100 text-lg max-w-2xl mx-auto">
      Los mejores aventureros del JurassiDraft.  
      Competí, sumá puntos y ganá tu lugar entre los dinosaurios legendarios.
    </p>
  </div>

  <!-- 🦖 Tabla ranking -->
  <div class="relative mx-auto max-w-5xl px-6 lg:px-8">
    <div class="backdrop-blur-md bg-white/10 border border-white/20 rounded-3xl shadow-xl overflow-hidden">
      <table class="w-full border-collapse">
        <thead class="bg-emerald-900/60 text-emerald-100 uppercase tracking-wide text-sm">
          <tr>
            <th class="py-4 px-6 text-left">Posición</th>
            <th class="py-4 px-6 text-left">Jugador</th>
            <th class="py-4 px-6 text-right">Puntos Totales</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($ranking as $index => $item)
            @php
              $rank = $index + 1;
              $medalla = match($rank) {
                1 => '🥇',
                2 => '🥈',
                3 => '🥉',
                default => null,
              };
              $rowBg = match($rank) {
                1 => 'bg-gradient-to-r from-yellow-400/10 to-amber-400/5',
                2 => 'bg-gradient-to-r from-gray-300/10 to-gray-400/5',
                3 => 'bg-gradient-to-r from-orange-400/10 to-amber-400/5',
                default => 'hover:bg-white/5',
              };
            @endphp
            <tr class="transition {{ $rowBg }}">
              <td class="py-4 px-6 font-bold text-emerald-200 flex items-center gap-2">
                <span class="text-xl">{{ $rank }}</span>
                @if($medalla)
                  <span class="text-2xl">{{ $medalla }}</span>
                @endif
              </td>
              <td class="py-4 px-6 text-lg font-semibold text-gray-100">
                {{ $item->usuario->nickname ?? 'Anónimo' }}
              </td>
              <td class="py-4 px-6 text-lg font-bold text-right text-amber-300">
                {{ number_format($item->puntos_acumulados, 0, ',', '.') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center py-10 text-gray-300">Aún no hay jugadores registrados en el ranking.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- 🌋 Frase final -->
  <div class="relative text-center mt-12 text-emerald-100 px-6">
    <p class="text-lg italic">
      “Solo los más hábiles conquistan el tablero de <span class="text-amber-300 font-semibold">JurassiDraft</span>.”
    </p>
  </div>
</section>
@endsection
