<?php
session_start();
$logeado = isset($_SESSION['usuario']);
$nombre = $logeado ? htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8') : '';
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./assets/css/home.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous" />
    <link rel="shortcut icon" href="assets/images/logojuego_nobg.png" type="image/x-icon">
    <title>JurassiDraft - Inicio</title>
  </head>
  <body class="bg-light">
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-success navbar-dark">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          <img src="assets/images/logojuego_nobg.png" alt="Logo" class="img-fluid" style="max-height: 50px" />
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link active" href="#">Inicio</a>
            </li>
            <?php if ($logeado): ?>
            <li class="nav-item"><a class="nav-link" href="jugar.php">Jugar</a></li>
            <li class="nav-item"><a class="nav-link" href="seguimiento.php">Seguir partida</a></li>
            <?php endif; ?>
            <li class="nav-item"><a class="nav-link" href="#quienes-somos">¿Quiénes Somos?</a></li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Ayuda</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="documentacion.php" target="_blank">Documentación</a></li>
                <li><a class="dropdown-item" href="mailto:jurassicodeisbo@gmail.com">Contacto</a></li>
              </ul>
            </li>
          </ul>
          <ul class="navbar-nav">
            <?php if (!$logeado): ?>
              <li class="nav-item"><a class="nav-link" href="login.php">Iniciar Sesión</a></li>
              <li class="nav-item"><a class="nav-link" href="registro.php">Registrarse</a></li>
            <?php else: ?>
              <li class="nav-item d-flex align-items-center pe-3">
                <span class="navbar-text">Hola, <strong><?= $nombre ?></strong></span>
              </li>
              <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </nav>

    <!-- HERO -->
    <section class="d-flex flex-column justify-content-center align-items-center text-center bg-white" style="height: 60vh">
      <img src="assets/images/logojuego_nobg.png" alt="Logo JurassiDraft" class="img-fluid mb-4" style="max-height: 200px" />
      <?php if (!$logeado): ?>
        <h1 class="mb-3">¡Hola! Bienvenido a <strong>JurassiDraft</strong></h1>
        <div class="d-flex gap-3">
          <a href="login.php" class="btn btn-success btn-lg">Iniciar Sesión</a>
          <a href="registro.php" class="btn btn-outline-secondary btn-lg">Registrarse</a>
        </div>
      <?php else: ?>
        <h1 class="mb-3">¡Hola, <?= $nombre ?>! Bienvenido a <strong>JurassiDraft</strong></h1>
        <div class="d-flex gap-3">
          <a href="jugar.php" class="btn btn-success btn-lg">Jugar</a>
          <a href="seguimiento.php" class="btn btn-outline-secondary btn-lg">Seguir partida</a>
          <a href="logout.php" class="btn btn-danger btn-lg">Cerrar sesión</a>
        </div>
      <?php endif; ?>
    </section>

    <!-- SECCIÓN: ¿Cómo funciona? -->
    <section class="container my-5">
      <h2 class="text-center mb-4">¿Cómo funciona JurassiDraft?</h2>
      <div class="row g-4">
        <div class="col-12 col-md-8">
          <div class="card h-100 d-flex flex-row align-items-start p-3">
            <img src="assets/images/logojuego_nobg.png" alt="Draft" class="me-3" style="width: 80px; height: 80px; object-fit: cover" />
            <div>
              <h5 class="card-title">1. Elegí tus dinosaurios</h5>
              <p class="card-text">Tomá dinosaurios al azar, mantenelos en secreto y seleccioná uno por turno. ¡El draft es clave para armar el mejor parque!</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card h-100 d-flex flex-row align-items-start p-3">
            <img src="assets/images/logojuego_nobg.png" alt="Dado" class="me-3" style="width: 80px; height: 80px; object-fit: cover" />
            <div>
              <h5 class="card-title">2. Restricción del dado</h5>
              <p class="card-text">En cada turno, el dado define una regla especial de colocación. ¡Adaptate y planeá tu jugada!</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-4">
          <div class="card h-100 d-flex flex-row align-items-start p-3">
            <img src="assets/images/logojuego_nobg.png" alt="Parque" class="me-3" style="width: 80px; height: 80px; object-fit: cover" />
            <div>
              <h5 class="card-title">3. Colocá los dinosaurios</h5>
              <p class="card-text">Cada recinto tiene sus propias reglas y formas de puntuar. Pensá bien dónde los ponés para maximizar tus puntos.</p>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-8">
          <div class="card h-100 d-flex flex-row align-items-start p-3">
            <img src="assets/images/logojuego_nobg.png" alt="Puntaje" class="me-3" style="width: 80px; height: 80px; object-fit: cover" />
            <div>
              <h5 class="card-title">4. Sumá puntos y ganá</h5>
              <p class="card-text">Después de dos rondas, se cuentan los puntos por recinto, parejas, T-Rex y río. ¡El que mejor haya gestionado su parque, gana!</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- SECCIÓN: ¿Quiénes Somos? -->
<section id="quienes-somos" class="container py-5">
  <h2 class="text-center mb-4">¿Quiénes Somos?</h2>

  <div class="row align-items-center">
    <div class="col-md-6 mb-4 mb-md-0">
      <p>
        <strong>JurassiDraft</strong> es una solución creada por <strong>JurassiCode</strong>,
        un equipo de estudiantes apasionados por el desarrollo web y los juegos de mesa.
        Nuestra misión es digitalizar la experiencia lúdica, haciendo que las partidas
        sean más fluidas, organizadas y divertidas.
      </p>

      <ul class="list-unstyled">
        <li>
          <strong>🎯 Misión:</strong>
          Simplificar la gestión de puntos y turnos en juegos de mesa.
        </li>
        <li>
          <strong>👀 Visión:</strong>
          Ser la plataforma de referencia para acompañar partidas en todo el mundo.
        </li>
        <li>
          <strong>🌟 Valores:</strong>
          Innovación, claridad, accesibilidad y diversión.
        </li>
      </ul>
    </div>

    <div class="col-md-6 text-center">
      <img
        src="assets/images/logojuego_nobg.png"
        alt="Equipo JurassiCode"
        class="img-fluid rounded shadow"
        style="max-height: 300px;"
      />
    </div>
  </div>
</section>

    <!-- SECCIÓN: Testimonios -->
    <section class="bg-white py-5">
      <div class="container">
        <h2 class="text-center mb-4">Lo que dicen quienes lo probaron</h2>
        <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner text-center">
            <?php
            $testimonios = [
              ['Joaco', '¡Nunca fue tan fácil llevar la cuenta en Draftosaurus! Nos enfocamos en jugar y no en discutir reglas.'],
              ['Seba', 'Ideal para jugar en familia. Los chicos anotan y los grandes se olvidan del papel y lápiz.'],
              ['Tomi', 'Nos evitó discusiones y errores de cuenta. ¡La interfaz es tan clara que hasta mi abuela lo usa!'],
              ['Nacho', 'La usabilidad está perfecta. En segundos ya estás jugando, no hay que explicar nada técnico.'],
              ['Marcelo', 'Está buenísimo que funcione en el celu. Jugamos en la playa y llevamos el puntaje sin drama.'],
              ['Walter', 'Lo usamos en el torneo de la UTU. ¡Re fácil de entender y cero errores!'],
              ['Matías Prestes', 'No suelo dar muchas clases de programación, pero este juego me pareció espectacular. Muy bien pensado, ¡sigan así!']
            ];
            foreach ($testimonios as $index => [$autor, $texto]) {
              $active = $index === 0 ? 'active' : '';
              echo "<div class='carousel-item $active'><blockquote class='blockquote'><p class='mb-4'>\"$texto\"</p><footer class='blockquote-footer'>$autor</footer></blockquote></div>";
            }
            ?>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
          </button>
        </div>
      </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-success text-white py-4 mt-5">
      <div class="container text-center">
        <small class="d-block">
          Web hecha con ❤️ por Nacho, Seba, Tomi y Joaco &mdash; &copy;
          <a href="http://jurassicode.vercel.app/" class="text-white text-decoration-none fw-semibold" target="_blank" rel="noopener noreferrer">JurassiCode</a> 2025
        </small>
      </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
    <script>
      new bootstrap.Carousel('#testimonialCarousel', {
        interval: 4000,
        ride: 'carousel',
        pause: false,
        wrap: true
      });
    </script>
  </body>
</html>