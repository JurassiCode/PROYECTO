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
<head><meta charset="UTF-8"><title>Crear Usuario</title></head>
<body>
    <h2>Crear Usuario</h2>
    <?php if ($error): ?><p style="color:red"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="POST">
        <input name="nombre" required placeholder="Nombre"><br>
        <input name="usuario" required placeholder="Usuario"><br>
        <input type="password" name="clave" required placeholder="Contraseña"><br>
        <select name="rol">
            <option value="jugador">Jugador</option>
            <option value="admin">Admin</option>
        </select><br>
        <button type="submit">Crear</button>
    </form>
    <a href="dashboard.php">← Volver</a>
</body>
</html>