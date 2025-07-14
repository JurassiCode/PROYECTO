<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_usuario.php';
proteger_admin();
$id = $_GET['id'] ?? null;
if (!$id || !is_numeric($id)) { header('Location: dashboard.php'); exit; }
$user = obtener_usuario($id);
if (!$user) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre  = trim($_POST['nombre'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $rol     = $_POST['rol'] ?? 'jugador';
    $clave   = trim($_POST['clave'] ?? '') ?: null;
    if (actualizar_usuario($id, $nombre, $usuario, $rol, $clave)) {
        header('Location: dashboard.php'); exit;
    }
    $error = 'Error al actualizar usuario.';
}
?>
<!DOCTYPE html>
<html lang="es">
<head><meta charset="UTF-8"><title>Editar Usuario #<?= htmlspecialchars($id) ?></title></head>
<body>
    <h2>Editar Usuario #<?= htmlspecialchars($id) ?></h2>
    <?php if ($error): ?><p style="color:red"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="POST" action="editar_usuario.php?id=<?= htmlspecialchars($id) ?>">
        <input name="nombre"  value="<?= htmlspecialchars($user['nombre']) ?>" required><br>
        <input name="usuario" value="<?= htmlspecialchars($user['usuario']) ?>" required><br>
        <input type="password" name="clave" placeholder="Nueva contraseña (opcional)"><br>
        <select name="rol">
            <option value="jugador" <?= $user['rol']==='jugador'?'selected':'' ?>>Jugador</option>
            <option value="admin"   <?= $user['rol']==='admin'?'selected':'' ?>>Admin</option>
        </select><br>
        <button type="submit">Guardar</button>
    </form>
    <a href="dashboard.php">← Volver</a>
</body>
</html>