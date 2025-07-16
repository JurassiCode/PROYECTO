<?php
require_once __DIR__ . '/conexion.php';

function registrar_usuario($nombre, $usuario, $clave, $rol = 'jugador') {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id_usuario FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    if ($stmt->fetch()) return false;
    $hash = password_hash($clave, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, usuario, contrasena, rol) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$nombre, $usuario, $hash, $rol]);
}

function verificar_usuario($usuario, $clave) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($clave, $user['contrasena'])) return false;
    session_start();
    session_regenerate_id(true);
    $_SESSION['usuario'] = $user['usuario'];
    $_SESSION['rol']     = $user['rol'];
    $_SESSION['nombre']  = $user['nombre'];
    return true;
}

// --- Funciones CRUD Usuarios ---

function obtener_usuarios() {
    global $pdo;
    $stmt = $pdo->query("SELECT id_usuario, nombre, usuario, rol, creado_en FROM usuarios ORDER BY id_usuario");
    return $stmt->fetchAll();
}

function obtener_usuario($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT id_usuario, nombre, usuario, rol FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function actualizar_usuario($id, $nombre, $usuario, $rol, $clave = null) {
    global $pdo;
    if ($clave) {
        $hash = password_hash($clave, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            "UPDATE usuarios SET nombre = ?, usuario = ?, contrasena = ?, rol = ? WHERE id_usuario = ?"
        );
        return $stmt->execute([$nombre, $usuario, $hash, $rol, $id]);
    } else {
        $stmt = $pdo->prepare(
            "UPDATE usuarios SET nombre = ?, usuario = ?, rol = ? WHERE id_usuario = ?"
        );
        return $stmt->execute([$nombre, $usuario, $rol, $id]);
    }
}

function eliminar_usuario($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    return $stmt->execute([$id]);
}