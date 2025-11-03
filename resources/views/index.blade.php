@extends('layouts.publicLayout')

@section('title', 'Inicio')

@section('content')
<!-- HERO -->
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-800 to-gray-900 text-white py-20">
  <!--  Patrón de dinosaurios -->
  <div class="absolute inset-0 opacity-10 bg-repeat" style="background-image: url(/images/pattern_dinos.svg);"></div>

  <!--  Contenido principal -->
  <div class="relative mx-auto max-w-7xl px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
    
    {{--  Columna de texto --}}
    <div class="space-y-8">
      <div>
        <h1 class="text-5xl md:text-6xl font-extrabold leading-tight drop-shadow-sm">
          ¡Bienvenido a <span class="text-emerald-300">JurassiDraft</span>!
        </h1>
        <p class="mt-4 text-lg text-emerald-100 max-w-lg">
          La forma moderna de jugar <strong>Draftosaurus</strong>: gestioná tus partidas, turnos y puntajes 
          de manera digital, rápida y divertida.
        </p>
      </div>

      {{--  Botones principales --}}
      <div class="flex flex-wrap gap-3">
        {{-- 🔗 Ver Ranking (visible para todos) --}}
        <a href="{{ route('ranking.index') }}"
          class="flex items-center gap-2 rounded-md border border-amber-400 text-amber-400 hover:bg-amber-500/10 px-6 py-3 font-semibold transition">
          <i class="bi bi-trophy"></i> Ver Ranking
        </a>

        @auth
          @if (auth()->user()->rol === 'admin')
          <a href="{{ route('admin.usuarios.index') }}"
            class="flex items-center gap-2 rounded-md bg-amber-500 hover:bg-amber-600 px-6 py-3 font-semibold shadow-md transition">
            <i class="bi bi-speedometer2"></i> Panel Admin
          </a>
          @endif

          <a href="{{ route('lobby') }}"
            class="flex items-center gap-2 rounded-md bg-emerald-500 hover:bg-emerald-600 px-6 py-3 font-semibold shadow-md transition">
            <i class="bi bi-play-fill"></i> Iniciar partida
          </a>

          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
              class="flex items-center gap-2 rounded-md border border-red-400 text-red-400 hover:bg-red-600/10 px-6 py-3 font-semibold transition">
              <i class="bi bi-box-arrow-right"></i> Salir
            </button>
          </form>

          <p class="w-full mt-4 text-sm text-emerald-200">
            👋 Hola, <span class="font-semibold">{{ auth()->user()->nombre ?? auth()->user()->usuario }}</span> · ID {{ auth()->user()->id }}
          </p>
        @endauth

        @guest
          <a href="{{ route('login') }}"
            class="flex items-center gap-2 rounded-md bg-emerald-600 hover:bg-emerald-700 px-6 py-3 font-semibold shadow-md transition">
            <i class="bi bi-box-arrow-in-right"></i> Iniciar sesión
          </a>

          <a href="{{ route('register') }}"
            class="flex items-center gap-2 rounded-md border border-emerald-400 text-emerald-400 hover:bg-emerald-500/10 px-6 py-3 font-semibold transition">
            <i class="bi bi-person-plus"></i> Registrarse
          </a>
        @endguest
      </div>
    </div>

    {{--  Imagen lateral --}}
    <div class="relative">
      <div class="rounded-2xl bg-white/10 border border-white/20 backdrop-blur-md shadow-xl overflow-hidden">
        <img src="{{ asset('images/logojuego_nobg.png') }}" alt="JurassiDraft Logo"
          class="object-contain w-full h-full p-10">
      </div>

      <div class="absolute -z-10 -top-12 -right-12 w-80 h-80 bg-emerald-500/30 rounded-full blur-3xl"></div>
    </div>
  </div>
</section>



<!-- CÓMO FUNCIONA -->
<section class="py-20 bg-gray-50 border-t border-gray-200">
  <div class="mx-auto max-w-7xl px-6 lg:px-8 text-center">
    <h2 class="text-4xl font-bold text-gray-900 mb-3">¿Cómo funciona?</h2>
    <p class="text-gray-600 mb-12">Aprendé a jugar en solo cuatro pasos.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
      @foreach ([
      ['🦕','Elegí tus dinosaurios','Seleccioná uno por turno y planeá tu estrategia.'],
      ['🎲','Cargá la restricción del dado','Limitando las colocaciones.'],
      ['🏞️','Colocá en los recintos','Cada tipo puntúa distinto: pensá antes de actuar.'],
      ['🏆','Sumá puntos y ganá','Al final de la segunda ronda, el sistema calcula el ganador.']
      ] as [$icon,$title,$desc])
      <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 hover:shadow-md hover:-translate-y-1 transition">
        <div class="text-4xl mb-4">{{ $icon }}</div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
        <p class="text-gray-600 text-sm">{{ $desc }}</p>
      </div>
      @endforeach
    </div>ƒ

    @guest
    <a href="{{ route('register') }}"
      class="mt-12 inline-flex items-center gap-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 text-lg font-semibold shadow transition">
      <i class="bi bi-person-plus"></i> Crear cuenta y jugar gratis
    </a>
    @endguest
  </div>
</section>

<!-- CARACTERÍSTICAS -->
<section class="py-20 bg-white border-t border-gray-200">
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="text-center mb-12">
      <h2 class="text-4xl font-bold text-gray-900 mb-3">Características principales</h2>
      <p class="text-gray-600">Todo lo que necesitás para disfrutar una partida digital.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      @foreach ([
      ['🔐','Gestión de usuarios y roles','Jugadores y administradores con permisos definidos.'],
      ['🧩','Partidas configurables','Crea, edita y relanzá partidas en cualquier momento.'],
      ['📊','Puntuación automatizada','El sistema valida jugadas y calcula los puntos al instante.']
      ] as [$icon,$title,$desc])
      <div class="rounded-2xl bg-gray-50 border border-gray-200 p-8 shadow-sm hover:shadow-md transition">
        <div class="text-4xl mb-3">{{ $icon }}</div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $title }}</h3>
        <p class="text-gray-600">{{ $desc }}</p>
      </div>
      @endforeach
    </div>

    @auth
    <div class="text-center mt-12">
      <a href="{{ route('lobby') }}"
        class="inline-flex items-center gap-2 rounded-md bg-emerald-600 hover:bg-emerald-700 px-6 py-3 text-white text-lg font-semibold shadow transition">
        <i class="bi bi-controller"></i> Crear nueva partida
      </a>
    </div>
    @endauth
  </div>
</section>

<!-- EQUIPO -->
<section class="py-20 bg-gray-50 border-t border-gray-200">
  <div class="mx-auto max-w-7xl px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 items-center gap-12">
    <div>
      <h2 class="text-4xl font-bold mb-4 text-gray-900">¿Quiénes somos?</h2>
      <p class="text-gray-700 mb-4 leading-relaxed">
        <strong>JurassiDraft</strong> es una creación del equipo <strong>JurassiCode</strong>, un grupo de estudiantes apasionados por la programación, la innovación y los juegos de mesa.
      </p>
      <ul class="space-y-2 text-gray-700">
        <li><strong>Misión:</strong> Hacer que el seguimiento de partidas sea simple y divertido.</li>
        <li><strong>Visión:</strong> Convertirse en la herramienta digital de referencia para Draftosaurus.</li>
        <li><strong>Valores:</strong> Innovación, claridad, accesibilidad y pasión por el juego.</li>
      </ul>
    </div>

    <div class="text-center">
      <div class="inline-block rounded-2xl bg-white shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-emerald-50 p-6">
          <img src="https://jurassicode.vercel.app/images/logo.png" alt="Equipo JurassiCode"
            class="mx-auto max-h-64 object-contain">
        </div>
      </div>
    </div>
  </div>
</section>
@endsection