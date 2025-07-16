<?php
// includes/auth.php
session_start();

/**
 * @return bool
 */
function usuario_logueado() {
    return isset($_SESSION['usuario']);
}

/**
 * @return bool
 */
function es_admin() {
    return usuario_logueado() && $_SESSION['rol'] === 'admin';
}

/**
 * Protege páginas de admin.
 */
function proteger_admin() {
    if (!es_admin()) {
        header('Location: login.php');
        exit;
    }
}