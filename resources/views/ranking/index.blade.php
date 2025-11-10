@extends('layouts.publicLayout')

@section('title', __('Player Ranking'))

@section('content')
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-800 to-gray-900 text-white py-20">
  <!--  Patrón de fondo -->
  <div class="absolute inset-0 opacity-10 bg-repeat" style="background-image: url(/images/pattern_dinos.svg);"></div>

  <div class="relative mx-auto max-w-6xl px-6 lg:px-8 text-center mb-12">
    <h1 class="text-5xl font-extrabold mb-4 drop-shadow-md">
      🏆 {{ __('Player Ranking') }} 🏆
    </h1>
    <p class="text-emerald-100 text-lg max-w-2xl mx-auto">
      {{ __('The best adventurers of JurassiDraft.') }}  
      {{ __('Compete, earn points, and secure your place among the legendary dinosaurs.') }}
    </p>
  </div>

  <!--  Tabla ranking -->
<div class="relative mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
  <div class="backdrop-blur-md bg-white/10 border border-white/20 rounded-3xl shadow-xl overflow-hidden">

    <!-- Versión de tabla para pantallas grandes -->
    <div class="hidden sm:block overflow-x-auto">
      <table class="w-full border-collapse">
        <thead class="bg-emerald-900/60 text-emerald-100 uppercase tracking-wide text-sm">
          <tr>
            <th class="py-4 px-6 text-left">{{ __('Position') }}</th>
            <th class="py-4 px-6 text-left">{{ __('Player') }}</th>
            <th class="py-4 px-6 text-right">{{ __('Total Points') }}</th>
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
                {{ $item->usuario->nickname ?? __('Anonymous') }}
              </td>
              <td class="py-4 px-6 text-lg font-bold text-right text-amber-300">
                {{ number_format($item->puntos_acumulados, 0, ',', '.') }}
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center py-10 text-gray-300">
                {{ __('There are no registered players in the ranking yet.') }}
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Versión móvil tipo tarjetas -->
    <div class="sm:hidden divide-y divide-white/10">
      @forelse ($ranking as $index => $item)
        @php
          $rank = $index + 1;
          $medalla = match($rank) {
            1 => '🥇',
            2 => '🥈',
            3 => '🥉',
            default => null,
          };
        @endphp
        <div class="p-4 flex flex-col gap-2 bg-white/5 rounded-2xl m-3 border border-white/10 shadow-md">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="text-lg font-bold text-emerald-200">{{ $rank }}</span>
              @if($medalla)
                <span class="text-xl">{{ $medalla }}</span>
              @endif
            </div>
            <span class="text-amber-300 font-bold text-lg">
              {{ number_format($item->puntos_acumulados, 0, ',', '.') }}
            </span>
          </div>
          <div class="text-gray-100 font-semibold text-base truncate">
            {{ $item->usuario->nickname ?? __('Anonymous') }}
          </div>
        </div>
      @empty
        <div class="text-center py-10 text-gray-300">
          {{ __('There are no registered players in the ranking yet.') }}
        </div>
      @endforelse
    </div>

  </div>
</div>

  <!--  Frase final -->
  <div class="relative text-center mt-12 text-emerald-100 px-6">
    <p class="text-lg italic">
      “{{ __('Only the most skilled conquer the board of') }} <span class="text-amber-300 font-semibold">JurassiDraft</span>.”
    </p>
  </div>
</section>
@endsection
