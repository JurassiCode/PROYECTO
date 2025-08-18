<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Trackeo de Partida · JurassiDraft</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-[100dvh] bg-gray-50 text-gray-900">
  <!-- Header -->
  <header class="border-b border-gray-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="{{ asset('images/logojuego_nobg.png') }}" class="h-8 w-auto" alt="Logo JurassiDraft">
        <div>
          <h1 class="text-lg sm:text-xl font-semibold">Trackeo de Partida</h1>
          <p class="text-xs text-gray-500">Visual (usando jugadores de /play)</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <span class="rounded-md bg-emerald-50 text-emerald-700 text-xs px-2 py-1">Sala</span>
        <span class="font-mono text-sm">{{ $datos['sala'] }}</span>
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 space-y-6">

    <!-- Estado global (2 columnas) -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-4">
      <!-- Fase + Turno + Ronda -->
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between">
          <h2 class="text-sm font-semibold text-gray-700">Estado</h2>
          <span class="text-xs text-gray-500">Draft en curso</span>
        </div>
        <div class="mt-3 grid grid-cols-3 gap-2 text-center">
          <div class="rounded-lg bg-gray-50 p-3">
            <div class="text-xs text-gray-500">Fase</div>
            <div class="text-lg font-semibold">{{ $datos['fase'] }}</div>
          </div>
          <div class="rounded-lg bg-gray-50 p-3">
            <div class="text-xs text-gray-500">Turno</div>
            <div class="text-lg font-semibold">{{ $datos['turno'][0] }} / {{ $datos['turno'][1] }}</div>
          </div>
          <div class="rounded-lg bg-gray-50 p-3">
            <div class="text-xs text-gray-500">Ronda</div>
            <div class="text-lg font-semibold">{{ $datos['ronda'][0] }} / {{ $datos['ronda'][1] }}</div>
          </div>
        </div>
      </div>

      <!-- Dado / Restricción -->
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between">
          <h2 class="text-sm font-semibold text-gray-700">Restricción del dado</h2>
        </div>
        <div class="mt-3 flex items-center gap-4">
          <div class="rounded-xl border border-gray-200 bg-emerald-50 text-emerald-700 w-20 h-20 flex items-center justify-center text-3xl font-bold shadow-sm select-none">🎲</div>
          <div class="flex-1">
            <div class="text-sm font-medium">{{ $datos['restric']['titulo'] }}</div>
            <p class="text-sm text-gray-600">{{ $datos['restric']['desc'] }}</p>
            <div class="mt-2 flex flex-wrap gap-2">
              @forelse($datos['restric']['tags'] as $tag)
                <span class="rounded-md bg-gray-100 text-gray-700 text-xs px-2 py-1">{{ $tag }}</span>
              @empty
                <span class="rounded-md bg-gray-100 text-gray-400 text-xs px-2 py-1">Sin restricciones</span>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Jugadores -->
    <section class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
      <div class="flex items-center justify-between">
        <h2 class="text-sm font-semibold text-gray-700">Jugadores</h2>
        <span class="text-xs text-gray-500">{{ count($datos['jugadores']) }} / 6</span>
      </div>

      <div class="mt-3">
        @if (empty($datos['jugadores']))
          <div class="rounded-md border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">
            No hay jugadores cargados. Agregalos desde <a href="{{ route('play') }}" class="underline">/play</a>.
          </div>
        @else
          <div class="overflow-x-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 min-w-[640px] lg:min-w-0">
              @foreach($datos['jugadores'] as $p)
                @php
                  $palette = [
                    'emerald' => 'bg-emerald-100 text-emerald-700',
                    'sky'     => 'bg-sky-100 text-sky-700',
                    'purple'  => 'bg-purple-100 text-purple-700',
                    'rose'    => 'bg-rose-100 text-rose-700',
                    'amber'   => 'bg-amber-100 text-amber-700',
                    'teal'    => 'bg-teal-100 text-teal-700',
                  ];
                  $badge = match($p['estado']) {
                    'Listo'     => 'bg-emerald-50 text-emerald-700',
                    'Pensando'  => 'bg-yellow-50 text-yellow-700',
                    default     => 'bg-gray-100 text-gray-700',
                  };
                  $avatar = $palette[$p['color']] ?? 'bg-gray-100 text-gray-700';
                @endphp

                <article class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                      <div class="h-8 w-8 rounded-full {{ $avatar }} flex items-center justify-center text-sm font-semibold">
                        {{ strtoupper(substr($p['nombre'],0,1)) }}
                      </div>
                      <h3 class="text-sm font-semibold">{{ $p['nombre'] }}</h3>
                    </div>
                    <span class="rounded-full {{ $badge }} text-xs px-2 py-0.5">{{ $p['estado'] }}</span>
                  </div>
                  <dl class="mt-2 grid grid-cols-3 gap-2 text-center">
                    <div class="rounded-md bg-gray-50 p-2">
                      <dt class="text-[11px] text-gray-500">En mano</dt>
                      <dd class="text-sm font-semibold">{{ $p['hand'] }}</dd>
                    </div>
                    <div class="rounded-md bg-gray-50 p-2">
                      <dt class="text-[11px] text-gray-500">Colocados</dt>
                      <dd class="text-sm font-semibold">{{ $p['placed'] }}</dd>
                    </div>
                    <div class="rounded-md bg-gray-50 p-2">
                      <dt class="text-[11px] text-gray-500">Puntos</dt>
                      <dd class="text-sm font-semibold">{{ $p['score'] }}</dd>
                    </div>
                  </dl>
                </article>
              @endforeach
            </div>
          </div>
        @endif
      </div>
    </section>

    <!-- Puntuación / Resumen -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-4">
      <!-- Tabla de puntajes -->
      <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="flex items-center justify-between">
          <h2 class="text-sm font-semibold text-gray-700">Puntuación (snapshot)</h2>
          <span class="text-xs text-gray-500">Visual</span>
        </div>
        <div class="mt-3 overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-left text-gray-500">
                <th class="py-2 pr-4">Jugador</th>
                <th class="py-2 px-4">Recintos</th>
                <th class="py-2 px-4">Parejas</th>
                <th class="py-2 px-4">T-Rex</th>
                <th class="py-2 px-4">Río</th>
                <th class="py-2 pl-4 text-right">Total</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              @foreach($datos['score_rows'] as $r)
                <tr>
                  <td class="py-2 pr-4 font-medium">{{ $r['jugador'] }}</td>
                  <td class="py-2 px-4">{{ $r['recintos'] }}</td>
                  <td class="py-2 px-4">{{ $r['parejas'] }}</td>
                  <td class="py-2 px-4">{{ $r['trex'] }}</td>
                  <td class="py-2 px-4">{{ $r['rio'] }}</td>
                  <td class="py-2 pl-4 text-right font-semibold">{{ $r['total'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Acciones (solo UI) -->
      <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <h2 class="text-sm font-semibold text-gray-700">Acciones</h2>
        <div class="mt-3 grid grid-cols-2 gap-2">
          <span class="rounded-md bg-emerald-600 px-3 py-2 text-white text-sm shadow-sm text-center select-none opacity-60">Siguiente turno</span>
          <span class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm text-center select-none opacity-60">Finalizar ronda</span>
          <span class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm text-center select-none col-span-2 opacity-60">Reiniciar partida</span>
        </div>
        <p class="mt-3 text-xs text-gray-500">Placeholder: sin lógica.</p>
      </div>
    </section>
  </main>

  <footer class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 text-center text-xs text-gray-500">
    JurassiDraft · Visual de trackeo — listo para cablear reglas y cálculo
  </footer>
</body>
</html>
