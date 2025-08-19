<!doctype html>
<html lang="es" x-data="{ open:false }" class="h-full">

<head>
  <meta charset="utf-8" />
  <title>JurassiDraft Admin – @yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  @vite(['resources/css/app.css','resources/js/app.js'])
  <!-- Bootstrap Icons (solo íconos) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body class="min-h-full bg-gray-50 text-gray-800">
  <!-- NAVBAR -->
  <nav class="bg-white border-b border-gray-200 shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <!-- Brand -->
        <a href="{{ route('admin.home') }}" class="flex items-center font-bold">
          <img src="{{ asset('images/logojuego_nobg.png') }}" alt="JurassiDraft Logo" class="mr-2 h-10 w-10 object-contain">
          <span class="text-gray-900">JurassiDraft</span>
          <span class="ml-2 text-sm font-semibold text-red-600">Admin</span>
        </a>

        <!-- Desktop actions -->
        <div class="hidden lg:flex items-center gap-3">
          <a href="{{ route('home') }}"
            class="inline-flex items-center justify-center rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            Volver al inicio
          </a>

          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button
              class="inline-flex items-center justify-center rounded-md border border-red-300 px-4 py-2 text-red-700 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
              Salir
            </button>
          </form>
        </div>

        <!-- Mobile toggle -->
        <button
          @click="open = !open"
          class="inline-flex items-center justify-center rounded-md p-2 text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 lg:hidden"
          aria-controls="navMain" :aria-expanded="open.toString()" aria-label="Toggle navigation">
          <span class="sr-only">Abrir menú</span>
          <i class="bi" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <div id="navMain" x-cloak x-show="open" x-transition class="border-t border-gray-200 bg-white lg:hidden">
      <div class="mx-auto max-w-7xl space-y-3 px-4 py-3">
        <a href="{{ route('home') }}"
          class="block w-full rounded-md bg-blue-600 px-4 py-2 text-center text-white hover:bg-blue-700">
          Volver al inicio
        </a>

        <form action="{{ route('logout') }}" method="POST" class="w-full">
          @csrf
          <button
            class="block w-full rounded-md border border-red-300 px-4 py-2 text-center text-red-700 hover:bg-red-50">
            Salir
          </button>
        </form>
      </div>
    </div>
  </nav>

  <!-- MAIN -->
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
    @if (session('ok'))
    <div class="mb-4 rounded-md border border-green-200 bg-green-50 p-3 text-green-800 shadow-sm">
      {{ session('ok') }}
    </div>
    @endif

    @if (session('error'))
    <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-red-800 shadow-sm">
      {{ session('error') }}
    </div>
    @endif

    {{-- CONTENIDO DE CADA PÁGINA --}}
    @yield('content')
  </main>

  <!-- FOOTER -->
  <footer class="mt-6 border-t border-gray-200 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 text-center text-sm text-gray-500">
      © {{ date('Y') }} JurassiDraft - Panel de administración
    </div>
  </footer>

  <!-- Alpine (toggle menú) -->
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>