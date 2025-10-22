<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>JurassiDraft – Editar perfil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-700 via-emerald-800 to-gray-900 flex items-center justify-center px-4 py-4">

    <div class="w-full max-w-sm space-y-4">
        <!-- Logo -->
        <div class="flex flex-col items-center space-y-2">
            <img src="{{ asset('images/logojuego_nobg.png') }}" alt="Logo JurassiDraft" class="h-20 w-auto drop-shadow-lg">
            <h1 class="text-2xl font-extrabold text-white tracking-tight">JurassiDraft</h1>
        </div>

        <!-- Card -->
        <div class="rounded-xl border border-white/10 bg-white/10 backdrop-blur-md shadow-lg p-5">
            <form method="POST" action="{{ route('perfil.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <h2 class="text-lg font-semibold text-white text-center">Editar perfil</h2>

                @if (session('success'))
                <div class="rounded-md border border-emerald-400 bg-emerald-50/10 text-emerald-300 p-2 text-xs text-center">
                    <i class="bi bi-check-circle mr-1"></i> {{ session('success') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="rounded-md border border-red-400 bg-red-50/10 text-red-300 p-2 text-xs text-center">
                    {{ $errors->first() }}
                </div>
                @endif

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-xs font-medium text-emerald-100 mb-1">Nombre</label>
                    <input
                        id="nombre"
                        name="nombre"
                        type="text"
                        placeholder="Tu nombre"
                        value="{{ old('nombre', $user->nombre) }}"
                        required
                        class="block w-full rounded-md border border-white/20 bg-white/10 backdrop-blur-sm px-3 py-1.5 text-sm text-white placeholder:text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Usuario -->
                <div>
                    <label for="nickname" class="block text-xs font-medium text-emerald-100 mb-1">Usuario / Alias</label>
                    <input
                        id="nickname"
                        name="nickname"
                        type="text"
                        placeholder="Tu usuario"
                        value="{{ old('nickname', $user->nickname) }}"
                        required
                        class="block w-full rounded-md border border-white/20 bg-white/10 backdrop-blur-sm px-3 py-1.5 text-sm text-white placeholder:text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Contraseña (opcional) -->
                <div>
                    <label for="contrasena" class="block text-xs font-medium text-emerald-100 mb-1">
                        Nueva contraseña (opcional)
                    </label>
                    <div class="relative">
                        <input
                            id="contrasena"
                            name="contrasena"
                            type="password"
                            placeholder="Dejar en blanco para no cambiar"
                            class="block w-full rounded-md border border-white/20 bg-white/10 backdrop-blur-sm px-3 py-1.5 pr-8 text-sm text-white placeholder:text-white-600 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-300 hover:text-white">
                            <i class="bi bi-eye text-base"></i>
                        </button>
                    </div>
                </div>

                <!-- Botón principal -->
                <button
                    type="submit"
                    class="w-full rounded-md bg-emerald-600 hover:bg-emerald-700 transition-colors py-2 text-sm text-white font-medium shadow focus:ring-2 focus:ring-emerald-500">
                    <i class="bi bi-save2-fill mr-1"></i> Guardar cambios
                </button>

                <!-- Link volver -->
                <div class="text-center text-xs text-gray-300 space-y-1">
                    <p>¿Querés volver sin guardar?</p>
                    <a
                        href="{{ url()->previous() }}"
                        class="inline-block rounded-md border border-emerald-500 px-3 py-1 text-emerald-300 hover:bg-emerald-500/10 transition-colors">
                        ← Volver atrás
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Script -->
    <script>
        const btn = document.getElementById('togglePassword');
        const inp = document.getElementById('contrasena');
        if (btn && inp) {
            btn.addEventListener('click', () => {
                const isPwd = inp.type === 'password';
                inp.type = isPwd ? 'text' : 'password';
                const icon = btn.querySelector('i');
                icon.classList.toggle('bi-eye');
                icon.classList.toggle('bi-eye-slash');
            });
        }
    </script>

</body>

</html>