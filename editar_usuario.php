<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_usuario.php';
proteger_admin();

$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) {
    header('Location: dashboard.php');
    exit;
}

$user = obtener_usuario($id);
if (!$user) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $rol     = $_POST['rol'] ?? 'jugador';
    $clave   = trim($_POST['clave'] ?? '') ?: null;

    if (actualizar_usuario($id, $nombre, $usuario, $rol, $clave)) {
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Error al actualizar usuario.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario #<?= htmlspecialchars($id) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Editar Usuario #<?= htmlspecialchars($id) ?></h3>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="editar_usuario.php?id=<?= htmlspecialchars($id) ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" class="form-control" name="usuario" id="usuario" value="<?= htmlspecialchars($user['usuario']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="clave" class="form-label">Nueva Contraseña (opcional)</label>
                        <input type="password" class="form-control" name="clave" id="clave" placeholder="••••••••">
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select name="rol" id="rol" class="form-select">
                            <option value="jugador" <?= $user['rol']==='jugador'?'selected':'' ?>>Jugador</option>
                            <option value="admin" <?= $user['rol']==='admin'?'selected':'' ?>>Admin</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Guardar Cambios</button>
                    </div>
                </form>

                <div class="text-center mt-3">
                    <a href="dashboard.php" class="btn btn-link">← Volver al Panel</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
