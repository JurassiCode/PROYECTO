<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_usuario.php';
proteger_admin();
$usuarios = obtener_usuarios();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel Admin - Usuarios</title>
  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
    crossorigin="anonymous"
  />
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark bg-success mb-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="dashboard.php">Admin Panel</a>
      <button class="navbar-toggler" type="button"
              data-bs-toggle="collapse"
              data-bs-target="#navAdmin"
              aria-controls="navAdmin"
              aria-expanded="false"
              aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navAdmin">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a href="crear_usuario.php" class="btn btn-outline-light me-2">+ Nuevo Usuario</a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="btn btn-outline-light">Cerrar sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <h2 class="mb-4">Gestión de Usuarios</h2>

    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-success">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Rol</th>
            <th>Creado en</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($usuarios as $u): ?>
          <tr>
            <td><?= htmlspecialchars($u['id_usuario']) ?></td>
            <td><?= htmlspecialchars($u['nombre']) ?></td>
            <td><?= htmlspecialchars($u['usuario']) ?></td>
            <td>
              <?php if ($u['rol'] === 'admin'): ?>
                <span class="badge bg-primary">Admin</span>
              <?php else: ?>
                <span class="badge bg-secondary">Jugador</span>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($u['creado_en']) ?></td>
            <td class="text-center">
              <a href="editar_usuario.php?id=<?= $u['id_usuario'] ?>"
                 class="btn btn-sm btn-warning me-1">Editar</a>
              <a href="eliminar_usuario.php?id=<?= $u['id_usuario'] ?>"
                 class="btn btn-sm btn-danger"
                 onclick="return confirm('¿Confirmar eliminación?')">Eliminar</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Bootstrap JS Bundle -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
    crossorigin="anonymous"
  ></script>
</body>
</html>
