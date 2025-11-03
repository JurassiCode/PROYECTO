@extends('layouts.publicLayout')

@section('title', 'Perfil')

@section('content')
<section
  x-data="{ openModal: false, showToast: {{ session('success') ? 'true' : 'false' }} }"
  x-init="if(showToast){ setTimeout(() => showToast = false, 3000) }"
  class="min-h-[100vh] bg-gradient-to-br from-emerald-900 via-emerald-950 to-gray-900 text-gray-100 py-12 relative">

  {{--  Toast de éxito --}}
  <div
    x-show="showToast"
    x-transition.opacity
    class="fixed top-6 left-1/2 -translate-x-1/2 bg-emerald-700/80 text-sm px-5 py-2 rounded-md shadow-lg z-50 text-white">
    {{ session('success') }}
  </div>

  <div class="max-w-5xl mx-auto px-6 space-y-10">

    {{--  Encabezado --}}
    <header class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-4xl font-extrabold text-emerald-300 drop-shadow-sm">
          {{ $user->nombre }}
        </h1>
        <h2 class="text-base text-emerald-100/90">
          Usuario: <span class="font-semibold text-emerald-200">{{ $user->nickname }}</span>
          <span class="text-gray-400">#{{ $user->id }}</span>
        </h2>
        <p class="text-sm text-gray-400 mt-1">
          Miembro desde {{ $user->creado_en->format('d/m/Y') }}
        </p>
      </div>

      <button
        @click="openModal = true"
        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-700/70 text-sm font-medium rounded-md 
               hover:bg-emerald-600/80 transition shadow-md">
        Editar perfil
      </button>
    </header>

    {{--  Estadísticas --}}
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 text-center">
      <div class="bg-emerald-800/30 hover:bg-emerald-800/50 p-5 rounded-xl shadow-sm transition">
        <p class="text-4xl font-extrabold text-emerald-200">{{ $stats['jugadas'] }}</p>
        <p class="text-sm text-gray-300 tracking-wide mt-1">Partidas jugadas</p>
      </div>
      <div class="bg-emerald-800/30 hover:bg-emerald-800/50 p-5 rounded-xl shadow-sm transition">
        <p class="text-4xl font-extrabold text-emerald-200">{{ $stats['creadas'] }}</p>
        <p class="text-sm text-gray-300 tracking-wide mt-1">Partidas creadas</p>
      </div>
      <div class="bg-emerald-800/30 hover:bg-emerald-800/50 p-5 rounded-xl shadow-sm transition">
        <p class="text-4xl font-extrabold text-emerald-200">{{ $stats['puntos_totales'] }}</p>
        <p class="text-sm text-gray-300 tracking-wide mt-1">Puntos totales</p>
      </div>
      <div class="bg-gray-800/40 hover:bg-gray-700/60 p-5 rounded-xl shadow-sm transition relative">
        <p class="text-4xl font-extrabold text-gray-400">
          {{ $stats['ganadas'] ?? '—' }}
        </p>
        <p class="text-sm text-gray-300 tracking-wide mt-1">Partidas ganadas</p>
        <span class="absolute top-2 right-2 text-[10px] bg-emerald-600/60 px-2 py-0.5 rounded-full text-white font-semibold">
          Coming Soon
        </span>
      </div>
    </div>


    {{--  Partidas jugadas --}}
    <section>
      <h2 class="text-xl font-semibold mb-4 text-emerald-400 flex items-center gap-2">
        🎲 Partidas jugadas
      </h2>

      @forelse ($partidasJugadas as $pj)
      <a href="{{ route('resultados.partida.show', $pj->partida->id ?? 0) }}"
        class="bg-gray-800/40 hover:bg-gray-700/50 transition rounded-md px-4 py-3 mb-2 flex justify-between items-center">
        <span class="font-medium">{{ $pj->partida->nombre ?? '—' }}</span>
        <span class="text-xs text-gray-400">Puntos: {{ $pj->puntos_totales }}</span>
      </a>
      @empty
      <p class="text-sm text-gray-500 italic">Todavía no jugaste ninguna partida.</p>
      @endforelse
    </section>

    {{--  Partidas creadas --}}
    <section>
      <h2 class="text-xl font-semibold mb-4 text-emerald-400 flex items-center gap-2">
        👷‍♂️ Partidas creadas
      </h2>

      @forelse ($partidasCreadas as $p)
      <a href="{{ route('trackeo.partida.show', $p->id) }}"
        class="bg-gray-800/40 hover:bg-gray-700/50 transition rounded-md px-4 py-3 mb-2 flex justify-between items-center">
        <span class="font-medium">{{ $p->nombre }}</span>
        <span class="text-xs text-gray-400 uppercase tracking-wide">{{ $p->estado }}</span>
      </a>
      @empty
      <p class="text-sm text-gray-500 italic">No creaste ninguna partida todavía.</p>
      @endforelse
    </section>
  </div>

  {{--  Modal de edición de perfil --}}
  <div
    x-show="openModal"
    x-cloak
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">

    <div
      @click.away="openModal = false"
      class="bg-gray-900/95 border border-emerald-800/40 rounded-xl shadow-xl p-6 w-full max-w-md text-gray-100 transform transition-all scale-100">

      <h2 class="text-xl font-bold text-emerald-300 mb-4">Editar Perfil</h2>

      <form method="POST" action="{{ route('perfil.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Nombre --}}
        <div>
          <label class="block text-sm text-gray-300 mb-1">Nombre</label>
          <input type="text" name="nombre" value="{{ old('nombre', $user->nombre) }}"
            class="w-full rounded-md bg-gray-800 border border-emerald-800/40 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
          @error('nombre') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Nickname --}}
        <div>
          <label class="block text-sm text-gray-300 mb-1">Usuario</label>
          <input type="text" name="nickname" value="{{ old('nickname', $user->nickname) }}"
            class="w-full rounded-md bg-gray-800 border border-emerald-800/40 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500" required>
          @error('nickname') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Contraseña --}}
        <div>
          <label class="block text-sm text-gray-300 mb-1">Nueva contraseña (opcional)</label>
          <input type="password" name="contrasena"
            class="w-full rounded-md bg-gray-800 border border-emerald-800/40 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>

        {{-- Confirmar --}}
        <div>
          <label class="block text-sm text-gray-300 mb-1">Confirmar contraseña</label>
          <input type="password" name="contrasena_confirmation"
            class="w-full rounded-md bg-gray-800 border border-emerald-800/40 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-500">
          @error('contrasena') <p class="text-xs text-red-400 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Botones --}}
        <div class="flex justify-end gap-2 pt-3">
          <button type="button" @click="openModal = false"
            class="px-3 py-2 bg-gray-700 hover:bg-gray-600 rounded-md text-sm transition">
            Cancelar
          </button>
          <button type="submit"
            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 rounded-md text-sm font-medium transition">
            Guardar cambios
          </button>
        </div>
      </form>
    </div>
  </div>

</section>
@endsection