<?php
// registro.php
require_once __DIR__ . '/includes/funciones_usuario.php';
session_start();

$mensaje = '';
$error   = '';
$name    = '';
$email   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']            ?? '');
    $email   = trim($_POST['email']           ?? '');
    $password=            $_POST['password']  ?? '';
    $confirm =            $_POST['confirmPassword'] ?? '';

    if ($password !== $confirm) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        $ok = registrar_usuario($name, $email, $password);
        if ($ok) {
            $mensaje = 'Usuario registrado con éxito. <a href="login.php">Iniciá sesión</a>.';
            // opcional: vaciar campos
            $name = $email = '';
        } else {
            $error = 'Ese correo ya está registrado.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
      crossorigin="anonymous"
    />
    <title>JurassiDraft - Registro</title>
  </head>
  <body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow w-100" style="max-width: 400px;">
      <form method="POST" action="registro.php" novalidate>
        <h2 class="mb-4 text-center">Registrarse</h2>

        <?php if ($mensaje): ?>
          <div class="alert alert-success" role="alert">
            <?= $mensaje ?>
          </div>
        <?php endif; ?>

        <?php if ($error): ?>
          <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
          </div>
        <?php endif; ?>

        <div class="mb-3">
          <label for="name" class="form-label">Nombre</label>
          <input
            type="text"
            class="form-control"
            id="name"
            name="name"
            placeholder="Tu nombre"
            required
            value="<?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>"
          />
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Correo</label>
          <input
            type="email"
            class="form-control"
            id="email"
            name="email"
            placeholder="correo@ejemplo.com"
            required
            value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
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

        <div class="mb-3">
          <label for="confirmPassword" class="form-label">Repetir contraseña</label>
          <input
            type="password"
            class="form-control"
            id="confirmPassword"
            name="confirmPassword"
            placeholder="Repetí la contraseña"
            required
          />
        </div>

        <div class="mb-3 text-center">
          <p class="mb-1">
            ¿Ya tenés cuenta?
            <a href="login.php">Iniciá sesión</a>
          </p>
        </div>

        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Crear cuenta</button>
        </div>
      </form>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
