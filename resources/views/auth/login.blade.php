<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Iniciar sesión</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 flex items-center justify-center">
  <div class="w-full max-w-md px-4">
    <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
      <form method="POST" action="{{ route('login.post') }}" novalidate class="space-y-4">
        @csrf
        <h2 class="mb-2 text-center text-2xl font-semibold text-gray-900">Iniciar sesión</h2>

        @if ($errors->any())
        <div class="rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-700">
          {{ $errors->first() }}
        </div>
        @endif

        <!-- Usuario -->
        <div>
          <label for="usuario" class="mb-1 block text-sm font-medium text-gray-700">Usuario</label>
          <input
            type="text"
            id="usuario"
            name="usuario"
            placeholder="Tu usuario"
            required
            value="{{ old('usuario') }}"
            autofocus
            class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600">
        </div>

        <!-- Contraseña -->
        <div>
          <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Contraseña</label>
          <div class="relative">
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Contraseña"
              required
              class="block w-full rounded-md border border-gray-300 bg-white px-3 py-2 pr-10 text-gray-900 shadow-sm placeholder:text-gray-400 focus:border-emerald-600 focus:outline-none focus:ring-2 focus:ring-emerald-600">
            <button type="button" id="togglePassword"
              class="absolute inset-y-0 right-0 my-auto mr-1 inline-flex h-8 w-8 items-center justify-center rounded-md text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-600"
              tabindex="-1" aria-label="Mostrar/Ocultar contraseña">
              <i class="bi bi-eye"></i>
            </button>
          </div>
        </div>

        <!-- Nota registro -->
        <div class="text-center text-sm text-emerald-700">
          <p class="mb-1">
            (🔜🔜) ¿No tenés cuenta?
            <a href="#"
              class="font-medium text-blue-600 underline underline-offset-4 hover:text-blue-800">
              Registrate
            </a>
          </p>
        </div>

        <!-- Submit -->
        <div>
          <button type="submit"
            class="inline-flex w-full items-center justify-center rounded-md bg-emerald-600 px-4 py-2 text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600">
            Iniciar Sesión
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    const btn = document.getElementById('togglePassword');
    const inp = document.getElementById('password');
    btn.addEventListener('click', () => {
      const isPwd = inp.type === 'password';
      inp.type = isPwd ? 'text' : 'password';
      const icon = btn.querySelector('i');
      icon.classList.toggle('bi-eye', !isPwd);
      icon.classList.toggle('bi-eye-slash', isPwd);
    });
  </script>
</body>

</html>