@extends('layouts.playLayout')

@section('title', "Partida #{$partida->id} ({$partida->nombre})")

@section('content')
@php
// colores de posición
$colores = [
0 => ['bg' => 'from-amber-300 to-yellow-500', 'text' => 'text-yellow-950'],
1 => ['bg' => 'from-gray-300 to-gray-400', 'text' => 'text-gray-900'],
2 => ['bg' => 'from-orange-300 to-amber-500', 'text' => 'text-orange-950'],
];
@endphp

<!-- ===== Fondo jurásico ===== -->
<section class="min-h-[100dvh] relative flex flex-col items-center justify-start py-16 px-6 text-gray-100">
    <div class="absolute inset-0 bg-[radial-gradient(1000px_600px_at_15%_-10%,#064e3b_10%,#022c22_35%,#0b1412_70%)]"></div>
    <div class="pointer-events-none absolute inset-0 opacity-[0.06] mix-blend-overlay"
        style="background-image:url('/images/pattern_dinos.svg');background-size:400px;background-repeat:repeat;"></div>

    <!-- 🏆 Encabezado -->
    <div class="relative text-center mb-14">
        <h1 class="text-5xl font-extrabold tracking-tight drop-shadow-lg text-amber-300">
            🦖 Resultados de la Partida
        </h1>
        <p class="mt-3 text-emerald-100/90 text-lg">Partida #{{ $partida->id }} ({{ $partida->nombre }}) finalizada</p>
    </div>

    <!-- 🥇 Podio -->
    <div class="relative z-10 grid grid-cols-3 gap-6 items-end max-w-5xl w-full mb-20">

        {{-- 🥈 Segundo Lugar --}}
        @if (isset($jugadores[1]))
        <div class="flex flex-col items-center">
            <div class="bg-gradient-to-b {{ $colores[1]['bg'] }} {{ $colores[1]['text'] }} 
                  w-28 h-32 sm:w-32 sm:h-36 flex flex-col items-center justify-center 
                  rounded-t-2xl shadow-xl border border-white/10">
                <span class="text-3xl sm:text-4xl">🥈</span>
                <p class="text-sm font-semibold mt-1">{{ $jugadores[1]['nombre'] }}</p>
                <p class="text-xl font-extrabold">{{ $jugadores[1]['puntos'] }}</p>
            </div>
            <div class="bg-gray-500 w-28 sm:w-32 h-10 rounded-b-2xl shadow-inner"></div>
        </div>
        @endif

        {{-- 🥇 Primer Lugar --}}
        @if (isset($jugadores[0]))
        <div class="flex flex-col items-center scale-110">
            <div class="bg-gradient-to-b {{ $colores[0]['bg'] }} {{ $colores[0]['text'] }} 
                  w-32 h-40 sm:w-36 sm:h-44 flex flex-col items-center justify-center 
                  rounded-t-2xl shadow-2xl border border-white/20">
                <span class="text-4xl sm:text-5xl">🥇</span>
                <p class="text-base font-semibold mt-1">{{ $jugadores[0]['nombre'] }}</p>
                <p class="text-2xl font-extrabold">{{ $jugadores[0]['puntos'] }}</p>
            </div>
            <div class="bg-amber-500 w-32 sm:w-36 h-14 rounded-b-2xl shadow-inner"></div>
        </div>
        @endif

        {{-- 🥉 Tercer Lugar --}}
        @if (isset($jugadores[2]))
        <div class="flex flex-col items-center">
            <div class="bg-gradient-to-b {{ $colores[2]['bg'] }} {{ $colores[2]['text'] }} 
                  w-28 h-28 sm:w-32 sm:h-32 flex flex-col items-center justify-center 
                  rounded-t-2xl shadow-lg border border-white/10">
                <span class="text-2xl sm:text-3xl">🥉</span>
                <p class="text-sm font-semibold mt-1">{{ $jugadores[2]['nombre'] }}</p>
                <p class="text-lg font-extrabold">{{ $jugadores[2]['puntos'] }}</p>
            </div>
            <div class="bg-orange-500 w-28 sm:w-32 h-8 rounded-b-2xl shadow-inner"></div>
        </div>
        @endif
    </div>

    <!-- 📊 Tabla completa -->
    <div class="relative z-10 w-full max-w-3xl bg-emerald-950/40 border border-emerald-800/40 
              rounded-2xl overflow-hidden backdrop-blur-sm shadow-xl">
        <table class="w-full text-center">
            <thead class="bg-emerald-900/70 text-emerald-200 uppercase text-sm">
                <tr>
                    <th class="px-4 py-3">Posición</th>
                    <th class="px-4 py-3">Jugador</th>
                    <th class="px-4 py-3">Puntos</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-emerald-900/40">
                @foreach ($jugadores as $i => $j)
                <tr class="{{ $i === 0 ? 'bg-amber-300/10 font-extrabold text-amber-200' : 
                        ($i === 1 ? 'bg-gray-300/5 font-bold text-gray-200' : 
                        ($i === 2 ? 'bg-orange-400/10 font-semibold text-orange-200' : 'text-gray-100')) }}">
                    <td class="px-4 py-3">
                        @if ($i === 0)
                        🥇 {{ $i + 1 }}
                        @elseif ($i === 1)
                        🥈 {{ $i + 1 }}
                        @elseif ($i === 2)
                        🥉 {{ $i + 1 }}
                        @else
                        {{ $i + 1 }}
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ $j['nombre'] }}</td>
                    <td class="px-4 py-3">{{ $j['puntos'] }} pts</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- 🎮 Botones -->
    <div class="relative z-10 mt-12 flex flex-wrap justify-center gap-4">
        <a href="{{ route('lobby') }}"
            class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-lg font-semibold rounded-xl shadow-md transition
              hover:shadow-emerald-400/30">
            🔄 Jugar de nuevo
        </a>
        <a href="{{ route('home') }}"
            class="px-8 py-4 bg-gray-700 hover:bg-gray-600 text-lg font-semibold rounded-xl shadow-md transition">
            🏠 Volver al inicio
        </a>
    </div>
</section>
@endsection
