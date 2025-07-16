<?php
session_start();
// Si no hay usuario en sesión, lo mandamos al inicio
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
</head>
<body>
    <h2>¡Bienvenido, <?= htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8') ?>!</h2>
    <p>Ya estás logueado como jugador.</p>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
