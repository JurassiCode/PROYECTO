<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>JurassiDraft – Iniciar Sesión</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>

<body class="min-h-screen bg-gradient-to-br from-emerald-700 via-emerald-800 to-gray-900 flex items-center justify-center px-6 py-10">

  <div class="w-full max-w-md">
    <!-- Logo -->
    <div class="flex flex-col items-center mb-8 space-y-3">
      <img src="{{ asset('images/logojuego_nobg.png') }}" alt="Logo JurassiDraft" class="h-24 w-auto drop-shadow-lg">
      <h1 class="text-3xl font-extrabold text-white tracking-tight">JurassiDraft</h1>
    </div>

    <!-- Card -->
    <div class="rounded-2xl border border-white/10 bg-white/10 backdrop-blur-md shadow-xl p-8">
      <form method="POST" action="{{ route('login.post') }}" novalidate class="space-y-6">
        @csrf

        <h2 class="text-xl font-semibold text-white text-center mb-4">Iniciar sesión</h2>

        <!-- Mensajes -->
        @if (session('ok'))
        <div class="rounded-md border border-emerald-300 bg-emerald-50/10 text-emerald-200 p-3 text-sm">
          {{ session('ok') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="rounded-md border border-red-300 bg-red-50/10 text-red-300 p-3 text-sm">
          {{ $errors->first() }}
        </div>
        @endif

        <!-- Usuario -->
        <div>
          <label for="nickname" class="mb-1 block text-sm font-medium text-emerald-100">Usuario</label>
          <input
            id="nickname"
            name="nickname"
            type="text"
            autocomplete="username"
            placeholder="Tu usuario"
            value="{{ old('nickname') }}"
            required
            autofocus
            @class([ 'block w-full rounded-lg px-3 py-2 text-white placeholder:text-gray-400 bg-white/10 backdrop-blur-sm border focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent' , 'border-red-400'=> $errors->has('nickname'),
          'border-white/20' => !$errors->has('nickname'),
          ])
          >
          @error('nickname')
          <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
          @enderror
        </div>

        <!-- Contraseña -->
        <div>
          <label for="contrasena" class="mb-1 block text-sm font-medium text-emerald-100">Contraseña</label>
          <div class="relative">
            <input
              id="contrasena"
              name="contrasena"
              type="password"
              autocomplete="current-password"
              placeholder="Tu contraseña"
              required
              @class([ 'block w-full rounded-lg px-3 py-2 pr-10 text-white placeholder:text-gray-400 bg-white/10 backdrop-blur-sm border focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent' , 'border-red-400'=> $errors->has('contrasena'),
            'border-white/20' => !$errors->has('contrasena'),
            ])
            >
            <button
              type="button"
              id="togglePassword"
              class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-300 hover:text-white focus:outline-none">
              <i class="bi bi-eye text-lg"></i>
            </button>
          </div>
          @error('contrasena')
          <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
          @enderror
        </div>

        <!-- Botón principal -->
        <button
          type="submit"
          class="w-full rounded-lg bg-emerald-600 hover:bg-emerald-700 transition-colors px-6 py-3 text-white font-semibold shadow-md focus:ring-2 focus:ring-emerald-500 focus:outline-none">
          Iniciar sesión
        </button>

        <!-- Registro -->
        <div class="text-center text-sm text-gray-300 space-y-2">
          <p>¿No tenés cuenta?</p>
          <a
            href="{{ route('register') }}"
            class="inline-block w-full md:w-auto rounded-lg border border-emerald-500 px-4 py-2 text-emerald-300 hover:bg-emerald-500/10 transition-colors">
            Registrarse
          </a>
        </div>
      </form>
    </div>

    <!-- Botón volver -->
    <div class="flex justify-center">
      <button
        type="button"
        onclick="history.back()"
        class="mt-6 w-1/2 rounded-xl bg-white/10 border border-white/20 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-white/20 transition-all focus:ring-2 focus:ring-emerald-400">
        ← Volver atrás
      </button>
    </div>
  </div>

  <!-- Script toggle -->
  <script>
    const btn = document.getElementById('togglePassword');
    const input = document.getElementById('contrasena');
    btn.addEventListener('click', () => {
      const isHidden = input.type === 'password';
      input.type = isHidden ? 'text' : 'password';
      btn.querySelector('i').classList.toggle('bi-eye');
      btn.querySelector('i').classList.toggle('bi-eye-slash');
    });
  </script>

</body>

</html>