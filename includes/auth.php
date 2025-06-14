<?php
session_start();
require_once __DIR__ . '/db.php';

// Redirige si no está logueado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Validar que el usuario esté activo
$id_usuario = $_SESSION['admin_id'] ?? null;

if ($id_usuario) {
    $sql = "SELECT activo FROM usuarios_admin WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    $usuario = $resultado->fetch_assoc();

    if (!$usuario || !$usuario['activo']) {
        // Cerrar sesión y redirigir si el usuario está inactivo
        session_destroy();
        header('Location: login.php?error=cuenta_inactiva');
        exit();
    }
}

$_SESSION['permisos'] = obtenerPermisos($_SESSION['rol_id']);

// Función para validar si el usuario tiene uno de los roles permitidos
function requiereRol(array $rolesPermitidos) {
    if (!isset($_SESSION['rol_id']) || !in_array($_SESSION['rol_id'], $rolesPermitidos)) {
        // Puedes mostrar un mensaje o redirigir a una página de error
        http_response_code(403);
        echo "Acceso denegado. No tienes permisos para ver esta sección.";
        exit();
    }
}

function obtenerPermisos($rol_id) {
    global $conexion;
    $sql = "SELECT modulo, accion FROM permisos WHERE rol_id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $rol_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $permisos = [];
    while ($row = $result->fetch_assoc()) {
        $permisos[$row['modulo']][] = $row['accion'];
    }

    return $permisos;
}

function tienePermiso($modulo, $accion) {
    if (!isset($_SESSION['permisos'][$modulo])) return false;
    return in_array($accion, $_SESSION['permisos'][$modulo]);
}
