<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_usuario.php';
proteger_admin();

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ok = registrar_usuario(
        trim($_POST['nombre']),
        trim($_POST['usuario']),
        $_POST['clave'],
        $_POST['rol']
    );
    if ($ok) header('Location: dashboard.php');
    else $error = 'Usuario ya existe.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>JurassiDraft - Crear Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Crear Usuario</h3>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" required placeholder="Nombre completo">
                    </div>

                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" name="usuario" id="usuario" class="form-control" required placeholder="Nombre de usuario">
                    </div>

                    <div class="mb-3">
                        <label for="clave" class="form-label">Contraseña</label>
                        <input type="password" name="clave" id="clave" class="form-control" required placeholder="Contraseña">
                    </div>

                    <div class="mb-3">
                        <label for="rol" class="form-label">Rol</label>
                        <select name="rol" id="rol" class="form-select">
                            <option value="jugador">Jugador</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Crear Usuario</button>
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
