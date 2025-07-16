<?php
// login.php
session_start();
require_once __DIR__ . '/includes/funciones_usuario.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email']    ?? '');
    $password =        $_POST['password'] ?? '';

    if (verificar_usuario($email, $password)) {
        // Sesión iniciada; redirigimos según rol
        if ($_SESSION['rol'] === 'admin') {
            header('Location: dashboard.php');
        } else {
            header('Location: index.php');
        }
        exit;
    }

    $error = 'Credenciales incorrectas.';
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="./assets/css/login.css" />
    <title>JurassiDraft - Iniciar sesión</title>
  </head>
  <body
    class="bg-light d-flex justify-content-center align-items-center vh-100"
  >
    <div class="card p-4 shadow w-100" style="max-width: 400px">
      <form method="POST" action="login.php" novalidate>
        <h2 class="mb-4 text-center">Iniciar sesión</h2>

        <?php if ($error): ?>
          <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <label for="email" class="form-label">Correo</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            placeholder="correo@ejemplo.com"
            required
            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '' ?>"
          />
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input
            type="password"
            class="form-control"
            id="password"
            name="password"
            placeholder="Contraseña"
            required
          />
        </div>

        <div class="mb-3 text-center">
          <p class="mb-1">
            ¿No tenés cuenta? <a href="registro.php">Registrate</a>
          </p>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
        </div>
      </form>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
