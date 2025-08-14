<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Iniciar sesión</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- Bootstrap 5 --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  {{-- Bootstrap Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light d-flex justify-content-center align-items-center min-vh-100">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-5">
        <div class="card p-4 shadow border-0">
          <form method="POST" action="{{ route('login.post') }}" novalidate>
            @csrf
            <h2 class="mb-4 text-center">Iniciar sesión</h2>

            @if ($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="mb-3">
              <label for="usuario" class="form-label">Usuario</label>
              <input
                type="text"
                class="form-control"
                id="usuario"
                name="usuario"
                placeholder="Tu usuario"
                required
                value="{{ old('usuario') }}"
                autofocus>
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <div class="input-group">
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  name="password"
                  placeholder="Contraseña"
                  required>
                <button type="button" class="btn btn-outline-secondary" id="togglePassword" tabindex="-1">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="text-center mb-3 text-primary">
              <p class="mb-1">
                (🔜🔜) ¿No tenés cuenta? <a href="#">Registrate</a>
              </p>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-success">Iniciar Sesión</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('togglePassword').addEventListener('click', function() {
      const pwdInput = document.getElementById('password');
      const icon = this.querySelector('i');
      pwdInput.type = pwdInput.type === 'password' ? 'text' : 'password';
      icon.classList.toggle('bi-eye');
      icon.classList.toggle('bi-eye-slash');
    });
  </script>
</body>

</html>