@extends('layouts.playLayout')

@section('title', 'Trackeo de Partida')

@section('content')
@php
$colocaciones = session('colocaciones', []);
$mensajes = session('partida.mensajes', []);
$jugadorMap = collect($datos['jugadores'] ?? [])->keyBy('id');
$dinoMap = collect($datos['dinosaurios'] ?? [])->keyBy('id');
$recintoMap = collect($datos['recintos'] ?? [])->keyBy('id');
$restric = $datos['restric'] ?? ['titulo' => '—', 'desc' => '—'];
@endphp

<div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 space-y-8">

  {{-- ✅ Flash --}}
  @if (session('ok'))
  <div class="rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800 shadow-sm">
    {{ session('ok') }}
  </div>
  @endif

  {{-- ⚠️ Errores --}}
  @if ($errors->any())
  <div class="rounded-md border border-rose-200 bg-rose-50 p-3 text-sm text-rose-800 shadow-sm">
    <ul class="list-disc pl-5 space-y-0.5">
      @foreach ($errors->all() as $e)
      <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  {{-- 💬 Mensajes dinámicos --}}
  @if (!empty($mensajes))
  <div class="rounded-md border border-blue-200 bg-blue-50 p-3 text-sm text-blue-800 shadow-sm">
    <ul class="list-disc pl-4 space-y-0.5">
      @foreach ($mensajes as $m)
      <li>{{ $m }}</li>
      @endforeach
    </ul>
  </div>
  @endif

  {{-- 🦕 Encabezado --}}
  <header class="flex items-center justify-between py-4 border-b border-gray-200">
    <h1 class="text-xl font-bold text-gray-900 tracking-tight">Trackeo de Partida</h1>
    <div class="flex items-center gap-2 text-sm">
      <span class="rounded-md bg-emerald-50 text-emerald-700 px-2 py-0.5 font-medium">Sala</span>
      <span class="font-mono text-white">{{ $datos['sala'] ?? 'Local' }}</span>
    </div>
  </header>

  {{-- 🎲 Estado global --}}
  <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Fase / Turno / Ronda --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-gray-700">Estado</h2>
        <span class="text-xs text-gray-500">Draft en curso</span>
      </div>

      <div class="grid grid-cols-3 gap-3 text-center">
        @foreach ([['Fase', $datos['fase'] ?? 'Draft'], ['Turno', $datos['turno'][0] . ' / ' . $datos['turno'][1]], ['Ronda', $datos['ronda'][0] . ' / ' . $datos['ronda'][1]]] as [$label, $value])
        <div class="rounded-lg bg-gray-50 p-3">
          <div class="text-xs text-gray-500">{{ $label }}</div>
          <div class="text-lg font-semibold text-gray-900">{{ $value }}</div>
        </div>
        @endforeach
      </div>
    </div>

    {{-- Restricción del dado --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-gray-700">Restricción del dado</h2>
        <form method="POST" action="{{ route('partidas.tirarDado') }}">
          @csrf
          <button type="submit"
            class="rounded-md bg-emerald-600 px-3 py-1.5 text-white text-xs font-semibold shadow hover:bg-emerald-700 transition">
            🎲 Tirar Dado
          </button>
        </form>
      </div>

      <div class="flex items-center gap-4">
        <div
          class="rounded-xl border border-gray-200 bg-emerald-50 text-emerald-700 w-20 h-20 flex items-center justify-center text-3xl font-bold shadow-sm select-none">
          🎲
        </div>
        <div class="flex-1">
          <div class="text-sm font-medium text-gray-900">{{ $restric['titulo'] ?? '—' }}</div>
          <p class="text-sm text-gray-600 leading-snug">{{ $restric['desc'] ?? '—' }}</p>
        </div>
      </div>
    </div>
  </section>

  {{-- 👥 Jugadores --}}
  <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between mb-3">
      <h2 class="text-sm font-semibold text-gray-700">Jugadores</h2>
      <span class="text-xs text-gray-500">{{ count($datos['jugadores'] ?? []) }} / 6</span>
    </div>

    @if (empty($datos['jugadores']))
    <div class="rounded-md border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">
      No hay jugadores cargados. Agregalos desde <a href="{{ route('play') }}" class="underline font-medium">/play</a>.
    </div>
    @else
    <div class="overflow-x-auto">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 min-w-[640px]">
        @foreach ($datos['jugadores'] as $p)
        @php
        $palette = [
        'emerald' => 'bg-emerald-100 text-emerald-700',
        'sky' => 'bg-sky-100 text-sky-700',
        'purple' => 'bg-purple-100 text-purple-700',
        'rose' => 'bg-rose-100 text-rose-700',
        'amber' => 'bg-amber-100 text-amber-700',
        'teal' => 'bg-teal-100 text-teal-700',
        ];
        $avatar = $palette[$p['color']] ?? 'bg-gray-100 text-gray-700';
        @endphp

        <article class="rounded-lg border border-gray-200 bg-white p-3 shadow-sm hover:shadow-md transition">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="h-8 w-8 rounded-full {{ $avatar }} flex items-center justify-center text-sm font-semibold">
                {{ strtoupper(substr($p['nombre'], 0, 1)) }}
              </div>
              <h3 class="text-sm font-semibold text-gray-900">{{ $p['nombre'] }}</h3>
            </div>
            <span class="rounded-full bg-emerald-50 text-emerald-700 text-xs px-2 py-0.5 font-medium">
              {{ $p['estado'] }}
            </span>
          </div>

          <dl class="mt-2 grid grid-cols-3 gap-2 text-center">
            <div class="rounded-md bg-gray-50 p-2">
              <dt class="text-[11px] text-gray-500">En mano</dt>
              <dd class="text-sm font-semibold text-gray-900">{{ $p['hand'] }}</dd>
            </div>
            <div class="rounded-md bg-gray-50 p-2">
              <dt class="text-[11px] text-gray-500">Colocados</dt>
              <dd class="text-sm font-semibold text-gray-900">{{ $p['placed'] }}</dd>
            </div>
            <div class="rounded-md bg-gray-50 p-2">
              <dt class="text-[11px] text-gray-500">Puntos</dt>
              <dd class="text-sm font-semibold text-gray-900">{{ $p['score'] }}</dd>
            </div>
          </dl>
        </article>
        @endforeach
      </div>
    </div>
    @endif
  </section>

  {{-- 🦴 Colocar dinosaurio --}}
  <section class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
    <h2 class="text-sm font-semibold text-gray-700 mb-4">Colocar Dinosaurio</h2>

    <form method="POST" action="{{ route('partidas.agregarColocacion') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
      @csrf
      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Jugador</label>
        <select name="jugador" class="w-full rounded-md border-gray-300 text-sm focus:ring-emerald-500" required>
          <option value="">Seleccionar</option>
          @foreach ($datos['jugadores'] as $j)
          <option value="{{ $j['id'] }}">{{ $j['nombre'] }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Dinosaurio</label>
        <select name="dino" class="w-full rounded-md border-gray-300 text-sm focus:ring-emerald-500" required>
          <option value="">Seleccionar</option>
          @foreach ($datos['dinosaurios'] as $d)
          <option value="{{ $d->id }}">{{ $d->nombre_corto }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1">Recinto</label>
        <select name="recinto" class="w-full rounded-md border-gray-300 text-sm focus:ring-emerald-500" required>
          <option value="">Seleccionar</option>
          @foreach ($datos['recintos'] as $r)
          <option value="{{ $r->id }}">{{ $r->descripcion }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-span-1 sm:col-span-3">
        <button type="submit"
          class="w-full rounded-md bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition">
          🦕 Agregar colocación
        </button>
      </div>
    </form>

    @if (!empty($colocaciones))
    <div class="mt-5">
      <h3 class="text-xs font-semibold text-gray-700 mb-2">Colocaciones del turno actual</h3>
      <div class="overflow-x-auto rounded-md border border-gray-200">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-500 text-xs">
            <tr>
              <th class="px-3 py-2 text-left">Jugador</th>
              <th class="px-3 py-2 text-left">Dino</th>
              <th class="px-3 py-2 text-left">Recinto</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($colocaciones as $c)
            <tr class="border-t text-gray-700 hover:bg-gray-50 transition">
              <td class="px-3 py-1.5">{{ $jugadorMap[$c['jugador']]['nombre'] ?? 'Jugador' }}</td>
              <td class="px-3 py-1.5">{{ $dinoMap[$c['dino']]->nombre_corto ?? '—' }}</td>
              <td class="px-3 py-1.5">{{ $recintoMap[$c['recinto']]->descripcion ?? '—' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
  </section>

  {{-- 🧮 Puntuación / Acciones --}}
  <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Tabla de puntuación --}}
    <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
      <div class="flex items-center justify-between mb-3">
        <h2 class="text-sm font-semibold text-gray-700">Puntuación</h2>
        <span class="text-xs text-gray-500">Visual</span>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="text-left text-gray-500 border-b">
              <th class="py-2 pr-4">Jugador</th>
              <th class="py-2 pl-4 text-right">Total</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach ($datos['score_rows'] as $r)
            <tr>
              <td class="py-2 pr-4 font-medium text-gray-800">{{ $r['jugador'] }}</td>
              <td class="py-2 pl-4 text-right font-semibold text-gray-900">{{ $r['total'] }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Acciones finales --}}
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
      <h2 class="text-sm font-semibold text-gray-700 mb-3">Acciones</h2>
      <div class="grid grid-cols-1 gap-2">
        <form action="#" method="POST" onsubmit="alert('Partida finalizada (local).'); return false;">
          @csrf
          <button type="submit"
            class="w-full rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-700 transition">
            ✅ Finalizar partida
          </button>
        </form>
      </div>
      <p class="mt-3 text-xs text-gray-500 leading-relaxed">
        * La partida se guarda localmente hasta finalizar.
      </p>
    </div>
  </section>
</div>
@endsection