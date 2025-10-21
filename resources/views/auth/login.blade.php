<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>JurassiDraft – Iniciar Sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body class="min-h-[100dvh] bg-gray-50 flex flex-col md:items-center md:justify-center px-4 py-6 md:py-0">

    <!-- Logo -->
    <div class="mb-6 mx-auto">
        <img src="{{ asset('images/logojuego_nobg.png') }}" alt="Logo JurassiDraft" class="h-28 w-auto">
    </div>

    <!-- Card -->
    <div class="w-full max-w-md mx-auto">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">

            <form method="POST" action="{{ route('login.post') }}" novalidate class="space-y-4">
                @csrf
                <h1 class="mb-2 text-center text-2xl font-semibold text-gray-900">Iniciar sesión</h1>

                <!-- Mensajes de sesión -->
                @if (session('ok'))
                    <div class="rounded-md border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-700">
                        {{ session('ok') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Usuario -->
                <div>
                    <label for="nickname" class="mb-1 block text-sm font-medium text-gray-700">Usuario</label>
                    <input
                        id="nickname"
                        name="nickname"
                        type="text"
                        inputmode="text"
                        autocomplete="username"
                        placeholder="Tu usuario"
                        value="{{ old('nickname') }}"
                        required
                        autofocus
                        @class([
                            'block w-full rounded-md px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600',
                            'border border-red-300' => $errors->has('nickname'),
                            'border border-gray-300' => !$errors->has('nickname'),
                        ])
                    >
                    @error('nickname')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="contrasena" class="mb-1 block text-sm font-medium text-gray-700">Contraseña</label>
                    <div class="relative">
                        <input
                            id="contrasena"
                            name="contrasena"
                            type="password"
                            autocomplete="current-password"
                            placeholder="Tu contraseña"
                            required
                            @class([
                                'block w-full rounded-md px-3 py-2 pr-10 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600',
                                'border border-red-300' => $errors->has('contrasena'),
                                'border border-gray-300' => !$errors->has('contrasena'),
                            ])
                        >
                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute inset-y-0 right-0 my-auto mr-1 inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-600"
                            aria-label="Mostrar u ocultar contraseña"
                            aria-pressed="false"
                            tabindex="-1"
                        >
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('contrasena')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Acciones -->
                <div class="space-y-3">
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-md bg-emerald-600 px-6 py-3 text-white text-lg shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600"
                    >
                        Iniciar sesión
                    </button>

                    <div class="text-center text-sm text-gray-600 space-y-2">
                        <p class="leading-tight">¿No tenés cuenta?</p>
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex w-full md:w-auto items-center justify-center rounded-md bg-white px-4 py-2 text-emerald-600 text-sm md:text-base shadow-sm border border-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-600"
                        >
                            Registrarse
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Botón volver -->
        <div class="flex justify-center">
            <button
                type="button"
                onclick="history.back()"
                class="mt-6 w-1/2 rounded-xl bg-gradient-to-r from-gray-100 to-gray-200 px-6 py-3 text-lg font-medium text-gray-800 shadow-md transition-all duration-200 hover:from-gray-200 hover:to-gray-300 hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-gray-400 active:scale-95"
            >
                ← Volver atrás
            </button>
        </div>
    </div>

    <!-- Script toggle -->
    <script>
        (() => {
            const btn = document.getElementById('togglePassword');
            const inp = document.getElementById('contrasena'); // ⚠️ corregido el ID
            if (!btn || !inp) return;

            btn.addEventListener('click', () => {
                const isPwd = inp.type === 'password';
                inp.type = isPwd ? 'text' : 'password';

                const icon = btn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('bi-eye', !isPwd);
                    icon.classList.toggle('bi-eye-slash', isPwd);
                }

                const pressed = btn.getAttribute('aria-pressed') === 'true';
                btn.setAttribute('aria-pressed', (!pressed).toString());
            });
        })();
    </script>

</body>
</html>
