<!doctype html>
<html lang="{{ app()->getLocale() }}">

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

  {{-- Tailwind, Alpine.js y bootstrap icons via Vite --}}
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-emerald-700 via-emerald-800 to-gray-900 text-gray-100 antialiased min-h-screen flex flex-col bg-emerald-900">

  <!-- Navbar -->
  <nav x-data="{ open:false, userMenu:false }"
    class="sticky top-0 z-50 backdrop-blur-md bg-emerald-900/60 border-b border-white/10 shadow-md">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 flex h-16 items-center justify-between">

      <!-- Brand -->
      <a href="{{ route('admin.usuarios.index') }}"
        class="group flex items-center font-bold text-white hover:text-emerald-100 hover:scale-[1.03] transition">
        <img src="{{ asset('images/logojuego_nobg.png') }}" alt="JurassiDraft Logo" class="h-13 w-auto me-2">
        <span class="tracking-tight text-lg me-2">JurassiDraft</span>
        <span class="text-amber-400">{{ __('Admin') }}</span>
      </a>

      <!-- Desktop Menu -->
      <div class="hidden lg:flex items-center gap-3">
        @auth
          @php
            $avatarUrl = asset('images/avatar.jpg');
          @endphp

          <a href="{{ route('home') }}"
            class="inline-flex items-center gap-2 rounded-md px-4 py-2 text-white font-medium shadow-sm
              bg-emerald-600 hover:bg-emerald-700 focus-visible:ring-4 focus-visible:ring-emerald-500 transition">
            <i class="bi bi-house"></i>{{ __('Home') }}
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
                  <i class="bi bi-house mr-2"></i>{{ __('Home') }}
                </a>
              </li>
              <li>
                <a href="{{ route('perfil.show') }}"
                  class="flex items-center px-4 py-2 text-sm text-blue-300 hover:bg-blue-600/10 transition">
                  <i class="bi bi-person mr-2"></i>{{ __('View profile') }}
                </a>
              </li>
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit"
                    class="flex w-full items-center px-4 py-2 text-sm text-red-300 hover:bg-red-600/10 transition">
                    <i class="bi bi-box-arrow-right mr-2"></i>{{ __('Logout') }}
                  </button>
                </form>
              </li>
            </ul>
          </div>
        @else
          <a href="{{ route('register') }}"
            class="inline-flex items-center gap-2 rounded-md border border-emerald-300 text-emerald-200
              px-4 py-2 text-sm font-medium hover:bg-emerald-600/10 hover:border-emerald-400 transition">
            {{ __('Register') }}
          </a>
          <a href="{{ route('login') }}"
            class="inline-flex items-center gap-2 rounded-md bg-emerald-600 px-4 py-2 text-sm font-medium text-white shadow-sm
              hover:bg-emerald-700 focus-visible:ring-2 focus-visible:ring-emerald-500 transition">
            {{ __('Login') }}
          </a>
        @endauth

        <!-- Language Switcher -->
        <div x-data="{ open: false }" class="relative ml-4">
          <button @click="open = !open"
            class="flex items-center gap-2 text-2xl text-gray-300 hover:text-emerald-300 transition select-none hover:scale-105 active:scale-95 group">
            <i class="bi bi-translate text-xl transition-transform group-hover:rotate-6"></i>
            @if(app()->getLocale() === 'es')
              <span class="hidden sm:inline text-2xl group-hover:rotate-3 transition-transform">🇺🇾</span>
            @else
              <span class="hidden sm:inline text-2xl group-hover:rotate-3 transition-transform">🇬🇧</span>
            @endif
            <i class="bi transition-transform duration-200"
              :class="open ? 'bi-chevron-up rotate-180 text-emerald-300' : 'bi-chevron-down text-gray-400 group-hover:text-emerald-300'"></i>
          </button>

          <div x-show="open" @click.away="open = false"
            x-transition.opacity.scale.origin-top-right
            class="absolute right-0 mt-2 w-40 rounded-xl border border-emerald-400/30 bg-gray-900/95 backdrop-blur-md shadow-lg shadow-emerald-900/40 ring-1 ring-black/10 overflow-hidden">
            <ul class="py-2 text-sm text-gray-200">
              <li>
                <a href="{{ route('lang.switch', 'es') }}"
                  class="flex items-center gap-3 px-4 py-2 rounded-md transition-all duration-200
                    hover:bg-emerald-800/40 hover:text-emerald-200 hover:pl-5
                    {{ app()->getLocale() === 'es' ? 'bg-emerald-900/60 font-semibold text-white ring-1 ring-emerald-400/40' : '' }}">
                  🇺🇾 <span>Español</span>
                  @if(app()->getLocale() === 'es')
                    <i class="bi bi-check-lg ml-auto text-emerald-300"></i>
                  @endif
                </a>
              </li>
              <li>
                <a href="{{ route('lang.switch', 'en') }}"
                  class="flex items-center gap-3 px-4 py-2 rounded-md transition-all duration-200
                    hover:bg-emerald-800/40 hover:text-emerald-200 hover:pl-5
                    {{ app()->getLocale() === 'en' ? 'bg-emerald-900/60 font-semibold text-white ring-1 ring-emerald-400/40' : '' }}">
                  🇬🇧 <span>English</span>
                  @if(app()->getLocale() === 'en')
                    <i class="bi bi-check-lg ml-auto text-emerald-300"></i>
                  @endif
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Mobile toggle -->
      <button @click="open=!open"
        class="inline-flex items-center justify-center rounded-md p-2 text-gray-200
          hover:bg-emerald-600/20 focus-visible:ring-2 focus-visible:ring-emerald-400 lg:hidden">
        <i class="bi" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
      </button>
    </div>

    <!-- ✅ Nuevo menú móvil adaptado -->
    <div x-cloak x-show="open" x-transition
      class="lg:hidden border-t border-white/10 bg-gradient-to-b from-gray-900/90 to-emerald-950/80 backdrop-blur-md shadow-inner">
      <div class="px-5 py-6 space-y-4">
        @auth
          <!-- Acciones principales -->
          <div class="space-y-1">
            <a href="{{ route('home') }}"
              class="flex items-center justify-center gap-2 w-full rounded-md bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 font-semibold text-sm transition">
              <i class="bi bi-house"></i> {{ __('Home') }}
            </a>

            <a href="{{ route('perfil.show') }}"
              class="flex items-center justify-center gap-2 w-full rounded-md bg-emerald-900/40 hover:bg-emerald-800/60 border border-emerald-700 text-emerald-200 px-4 py-2 font-medium text-sm transition">
              <i class="bi bi-person"></i> {{ __('View profile') }}
            </a>

            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit"
                class="flex items-center justify-center gap-2 w-full rounded-md border border-red-500/50 text-red-300 hover:bg-red-600/10 px-4 py-2 font-medium text-sm transition">
                <i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}
              </button>
            </form>
          </div>
        @else
          <!-- Invitado -->
          <div class="space-y-3">
            <a href="{{ route('register') }}"
              class="flex items-center justify-center gap-2 w-full rounded-md border border-emerald-400 text-emerald-300 hover:bg-emerald-600/10 px-4 py-2 font-semibold text-sm transition">
              <i class="bi bi-person-plus"></i> {{ __('Register') }}
            </a>

            <a href="{{ route('login') }}"
              class="flex items-center justify-center gap-2 w-full rounded-md bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 font-semibold text-sm shadow transition">
              <i class="bi bi-box-arrow-in-right"></i> {{ __('Login') }}
            </a>
          </div>
        @endauth

        <!-- 🌐 Language Switcher (mobile) -->
        <div class="pt-5 border-t border-white/10 flex justify-center items-center gap-3">
          <a href="{{ route('lang.switch', 'es') }}"
            class="flex items-center gap-1 rounded-full px-3 py-1.5 text-sm transition
              {{ app()->getLocale() === 'es'
                  ? 'bg-emerald-700 text-white font-semibold shadow'
                  : 'text-gray-400 hover:text-emerald-300 hover:bg-emerald-800/40' }}">
            🇺🇾 <span>ES</span>
          </a>

          <a href="{{ route('lang.switch', 'en') }}"
            class="flex items-center gap-1 rounded-full px-3 py-1.5 text-sm transition
              {{ app()->getLocale() === 'en'
                  ? 'bg-emerald-700 text-white font-semibold shadow'
                  : 'text-gray-400 hover:text-emerald-300 hover:bg-emerald-800/40' }}">
            🇬🇧 <span>EN</span>
          </a>
        </div>
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
          © {{ date('Y') }} <strong class="text-white">JurassiDraft</strong> — {{ __('All rights reserved') }}.
        </div>
        <div class="text-center md:text-right space-x-4">
          @auth
            <a href="{{ route('lobby') }}"
              class="inline-flex items-center gap-2 rounded-md border border-emerald-400 text-emerald-200 px-3 py-2 hover:bg-emerald-600/10">
              <i class="bi bi-play-fill"></i> {{ __('Play') }}
            </a>
          @endauth
          <a href="{{ url('documentacion') }}" class="hover:text-blue-300">{{ __('Documentation') }}</a>
          <a href="mailto:jurassicodeisbo@gmail.com" class="hover:text-emerald-200">{{ __('Contact') }}</a>
        </div>
      </div>

      <div class="mt-3 text-center text-xs text-gray-400">
        {{ __('Made with love by') }} <span class="font-medium text-white">Seba, Nacho, Joaco & Tomi</span> —
        <a href="https://jurassicode.vercel.app" target="_blank"
          class="underline underline-offset-4 hover:text-emerald-200">
          JurassiCode
        </a>
      </div>
    </div>
  </footer>

</body>
</html>
