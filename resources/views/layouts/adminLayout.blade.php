<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>
    @hasSection('title')
    JurassiDraft – @yield('title')
    @else
    JurassiDraft
    @endif
  </title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  {{-- Tailwind via Vite --}}
  @vite(['resources/css/app.css','resources/js/app.js'])

  {{-- Bootstrap Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-emerald-700 via-emerald-800 to-gray-900 text-gray-100 antialiased min-h-screen flex flex-col bg-emerald-900">

  <!-- Navbar -->
  <nav x-data="{ open:false, userMenu:false }"
    class="sticky top-0 z-50 backdrop-blur-md bg-emerald-900/60 border-b border-white/10 shadow-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex h-16 items-center justify-between">

        <!-- Brand -->
        <a href="{{ route('admin.usuarios.index') }}" class="group flex items-center font-bold text-white hover:text-emerald-100 hover:scale-[1.03] transition">
          <img src="{{ asset('images/logojuego_nobg.png') }}" alt="JurassiDraft Logo"
            class="h-13 w-auto me-2">
          <span class="tracking-tight text-lg me-2">JurassiDraft</span> <span class="text-amber-400"> Admin</span>
        </a>

        <!-- Desktop Menu -->
        <div class="hidden lg:flex items-center gap-3">
          @auth
          @php
          $isAdmin = auth()->user()->rol === 'admin';
          $avatarUrl = 'https://www.gravatar.com/avatar/?s=160&d=mp';
          @endphp

          <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2 rounded-md px-4 py-2 text-white font-medium shadow-sm
                     focus:outline-none focus-visible:ring-4 focus-visible:ring-opacity-40 transition-all duration-200 active:scale-[0.98] bg-emerald-600 hover:bg-emerald-700 focus-visible:ring-emerald-500">
            <i class="bi bi-house"></i>Inicio
          </a>

          <!-- Avatar dropdown -->
          <div class="relative" @click.outside="userMenu=false">
            <button @click="userMenu=!userMenu"
              class="flex items-center rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-400 transition">
              <img src="{{ $avatarUrl }}" alt="Avatar"
                class="h-10 w-10 rounded-full object-cover ring-2 ring-white/20 hover:ring-emerald-300 transition">
            </button>

            <!-- Dropdown -->
            <ul x-cloak x-show="userMenu" x-transition
              class="absolute right-0 mt-3 w-56 rounded-xl border border-white/10 bg-gray-900/90 backdrop-blur-md shadow-xl">
              <li class="px-4 py-3 text-sm border-b border-white/10">
                <span class="block font-semibold text-white">{{ auth()->user()->nombre }}</span>
              </li>
              <li>
                <a href="{{ route('home') }}"
                  class="flex items-center px-4 py-2 text-sm text-emerald-300 hover:bg-emerald-600/10 transition">
                  <i class="bi bi-house mr-2"></i>Inicio
                </a>
              </li>
              <li>
                <a href="{{ route('perfil.edit') }}"
                  class="flex items-center px-4 py-2 text-sm text-blue-300 hover:bg-blue-600/10 transition">
                  <i class="bi bi-person mr-2"></i>Ver perfil
                </a>
              </li>
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit"
                    class="flex w-full items-center px-4 py-2 text-sm text-red-300 hover:bg-red-600/10 transition">
                    <i class="bi bi-box-arrow-right mr-2"></i>Salir
                  </button>
                </form>
              </li>
            </ul>
          </div>
          @else
          <a href="{{ route('register') }}"
            class="inline-flex items-center gap-2 rounded-md border border-emerald-300 text-emerald-200
                     px-4 py-2 text-sm font-medium hover:bg-emerald-600/10 hover:border-emerald-400
                     focus-visible:ring-2 focus-visible:ring-emerald-400 transition">
            Registrarse
          </a>
          <a href="{{ route('login') }}"
            class="inline-flex items-center gap-2 rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm
                     hover:bg-emerald-700 focus-visible:ring-2 focus-visible:ring-emerald-500 transition">
            Iniciar sesión
          </a>
          @endauth
        </div>

        <!-- Mobile toggle -->
        <button @click="open=!open"
          class="inline-flex items-center justify-center rounded-md p-2 text-gray-200
                 hover:bg-emerald-600/20 focus-visible:ring-2 focus-visible:ring-emerald-400 lg:hidden">
          <i class="bi" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <div x-cloak x-show="open" x-transition
      class="lg:hidden border-t border-white/10 bg-gray-900/80 backdrop-blur-md shadow-inner">
      <div class="px-4 py-4 space-y-2">
        @auth
        <div class="text-sm font-semibold text-emerald-200 mb-2">{{ auth()->user()->nombre ?? '' }}</div>
        <a href="{{ route('home') }}" class="block rounded-md px-4 py-2 text-sm hover:bg-emerald-600/10">
          <i class="bi bi-house-fill mr-2"></i>Inicio
        </a>
        <a href="{{ route('perfil.edit') }}" class="block rounded-md px-4 py-2 text-sm hover:bg-blue-600/10">
          <i class="bi bi-person mr-2"></i>Ver perfil
        </a>
        <form action="{{ route('logout') }}" method="POST" class="pt-2 border-t border-white/10">
          @csrf
          <button type="submit" class="w-full rounded-md px-4 py-2 text-sm text-red-300 hover:bg-red-600/10">
            <i class="bi bi-box-arrow-right mr-2"></i>Salir
          </button>
        </form>
        @else
        <a href="{{ route('register') }}"
          class="block w-full rounded-md border border-emerald-300 text-emerald-200 px-4 py-2 text-sm font-medium hover:bg-emerald-600/10">
          Registrarse
        </a>
        <a href="{{ route('login') }}"
          class="block w-full rounded-md bg-emerald-600 px-4 py-2 text-center text-white text-sm font-medium hover:bg-emerald-700">
          Iniciar sesión
        </a>
        @endauth
      </div>
    </div>
  </nav>

  <!-- Main -->
  <main class="flex-grow">
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="border-t border-white/10 bg-gray-900/80 backdrop-blur-md">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 text-gray-300 text-sm">
      <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-4">
        <div class="text-center md:text-left">
          © {{ date('Y') }} <strong class="text-white">JurassiDraft</strong> — Derechos reservados.
        </div>
        <div class="text-center md:text-right space-x-4">
          @auth
          <a href="{{ route('lobby') }}"
            class="inline-flex items-center gap-2 rounded-md border border-emerald-400 text-emerald-200 px-3 py-2 hover:bg-emerald-600/10">
            <i class="bi bi-play-fill"></i> Jugar
          </a>
          @endauth
          <a href="{{ url('documentacion') }}" class="hover:text-blue-300">Documentación</a>
          <a href="mailto:jurassicodeisbo@gmail.com" class="hover:text-emerald-200">Contacto</a>
        </div>
      </div>

      <div class="mt-3 text-center text-xs text-gray-400">
        Hecho con ❤️ por <span class="font-medium text-white">Seba, Nacho, Joaco y Tomi</span> —
        <a href="https://jurassicode.vercel.app" target="_blank"
          class="underline underline-offset-4 hover:text-emerald-200">
          JurassiCode
        </a>
      </div>
    </div>
  </footer>

  {{-- AlpineJS --}}
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>