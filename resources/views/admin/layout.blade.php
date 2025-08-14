<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>JurassiDraft Admin - @yield('title')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">
      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('admin.home') }}">
        <img src="{{ asset('images/logojuego_nobg.png') }}" alt="JurassiDraft Logo" height="40" class="me-2">
        JurassiDraft <small class="text-danger ms-1">Admin</small>
      </a>

      <!-- Mobile Toggle -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"
        aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu -->
      <div id="navMain" class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav ms-auto flex-column flex-lg-row align-items-lg-center gap-2">
          <li class="nav-item">
            <a href="{{ route('home') }}" class="btn btn-primary w-100 w-lg-auto px-3 mb-2 mb-lg-0">
              Volver al inicio
            </a>
          </li>
          <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST" class="d-grid">
              @csrf
              <button class="btn btn-outline-danger w-100 w-lg-auto px-3">
                Salir
              </button>
            </form>
          </li>
        </ul>
      </div>
    </div>
  </nav>


  <div class="container py-4">
    @if (session('ok'))
    <div class="alert alert-success shadow-sm">{{ session('ok') }}</div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    @yield('content')
  </div>

  <footer class="border-top bg-white py-3 mt-4">
    <div class="container text-center text-muted small">
      © {{ date('Y') }} JurassiDraft - Panel de administración
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>