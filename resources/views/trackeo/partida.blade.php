@extends('layouts.playLayout')

@section('title', 'Seguimiento de Partida')

@section('content')
@php
  $mensajes       = session('partida.mensajes', []);
  $restricSesion  = session('restriccion', $datos['restric'] ?? ['titulo' => '—', 'desc' => '—']);
  $estadoJugadores= session('jugadores_estado', []);
  $flash          = session('ok');
@endphp

<!-- ===== Fondo jurásico limpio ===== -->
<div class="min-h-[100dvh] relative overflow-x-hidden bg-[radial-gradient(1200px_600px_at_10%_-10%,#064e3b_10%,#022c22_35%,#0b1412_70%)]">
  <div class="pointer-events-none absolute inset-0 opacity-[0.08] mix-blend-overlay"
       style="background-image:url('/images/pattern_dinos.svg');background-size:360px;background-repeat:repeat;">
  </div>

  <!-- ===== Top bar ===== -->
  <header class="sticky top-0 z-30 backdrop-blur supports-[backdrop-filter]:bg-emerald-950/60 bg-emerald-950/80 border-b border-emerald-800">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <h1 class="text-sm sm:text-base font-semibold text-emerald-50 tracking-wide">
          Seguimiento de Partida
        </h1>
      </div>
      <div class="flex items-center gap-2 text-xs">
        <span class="rounded-md bg-emerald-700/60 text-emerald-50 px-2 py-0.5 border border-emerald-600/60">Sala</span>
        <span class="font-mono text-emerald-100">{{ $datos['sala'] }}</span>
      </div>
    </div>
  </header>

  <!-- ===== Contenido principal ===== -->
  <main class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 space-y-6 z-10">

    {{-- ✅ Flash --}}
    @if ($flash)
      <div class="rounded-lg border border-emerald-500/50 bg-emerald-900/40 text-emerald-100 px-4 py-3 text-sm shadow ring-1 ring-emerald-500/30">
        {{ $flash }}
      </div>
    @endif

    {{-- ⚠️ Errores --}}
    @if ($errors->any())
      <div class="rounded-lg border border-rose-500/40 bg-rose-900/30 text-rose-100 px-4 py-3 text-sm shadow">
        <ul class="list-disc pl-5 space-y-0.5">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- 💬 Mensajes dinámicos --}}
    @if (!empty($mensajes))
      <div class="rounded-lg border border-sky-500/40 bg-sky-900/30 text-sky-100 px-4 py-3 text-sm shadow">
        <ul class="list-disc pl-4 space-y-0.5">
          @foreach ($mensajes as $m)
            <li>{{ $m }}</li>
          @endforeach
        </ul>
      </div>
    @endif


    <!-- =========================
         FILA 1: Estado + Dado
         ========================= -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      {{-- Estado --}}
      @include('partials.estado')

      {{-- Tirar dado --}}
      @include('partials.dado')
    </section>


    <!-- =========================
         FILA 2: Agregar colocación + Colocaciones
         ========================= -->
    <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      {{-- Agregar colocación --}}
      @include('partials.colocacion')

      {{-- Tabla colocaciones --}}
      @include('partials.colocaciones')
    </section>


    <!-- =========================
         FILA 3: Jugadores + Placeholder Finalizar
         ========================= -->
    <section class="grid grid-cols-1 lg:grid-cols-5 gap-6">
      {{-- Jugadores ocupa 4/5 --}}
      <div class="lg:col-span-4">
        @include('partials.jugadores')
      </div>

      {{-- Placeholder finalizar --}}
      <div class="lg:col-span-1 rounded-2xl border border-emerald-800/60 bg-emerald-950/40 shadow-inner ring-1 ring-emerald-500/10 flex flex-col justify-center items-center text-center p-6">
        <h3 class="text-sm font-semibold text-emerald-200 mb-3">Finalizar partida</h3>
        <p class="text-xs text-emerald-400/80 mb-4">Una vez que todos los turnos finalicen, podrás cerrar la partida.</p>
        <button disabled class="rounded-md bg-emerald-700/60 px-4 py-2 text-sm text-white font-semibold opacity-60 cursor-not-allowed">
          🏁 Finalizar
        </button>
      </div>
    </section>

  </main>
</div>
@endsection
