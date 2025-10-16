<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>JurassiDraft – Crear cuenta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body class="min-h-[100dvh] bg-gray-50 flex flex-col items-center px-4 md:justify-center md:py-0 py-6">

    <!-- Logo -->
    <div class="mb-5 md:mb-4">
        <img src="{{ asset('images/logojuego_nobg.png') }}" alt="Logo JurassiDraft" class="h-28 md:h-12 w-auto">
    </div>

    <!-- Card -->
    <div class="w-full max-w-md">
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm p-6 md:p-5">
            <form method="POST" action="{{ route('register.post') }}" novalidate class="space-y-4 md:space-y-3">
                @csrf
                <h1 class="text-center text-2xl md:text-xl font-semibold text-gray-900">Crear cuenta</h1>

                @if ($errors->any())
                    <div class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="mb-1 block text-sm font-medium text-gray-700">Nombre</label>
                    <input id="nombre" name="nombre" type="text" inputmode="text" placeholder="Tu nombre"
                        value="{{ old('nombre') }}" required
                        class="block w-full rounded-md border border-gray-300 px-3 py-2 md:py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-[15px]">
                    @error('nombre')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nickname -->
                <div>
                    <label for="nickname" class="mb-1 block text-sm font-medium text-gray-700">Usuario</label>
                    <input id="nickname" name="nickname" type="text" inputmode="text" autocomplete="username"
                        placeholder="Tu usuario" value="{{ old('nickname') }}" required
                        class="block w-full rounded-md border border-gray-300 px-3 py-2 md:py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-[15px]">
                    @error('nickname')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="contrasena" class="mb-1 block text-sm font-medium text-gray-700">Contraseña</label>
                    <div class="relative">
                        <input id="contrasena" name="contrasena" type="password" autocomplete="new-password"
                            placeholder="Mínimo 8 caracteres" required
                            class="block w-full rounded-md border border-gray-300 px-3 py-2 md:py-2 pr-10 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-[15px]">
                        <button type="button" id="togglePassword"
                            class="absolute inset-y-0 right-0 my-auto mr-1 inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-600"
                            aria-label="Mostrar u ocultar contraseña" aria-pressed="false" tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('contrasena')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirmación -->
                <div>
                    <label for="contrasena_confirmation" class="mb-1 block text-sm font-medium text-gray-700">
                        Confirmar contraseña
                    </label>
                    <div class="relative">
                        <input id="contrasena_confirmation" name="contrasena_confirmation" type="password"
                            autocomplete="new-password" placeholder="Repetí tu contraseña" required
                            class="block w-full rounded-md border border-gray-300 px-3 py-2 md:py-2 pr-10 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-[15px]">
                        <button type="button" id="togglePassword2"
                            class="absolute inset-y-0 right-0 my-auto mr-1 inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-600"
                            aria-label="Mostrar u ocultar confirmación" aria-pressed="false" tabindex="-1">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('contrasena_confirmation')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Acciones -->
                <div class="space-y-3">
                    <button type="submit"
                        class="inline-flex w-full items-center justify-center rounded-md bg-emerald-600 px-6 py-3 md:py-2.5 text-white text-lg md:text-base shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                        Registrarse
                    </button>

                    <div class="text-center text-sm text-gray-600 space-y-2">
                        <p class="leading-tight">¿Ya tenés cuenta?</p>
                        <a href="{{ route('login') }}"
                            class="inline-flex w-full md:w-auto items-center justify-center rounded-md bg-white px-4 py-2 md:py-2.5 text-emerald-600 text-sm md:text-base shadow-sm border border-emerald-600 hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                            Iniciar sesión
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        (() => {
            const mkToggle = (btnId, inpId) => {
                const btn = document.getElementById(btnId);
                const inp = document.getElementById(inpId);
                if (!btn || !inp) return;
                btn.addEventListener('click', () => {
                    const isPwd = inp.type === 'password';
                    inp.type = isPwd ? 'text' : 'password';
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.toggle('bi-eye', !isPwd);
                        icon.classList.toggle('bi-eye-slash', isPwd);
                    }
                    btn.setAttribute('aria-pressed', (!isPwd).toString());
                });
            };
            mkToggle('togglePassword', 'contrasena');
            mkToggle('togglePassword2', 'contrasena_confirmation');
        })();
    </script>
</body>

</html>
