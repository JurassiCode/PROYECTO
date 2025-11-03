<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>JurassiDraft – Crear cuenta</title>
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
      <form method="POST" action="{{ route('register.post') }}" novalidate class="space-y-4">
        @csrf

        <h2 class="text-lg font-semibold text-white text-center">Crear cuenta</h2>

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
            value="{{ old('nombre') }}"
            required
            class="block w-full rounded-md border border-white/20 bg-white/10 backdrop-blur-sm px-3 py-1.5 text-sm text-white placeholder:text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>

        <!-- Usuario -->
        <div>
          <label for="nickname" class="block text-xs font-medium text-emerald-100 mb-1">Usuario</label>
          <input
            id="nickname"
            name="nickname"
            type="text"
            autocomplete="username"
            placeholder="Tu usuario"
            value="{{ old('nickname') }}"
            required
            class="block w-full rounded-md border border-white/20 bg-white/10 backdrop-blur-sm px-3 py-1.5 text-sm text-white placeholder:text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        </div>

        <!-- Contraseña -->
        <div>
          <label for="contrasena" class="block text-xs font-medium text-emerald-100 mb-1">Contraseña</label>
          <div class="relative">
            <input
              id="contrasena"
              name="contrasena"
              type="password"
              autocomplete="new-password"
              placeholder="Mínimo 8 caracteres"
              required
              class="block w-full rounded-md border border-white/20 bg-white/10 backdrop-blur-sm px-3 py-1.5 pr-8 text-sm text-white placeholder:text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <button
              type="button"
              id="togglePassword"
              class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-300 hover:text-white">
              <i class="bi bi-eye text-base"></i>
            </button>
          </div>
        </div>

        <!-- Confirmación -->
        <div>
          <label for="contrasena_confirmation" class="block text-xs font-medium text-emerald-100 mb-1">Confirmar contraseña</label>
          <div class="relative">
            <input
              id="contrasena_confirmation"
              name="contrasena_confirmation"
              type="password"
              autocomplete="new-password"
              placeholder="Repetí tu contraseña"
              required
              class="block w-full rounded-md border border-white/20 bg-white/10 backdrop-blur-sm px-3 py-1.5 pr-8 text-sm text-white placeholder:text-gray-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <button
              type="button"
              id="togglePassword2"
              class="absolute inset-y-0 right-0 flex items-center pr-2 text-gray-300 hover:text-white">
              <i class="bi bi-eye text-base"></i>
            </button>
          </div>
        </div>

        <!-- Botón principal -->
        <button
          type="submit"
          class="w-full rounded-md bg-emerald-600 hover:bg-emerald-700 transition-colors py-2 text-sm text-white font-medium shadow focus:ring-2 focus:ring-emerald-500">
          Registrarse
        </button>

        <!-- Link a login -->
        <div class="text-center text-xs text-gray-300 space-y-1">
          <p>¿Ya tenés cuenta?</p>
          <a
            href="{{ route('login') }}"
            class="inline-block rounded-md border border-emerald-500 px-3 py-1 text-emerald-300 hover:bg-emerald-500/10 transition-colors">
            Iniciar sesión
          </a>
        </div>
      </form>
    </div>

    <!-- Volver -->
    <div class="flex justify-center">
      <button
        type="button"
        onclick="history.back()"
        class="mt-4 w-1/2 rounded-lg bg-white/10 border border-white/20 py-2 text-xs font-medium text-white hover:bg-white/20 transition-all focus:ring-2 focus:ring-emerald-400">
        ← Volver atrás
      </button>
    </div>
  </div>

</body>

</html>