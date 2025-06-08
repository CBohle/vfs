<?php
session_start();

// Redirige si no está logueado
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit();
}

// Función para validar si el usuario tiene uno de los roles permitidos
function requiereRol(array $rolesPermitidos) {
    if (!isset($_SESSION['rol_id']) || !in_array($_SESSION['rol_id'], $rolesPermitidos)) {
        // Puedes mostrar un mensaje o redirigir a una página de error
        http_response_code(403);
        echo "Acceso denegado. No tienes permisos para ver esta sección.";
        exit();
    }
}
