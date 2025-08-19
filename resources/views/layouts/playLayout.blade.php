<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>JurassiDraft – @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Tailwind via Vite --}}
    @vite(['resources/css/app.css','resources/js/app.js'])
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <!-- Navbar -->
    <nav x-data="{ open:false, userMenu:false }"
        class="sticky top-0 z-40 bg-white/90 backdrop-blur supports-backdrop-blur border-b border-gray-200 shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Brand -->
                <a href="{{ route('home') }}"
                    class="group flex items-center font-bold text-gray-900">
                    <img src="{{ asset('images/logojuego_nobg.png') }}"
                        alt="JurassiDraft Logo"
                        class="h-10 w-10 object-contain mr-2 rounded-xl">
                    <span class="tracking-tight">
                        JurassiDraft <span class="text-emerald-600">Juego</span>
                    </span>
                </a>

                <!-- Desktop menu -->
                <div class="hidden lg:flex items-center gap-4">
                    <!-- Inicio con aura -->
                    <a href="{{ route('home') }}"
                        class="inline-flex items-center gap-2 rounded-md bg-white border border-blue-200 text-blue-600
                    px-3 py-2 text-sm font-medium shadow-sm
                    hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700
                    focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-300/40
                    active:scale-[0.98] transition-all duration-200">
                        <i class="bi bi-house-door"></i>
                        Inicio
                    </a>

                    @auth
                    @php
                    $isAdmin = auth()->user()->rol === 'admin';
                    $avatarUrl = 'https://www.gravatar.com/avatar/?s=160&d=mp';
                    $displayName = auth()->user()->nombre ?? auth()->user()->usuario;
                    @endphp

                    <!-- Avatar + dropdown -->
                    <div class="relative" @click.outside="userMenu=false">
                        <button @click="userMenu=!userMenu"
                            class="flex items-center rounded-full focus:outline-none
                             focus-visible:ring-2 focus-visible:ring-emerald-600 transition"
                            aria-label="Menú de usuario" aria-haspopup="true" :aria-expanded="userMenu.toString()">
                            <img src="{{ $avatarUrl }}" alt="Avatar"
                                class="h-10 w-10 rounded-full object-cover ring-1 ring-gray-200 hover:ring-emerald-300 transition">
                        </button>

                        <ul x-cloak x-show="userMenu" x-transition
                            class="absolute right-0 mt-2 w-56 overflow-hidden rounded-xl border border-gray-200
                         bg-white shadow-lg shadow-emerald-900/5">
                            <li class="px-4 py-3 text-sm text-gray-600">
                                <span class="block font-semibold text-gray-800">{{ $displayName }}</span>
                            </li>
                            <li>
                                <hr class="border-gray-200">
                            </li>

                            @if($isAdmin)
                            <li>
                                <a href="{{ route('admin.usuarios.index') }}"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="bi bi-speedometer2 mr-2 text-amber-600"></i> Panel Admin
                                </a>
                            </li>
                            @endif

                            <li>
                                <a href="#"
                                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                    <i class="bi bi-person-gear mr-2 text-blue-600"></i> Editar perfil
                                </a>
                            </li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="bi bi-box-arrow-right mr-2 text-red-600"></i> Salir
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @endauth
                </div>

                <!-- Mobile toggle -->
                <button @click="open = !open"
                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-600
                       hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-600 lg:hidden"
                    aria-controls="navMain" :aria-expanded="open.toString()" aria-label="Toggle navigation">
                    <span class="sr-only">Abrir menú</span>
                    <i class="bi" :class="open ? 'bi-x-lg' : 'bi-list'"></i>
                </button>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="navMain" x-cloak x-show="open" x-transition
            class="lg:hidden border-t border-gray-200 bg-white">
            <div class="mx-auto max-w-7xl px-4 py-3 space-y-2">
                <a href="{{ route('home') }}"
                    class="inline-flex w-full items-center gap-2 rounded-md bg-white border border-blue-200 text-blue-600
                  px-4 py-2 text-sm font-medium shadow-sm
                  hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700
                  focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-300/40
                  active:scale-[0.98] transition-all duration-200">
                    <i class="bi bi-house-door"></i>
                    Inicio
                </a>

                @auth
                @if($isAdmin)
                <a href="{{ route('admin.usuarios.index') }}"
                    class="block w-full rounded-md px-4 py-2 text-gray-700 hover:bg-gray-50">
                    <i class="bi bi-speedometer2 mr-2 text-amber-600"></i> Panel Admin
                </a>
                @endif

                <a href="#"
                    class="block w-full rounded-md px-4 py-2 text-gray-700 hover:bg-gray-50">
                    <i class="bi bi-person-gear mr-2"></i> Editar perfil
                </a>

                <form action="{{ route('logout') }}" method="POST" class="pt-1">
                    @csrf
                    <button class="w-full rounded-md border border-red-300 px-4 py-2 text-red-700 hover:bg-red-50" type="submit">
                        <i class="bi bi-box-arrow-right mr-2 text-red-700"></i> Salir
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </nav>

    <!-- MAIN -->
    <main class="py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-12 border-t border-gray-200 bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-4">
                <div class="text-center md:text-left text-gray-500">
                    © {{ date('Y') }} <strong class="text-gray-900">JurassiDraft</strong> — Panel de juego
                </div>
                <div class="text-center md:text-right">
                    <a href="{{ url('documentacion') }}" class="text-blue-600 hover:text-blue-800 mr-3">Documentación</a>
                    <a href="mailto:jurassicodeisbo@gmail.com" class="text-gray-600 hover:text-gray-800">Soporte</a>
                </div>
            </div>
        </div>
    </footer>

    {{-- Alpine --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>