<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/funciones_usuario.php';
proteger_admin();
$id = $_GET['id'] ?? null;
if ($id && is_numeric($id)) {
    eliminar_usuario($id);
}
header('Location: dashboard.php');
exit;