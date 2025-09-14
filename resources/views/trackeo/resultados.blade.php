@extends('layouts.playLayout')

@section('title', "Resultados de Partida #{$partida->id_partida}")

@section('content')
    <div class="flex flex-col items-center justify-center">

        <!-- Título -->
        <div class="text-center mb-10">
            <h1 class="text-4xl sm:text-5xl font-extrabold drop-shadow-lg">🏆 Resultados de la Partida</h1>
            <p class="mt-2 text-lg">Partida #{{ $partida->id_partida }} finalizada (datos ficticios, solo placeholders,
                aún no implementado)</p>
        </div>

        <!-- Podio -->
        <div class="grid grid-cols-3 gap-4 items-end w-full max-w-4xl mb-16">
            <!-- 2° Lugar -->
            @if (isset($jugadores[1]))
                <div class="flex flex-col items-center">
                    <div
                        class="bg-gray-200 text-gray-800 rounded-t-xl w-28 h-32 flex flex-col items-center justify-center shadow-lg">
                        <span class="text-3xl">🥈</span>
                        <p class="text-sm font-bold">{{ $jugadores[1]['nombre'] }}</p>
                        <p class="text-xl font-extrabold">{{ $jugadores[1]['puntos'] }}</p>
                    </div>
                    <div class="bg-gray-500 w-28 h-12 rounded-b-xl"></div>
                </div>
            @endif

            <!-- 1° Lugar -->
            @if (isset($jugadores[0]))
                <div class="flex flex-col items-center">
                    <div
                        class="bg-yellow-200 text-yellow-900 rounded-t-xl w-32 h-40 flex flex-col items-center justify-center shadow-2xl scale-110">
                        <span class="text-4xl">🥇</span>
                        <p class="text-base font-bold">{{ $jugadores[0]['nombre'] }}</p>
                        <p class="text-2xl font-extrabold">{{ $jugadores[0]['puntos'] }}</p>
                    </div>
                    <div class="bg-yellow-500 w-32 h-16 rounded-b-xl scale-110"></div>
                </div>
            @endif

            <!-- 3° Lugar -->
            @if (isset($jugadores[2]))
                <div class="flex flex-col items-center">
                    <div
                        class="bg-orange-200 text-orange-900 rounded-t-xl w-28 h-28 flex flex-col items-center justify-center shadow-lg">
                        <span class="text-2xl">🥉</span>
                        <p class="text-sm font-bold">{{ $jugadores[2]['nombre'] }}</p>
                        <p class="text-lg font-extrabold">{{ $jugadores[2]['puntos'] }}</p>
                    </div>
                    <div class="bg-orange-500 w-28 h-10 rounded-b-xl"></div>
                </div>
            @endif
        </div>

        <!-- Tabla completa -->
        <div class="w-full max-w-3xl bg-white text-gray-800 rounded-2xl shadow-lg overflow-hidden mb-12">
            <table class="w-full text-center">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Posición</th>
                        <th class="px-4 py-2">Jugador</th>
                        <th class="px-4 py-2">Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jugadores as $i => $j)
                        <tr
                            class="{{ $i === 0 ? 'bg-yellow-50 font-extrabold' : ($i === 1 ? 'bg-gray-50 font-bold' : ($i === 2 ? 'bg-orange-50 font-semibold' : '')) }}">
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

        <!-- Botones -->
        <div class="flex gap-6">
            <a href="{{ route('play') }}"
                class="px-8 py-4 bg-emerald-500 hover:bg-emerald-600 text-lg font-semibold rounded-xl shadow-md transition">
                🔄 Jugar de nuevo
            </a>
            <a href="{{ route('home') }}"
                class="px-8 py-4 bg-gray-500 hover:bg-gray-600 text-lg font-semibold rounded-xl shadow-md transition">
                🏠 Volver al inicio
            </a>
        </div>
    </div>
@endsection
