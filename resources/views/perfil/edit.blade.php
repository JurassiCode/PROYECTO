    <!doctype html>
    <html lang="es">

    <head>
        <meta charset="utf-8">
        <title>JurassiDraft – Editar perfil</title>
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
                <form method="POST" action="{{ route('perfil.update') }}" class="space-y-4 md:space-y-3">
                    @csrf
                    @method('PUT')

                    <h1 class="text-center text-2xl md:text-xl font-semibold text-gray-900">Editar perfil</h1>

                    @if (session('success'))
                    <div class="rounded-md border border-green-200 bg-green-50 p-3 text-sm text-green-700">
                        <i class="bi bi-check-circle mr-1"></i> {{ session('success') }}
                    </div>
                    @endif

                    <!-- Nombre -->
                    <div>
                        <label for="nombre" class="mb-1 block text-sm font-medium text-gray-700">Nombre</label>
                        <input id="nombre" name="nombre" type="text" inputmode="text" placeholder="Tu nombre"
                            value="{{ old('nombre', $user->nombre) }}" required
                            class="block w-full rounded-md border border-gray-300 px-3 py-2 md:py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-[15px]">
                        @error('nombre')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nickname -->
                    <div>
                        <label for="nickname" class="mb-1 block text-sm font-medium text-gray-700">Usuario / Alias</label>
                        <input id="nickname" name="nickname" type="text" inputmode="text" placeholder="Tu usuario"
                            value="{{ old('nickname', $user->nickname) }}" required
                            class="block w-full rounded-md border border-gray-300 px-3 py-2 md:py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-[15px]">
                        @error('nickname')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nueva contraseña (opcional) -->
                    <div>
                        <label for="contrasena" class="mb-1 block text-sm font-medium text-gray-700">
                            Nueva contraseña (opcional)
                        </label>
                        <div class="relative">
                            <input id="contrasena" name="contrasena" type="password" placeholder="Dejar en blanco para no cambiar"
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

                    <!-- Acciones -->
                    <div class="space-y-3">
                        <button type="submit"
                            class="inline-flex w-full items-center justify-center rounded-md bg-emerald-600 px-6 py-3 md:py-2.5 text-white text-lg md:text-base shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600">
                            <i class="bi bi-save2-fill mr-2"></i> Guardar cambios
                        </button>

                        <div class="pt-2 flex justify-center">
                            <a href="{{ url()->previous() }}"
                                class="inline-flex items-center gap-2 text-sm text-emerald-700 hover:text-emerald-800 transition-colors">
                                <i class="bi bi-arrow-left"></i> Volver
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <script>
            (() => {
                const btn = document.getElementById('togglePassword');
                const inp = document.getElementById('contrasena');
                if (btn && inp) {
                    btn.addEventListener('click', () => {
                        const isPwd = inp.type === 'password';
                        inp.type = isPwd ? 'text' : 'password';
                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.classList.toggle('bi-eye', !isPwd);
                            icon.classList.toggle('bi-eye-slash', isPwd);
                        }
                    });
                }
            })();
        </script>
    </body>

    </html>