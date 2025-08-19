@extends('layouts.publicLayout')

@section('title','Inicio')

@section('content')
<!-- Hero -->
<section class="py-12 bg-white">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-8">

      {{-- Texto y acciones --}}
      <div>
        <h1 class="text-4xl md:text-5xl font-extrabold mb-3">
          ¡Bienvenido a <span class="text-emerald-600">JurassiDraft</span>!
        </h1>
        <p class="text-lg text-gray-600 mb-6">
          Gestioná partidas de <strong>Draftosaurus</strong>: creá salas, administrá jugadores y llevá el puntaje sin papelitos.
          Ideal para clases, torneos o juntadas con amigos.
        </p>

        @auth
        <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2">
          @if(auth()->user()->rol === 'admin')
          <a href="{{ route('admin.usuarios.index') }}"
            class="inline-flex items-center justify-center rounded-md bg-amber-500 px-6 py-3 text-white text-lg shadow-sm hover:bg-amber-600 w-full md:w-auto">
            Panel admin
          </a>
          @endif
          <a href="{{ route('play') }}"
            class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-6 py-3 text-white text-lg shadow-sm hover:bg-emerald-700 w-full md:w-auto">
            Iniciar partida
          </a>


          <form action="{{ route('logout') }}" method="POST" class="w-full md:w-auto">
            @csrf
            <button
              class="inline-flex items-center justify-center rounded-md border border-red-300 px-6 py-3 text-red-700 text-lg shadow-sm hover:bg-red-50 w-full">
              Cerrar sesión
            </button>
          </form>
        </div>

        <div class="mt-3 inline-flex items-center gap-2 px-3 py-2 bg-emerald-50 text-emerald-700 rounded-full shadow-sm">
          <span class="inline-flex items-center justify-center rounded-md bg-blue-600 text-white text-xs px-2 py-0.5">👋</span>
          <span class="font-semibold">
            ¡Hola, {{ auth()->user()->nombre ?? auth()->user()->usuario }}!
          </span>
          <span class="inline-flex items-center justify-center rounded-md bg-gray-200 text-gray-700 text-xs px-2 py-0.5">
            ID: {{ auth()->user()->id_usuario }}
          </span>
        </div>
        @endauth

        @guest
        <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2">
          <a href="{{ route('login') }}"
            class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-6 py-3 text-white text-lg shadow-sm hover:bg-emerald-700 w-full md:w-auto">
            Iniciar sesión
          </a>
          <a href="{{ route('register') }}"
            class="inline-flex items-center justify-center rounded-md bg-white px-6 py-3 text-emerald-600 text-lg shadow-sm border border-emerald-600 hover:bg-emerald-50 w-full md:w-auto">
            Registrarse
          </a>

        </div>
        @endguest
      </div>

      {{-- Imagen --}}
      <div>
        <div class="rounded-2xl border border-gray-200 bg-white shadow-lg overflow-hidden">
          <div class="bg-emerald-50 aspect-video flex items-center justify-center">
            <img src="{{ asset('images/logojuego_nobg.png') }}"
              alt="Vista previa de JurassiDraft"
              class="w-full h-full p-6 object-contain">
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Cómo funciona -->
<section class="py-12 bg-gray-50 border-t border-gray-200">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="mb-8 text-center">
      <h2 class="text-2xl font-semibold mb-1">¿Cómo funciona?</h2>
      <p class="text-gray-600">Cuatro pasos y ya estás jugando.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="h-full">
        <div class="h-full flex flex-row items-start gap-3 p-4 rounded-xl border border-gray-200 bg-white shadow-sm">
          <img src="{{ asset('images/logojuego_nobg.png') }}" alt="Draft" class="rounded w-20 h-20 object-cover">
          <div>
            <h5 class="text-lg font-semibold mb-1">1) Elegí tus dinosaurios</h5>
            <p class="text-gray-600 mb-0">
              Tomá dinos al azar, mantenelos en secreto y seleccioná uno por turno. El draft define tu estrategia.
            </p>
          </div>
        </div>
      </div>

      <div class="h-full">
        <div class="h-full flex flex-row items-start gap-3 p-4 rounded-xl border border-gray-200 bg-white shadow-sm">
          <img src="{{ asset('images/logojuego_nobg.png') }}" alt="Dado" class="rounded w-20 h-20 object-cover">
          <div>
            <h5 class="text-lg font-semibold mb-1">2) Restricción del dado</h5>
            <p class="text-gray-600 mb-0">
              En cada turno, el dado impone una regla de colocación. Adaptate y maximizá tus opciones.
            </p>
          </div>
        </div>
      </div>

      <div class="h-full">
        <div class="h-full flex flex-row items-start gap-3 p-4 rounded-xl border border-gray-200 bg-white shadow-sm">
          <img src="{{ asset('images/logojuego_nobg.png') }}" alt="Parque" class="rounded w-20 h-20 object-cover">
          <div>
            <h5 class="text-lg font-semibold mb-1">3) Colocá los dinosaurios</h5>
            <p class="text-gray-600 mb-0">
              Cada recinto puntúa distinto. Pensá dónde poner cada especie para sumar al máximo.
            </p>
          </div>
        </div>
      </div>

      <div class="h-full">
        <div class="h-full flex flex-row items-start gap-3 p-4 rounded-xl border border-gray-200 bg-white shadow-sm">
          <img src="{{ asset('images/logojuego_nobg.png') }}" alt="Puntaje" class="rounded w-20 h-20 object-cover">
          <div>
            <h5 class="text-lg font-semibold mb-1">4) Sumá puntos y ganá</h5>
            <p class="text-gray-600 mb-0">
              Tras dos rondas, el sistema calcula puntajes por recinto, parejas, T-Rex y río. El mejor parque gana.
            </p>
          </div>
        </div>
      </div>
    </div>

    @guest
    <div class="text-center mt-6">
      <a href="{{ route('register') }}"
        class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-6 py-3 text-white text-lg shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600 transition">
        Crear cuenta y empezar
      </a>
    </div>
    @endguest
  </div>
</section>

{{-- CARACTERÍSTICAS --}}
<section class="py-12 bg-white border-t border-gray-200">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
      <h2 class="text-2xl font-semibold mb-1">Características</h2>
      <p class="text-gray-600">Todo lo que necesitás para una partida prolija.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="h-full">
        <div class="h-full rounded-xl border border-gray-200 bg-white shadow-sm p-5">
          <div class="mb-2 inline-flex items-center justify-center rounded-md bg-emerald-50 text-emerald-700 px-2 py-1">🔐</div>
          <h3 class="text-lg font-semibold mb-1">Usuarios y roles</h3>
          <p class="text-gray-600">Admin y jugador con permisos acotados por rol.</p>
        </div>
      </div>

      <div class="h-full">
        <div class="h-full rounded-xl border border-gray-200 bg-white shadow-sm p-5">
          <div class="mb-2 inline-flex items-center justify-center rounded-md bg-emerald-50 text-emerald-700 px-2 py-1">🧩</div>
          <h3 class="text-lg font-semibold mb-1">Partidas</h3>
          <p class="text-gray-600">Creá, configurá y relanzá partidas con tu grupo.</p>
        </div>
      </div>

      <div class="h-full">
        <div class="h-full rounded-xl border border-gray-200 bg-white shadow-sm p-5">
          <div class="mb-2 inline-flex items-center justify-center rounded-md bg-emerald-50 text-emerald-700 px-2 py-1">📈</div>
          <h3 class="text-lg font-semibold mb-1">Puntuación</h3>
          <p class="text-gray-600">Anotá, validá y compará puntajes por ronda.</p>
        </div>
      </div>
    </div>

    @auth
    <div class="text-center mt-6">
      <a href="{{ route('play') }}"
        class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-6 py-3 text-white text-lg shadow-sm hover:bg-emerald-700">
        Crear nueva partida
      </a>
    </div>
    @endauth
  </div>
</section>

{{-- QUIÉNES SOMOS --}}
<section id="quienes-somos" class="py-12 bg-gray-50 border-t border-gray-200">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-8">
      <div>
        <h2 class="text-2xl font-semibold mb-3">¿Quiénes somos?</h2>
        <p class="text-gray-800">
          <strong>JurassiDraft</strong> es una solución de <strong>JurassiCode</strong>, un equipo de estudiantes
          apasionados por el desarrollo web y los juegos de mesa. Digitalizamos la experiencia para hacerla más
          fluida, organizada y divertida.
        </p>
        <ul class="mt-3 space-y-1">
          <li><strong>Misión:</strong> Simplificar la gestión de puntos y turnos.</li>
          <li><strong>Visión:</strong> Ser la plataforma de referencia para partidas asistidas.</li>
          <li><strong>Valores:</strong> Innovación, claridad, accesibilidad y diversión.</li>
        </ul>
      </div>
      <div class="text-center">
        <div class="inline-block rounded-2xl border border-gray-200 bg-white shadow-lg overflow-hidden">
          <div class="bg-emerald-50 p-6">
            <img src="https://jurassicode.vercel.app/images/logo.png"
              alt="Equipo JurassiCode"
              class="mx-auto max-h-60 object-contain">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection