<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>@yield('title','JurassiDraft')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Tailwind via Vite --}}
  @vite(['resources/css/app.css','resources/js/app.js'])

  {{-- Bootstrap Icons (solo íconos) --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-800">

  <!-- Navbar -->
  <nav x-data="{ open:false, userMenu:false }" class="bg-white border-b border-gray-200 shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">
        <!-- Brand -->
        <a href="/" class="flex items-center font-bold text-gray-900">
          <img src="/images/logojuego_nobg.png" alt="JurassiDraft Logo" class="h-10 w-10 object-contain mr-2">
          JurassiDraft
        </a>

        <!-- Desktop menu (right) -->
        <div class="hidden lg:flex items-center gap-3">
          @auth
            @php
              $isAdmin = auth()->user()->rol === 'admin';
              $mainActionUrl = $isAdmin ? route('admin.usuarios.index') : route('play');
              $mainActionLabel = $isAdmin ? 'Panel Admin' : 'Jugar';
              $avatarUrl = 'https://www.gravatar.com/avatar/?s=160&d=mp';
              $displayName = auth()->user()->nombre ?? auth()->user()->usuario;
            @endphp

            <!-- Botón principal -->
            <a href="{{ $mainActionUrl }}"
               class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600">
              <i class="bi {{ $isAdmin ? 'bi-speedometer2' : 'bi-play-fill' }} mr-2"></i>{{ $mainActionLabel }}
            </a>

            <!-- Avatar + dropdown -->
            <div class="relative" @click.outside="userMenu=false">
              <button @click="userMenu=!userMenu" class="flex items-center rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-600" aria-label="Menú de usuario">
                <img src="{{ $avatarUrl }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover">
              </button>

              <ul x-cloak x-show="userMenu" x-transition
                  class="absolute right-0 mt-2 w-56 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-lg">
                <li class="px-4 py-3 text-sm text-gray-500">
                  <span class="block font-semibold text-gray-800">{{ $displayName }}</span>
                </li>
                <li><hr class="border-gray-200"></li>
                <li>
                  <a href="{{ $mainActionUrl }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <i class="bi {{ $isAdmin ? 'bi-speedometer2' : 'bi-play-fill' }} mr-2"></i>{{ $mainActionLabel }}
                  </a>
                </li>
                <li>
                  <a href="#" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <i class="bi bi-person-gear mr-2 text-blue-600"></i>Editar perfil
                  </a>
                </li>
                <li>
                  <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="flex w-full items-center px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                      <i class="bi bi-box-arrow-right mr-2 text-red-600"></i>Salir
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          @else
          <a href="{{ route('register') }}"
   class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-emerald-600 border border-emerald-600 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-600">
  Registrarse
</a>

            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600">
              Iniciar sesión
            </a>
          @endauth
        </div>

        <!-- Mobile toggle -->
        <button @click="open = !open"
                class="inline-flex items-center justify-center rounded-md p-2 text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-600 lg:hidden"
                aria-controls="navMain" :aria-expanded="open.toString()" aria-label="Toggle navigation">
          <span class="sr-only">Abrir menú</span>
          <i class="bi" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <div id="navMain" x-cloak x-show="open" x-transition class="lg:hidden border-t border-gray-200 bg-white">
      <div class="mx-auto max-w-7xl px-4 py-3">
        @auth
          @php
            $isAdmin = auth()->user()->rol === 'admin';
            $mainActionUrl = $isAdmin ? route('admin.usuarios.index') : route('play');
            $mainActionLabel = $isAdmin ? 'Panel Admin' : 'Jugar';
            $displayName = auth()->user()->nombre ?? auth()->user()->usuario;
          @endphp

          <div class="mb-2 text-sm font-semibold text-gray-700">{{ $displayName }}</div>

          <a href="{{ $mainActionUrl }}"
             class="block w-full rounded-md px-4 py-2 text-gray-700 hover:bg-gray-50">
            <i class="bi {{ $isAdmin ? 'bi-speedometer2' : 'bi-play-fill' }} mr-2"></i>{{ $mainActionLabel }}
          </a>
          <a href="#"
             class="mt-1 block w-full rounded-md px-4 py-2 text-gray-700 hover:bg-gray-50">
            <i class="bi bi-person-gear mr-2"></i>Editar perfil
          </a>
          <form action="{{ route('logout') }}" method="POST" class="mt-2">
            @csrf
            <button class="w-full rounded-md border border-red-300 px-4 py-2 text-red-700 hover:bg-red-50" type="submit">
              <i class="bi bi-box-arrow-right mr-2 text-red-700"></i>Salir
            </button>
          </form>
        @else
          <div class="flex flex-col gap-2">
            <button class="w-full rounded-md border border-gray-300 px-4 py-2 text-gray-600 hover:bg-gray-50 cursor-not-allowed" disabled>
              Registrarse (🔜🔜)
            </button>
            <a href="{{ route('login') }}"
               class="w-full rounded-md bg-emerald-600 px-4 py-2 text-center text-white hover:bg-emerald-700">
              Iniciar sesión
            </a>
          </div>
        @endauth
      </div>
    </div>
  </nav>

  <!-- MAIN -->
  <main>
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="mt-12 border-t border-gray-200 bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
      <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-4">
        <!-- Copyright -->
        <div class="text-center md:text-left text-gray-500">
          © {{ date('Y') }}
          <strong class="text-gray-900">JurassiDraft</strong>
          <span class="text-gray-500">— Derechos reservados.</span>
        </div>

        <!-- Links -->
        <div class="text-center md:text-right">
          @auth
          <a href="{{ route('play') }}" class="text-emerald-600 hover:text-emerald-700 mr-3">Jugar</a>
          @endauth
          <a href="{{ url('documentacion') }}" class="text-blue-600 hover:text-blue-800 mr-3">Documentación</a>
          <a href="mailto:jurassicodeisbo@gmail.com" class="text-gray-600 hover:text-gray-800">Contacto</a>
        </div>
      </div>

      <div class="mt-3 text-center text-sm text-gray-500">
        Hecho con ❤️ por Seba, Nacho, Joaco y Tomi —
        <a href="https://jurassicode.vercel.app" target="_blank" class="underline underline-offset-4 hover:text-gray-700">
          JurassiCode
        </a>
      </div>
    </div>
  </footer>

  {{-- Alpine para toggles --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
