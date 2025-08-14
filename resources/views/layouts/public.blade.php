<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>@yield('title','JurassiDraft')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

  <!-- Navbar  -->
  <nav class="navbar navbar-expand-lg bg-white border-bottom shadow-sm">
    <div class="container">
      <!-- Logo -->
      <a class="navbar-brand d-flex align-items-center fw-bold" href="/">
        <img src="/images/logojuego_nobg.png" alt="JurassiDraft Logo" height="50" class="me-2">
        JurassiDraft
      </a>

      <!-- Mobile Toggle -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain"
        aria-controls="navMain" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menu -->
      <div id="navMain" class="collapse navbar-collapse justify-content-end justify-content-md-around">
        <ul class="navbar-nav ms-auto align-items-center gap-2">
          @auth
          @php
          $isAdmin = auth()->user()->rol === 'admin';
          $mainActionUrl = $isAdmin ? route('admin.usuarios.index') : route('play');
          $mainActionLabel = $isAdmin ? 'Panel Admin' : 'Jugar';
          $avatarUrl = 'https://www.gravatar.com/avatar/?s=160&d=mp';
          $displayName = auth()->user()->nombre ?? auth()->user()->usuario;
          @endphp

          <!-- HAMBURGUESA MOBILE -->
          <li class="nav-item d-lg-none">
            <span class="nav-link fw-semibold">{{ $displayName }}</span>
          </li>
          <li class="nav-item d-lg-none">
            <a class="nav-link" href="{{ $mainActionUrl }}">
              <i class="bi {{ $isAdmin ? 'bi-speedometer2' : 'bi-play-fill' }} me-2"></i>{{ $mainActionLabel }}
            </a>
          </li>
          <li class="nav-item d-lg-none">
            <a class="nav-link" href="#"><i class="bi bi-person-gear me-2"></i>Editar perfil</a>
          </li>
          <li class="nav-item d-lg-none">
            <form action="{{ route('logout') }}" method="POST" class="d-grid my-2">
              @csrf
              <button class="btn btn-outline-danger" type="submit">
                <i class="bi bi-box-arrow-right me-2 text-danger"></i>Salir
              </button>
            </form>
          </li>

          <!-- DESKTOP AVATAR + DROPDOWN -->
          <li class="nav-item dropdown d-none d-lg-block">
            <button class="btn p-0 border-0 bg-transparent dropdown-toggle d-flex align-items-center"
              data-bs-toggle="dropdown" aria-expanded="false" aria-label="Menú de usuario">
              <img src="{{ $avatarUrl }}" alt="Avatar" width="40" height="40"
                class="rounded-circle" style="object-fit:cover;">
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
              <li class="px-3 py-2 small text-muted">
                <span class="fw-semibold">{{ $displayName }}</span>
              </li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item" href="{{ $mainActionUrl }}">
                  <i class="bi {{ $isAdmin ? 'bi-speedometer2' : 'bi-play-fill' }} me-2"></i>{{ $mainActionLabel }}
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#"><i class="bi bi-person-gear me-2 text-primary"></i>Editar perfil</a>
              </li>
              <li>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                  @csrf
                  <button class="dropdown-item" type="submit">
                    <i class="bi bi-box-arrow-right me-2 text-danger"></i>Salir
                  </button>
                </form>
              </li>
            </ul>
          </li>
          @else
          <div class="nav-item d-flex flex-row gap-2">
            <li class="nav-item">
              <button class="btn btn-outline-secondary px-3" disabled>
                Registrarse (🔜🔜)
              </button>
            </li>
            <li class="nav-item">
              <a href="{{ route('login') }}" class="btn btn-success px-3">Iniciar sesión</a>
            </li>
          </div>
          @endauth
        </ul>
      </div>
    </div>
  </nav>

  <!-- MAIN y contenido -->
  <main>
    @yield('content')
  </main>

  <!-- Footer -->
  <footer class="border-top bg-white shadow-sm mt-5">
    <div class="container py-4">
      <div class="row align-items-center">
        <!-- Copyright -->
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0 text-muted">
          © {{ date('Y') }}
          <strong class="text-dark">JurassiDraft</strong>
          <span class="text-secondary">— Derechos reservados.</span>
        </div>

        <!-- Links -->
        <div class="col-md-6 text-center text-md-end">
          @auth
          <a href="{{ route('play') }}" class="text-decoration-none text-success me-3">Jugar</a>
          @endauth
          <a href="{{ url('documentacion') }}" class="text-decoration-none text-info-muted me-3">Documentación</a>
          <a href="mailto:jurassicodeisbo@gmail.com" class="text-decoration-none text-muted">Contacto</a>
        </div>
      </div>

      <!-- FOOTER final -->
      <div class="text-center mt-3 small text-muted">
        Hecho con ❤️ por Seba, Nacho, Joaco y Tomi —
        <a href="https://jurassicode.vercel.app" target="_blank" class="link link-underline">
          JurassiCode
        </a>
      </div>
    </div>
  </footer>

  <!-- Bootstrap  JS-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>