<?php
// ==========================================
// AUTENTICACIÓN DEL SISTEMA
// ==========================================

// Iniciar sesión solo si aún no existe
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Evitar que el navegador guarde páginas protegidas
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Verificar si el usuario inició sesión
if (!isset($_SESSION['id_usuario'])) {

    session_unset();
    session_destroy();

    header("Location: /cumplimiento/permisos/login.php");
    exit();
}

// Verificar que exista el rol
if (!isset($_SESSION['rol'])) {

    session_unset();
    session_destroy();

    header("Location: /cumplimiento/permisos/login.php");
    exit();
}

// Variables disponibles en todo el sistema
$id_usuario = $_SESSION['id_usuario'];
$nombre     = $_SESSION['nombre'] ?? '';
$usuario    = $_SESSION['usuario'] ?? '';
$rol        = $_SESSION['rol'];