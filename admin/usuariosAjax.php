<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/Controller/usuariosController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$accion = $_POST['accion'] ?? '';

switch ($accion) {
    case 'obtenerRolPorId':
        $id = intval($_POST['id']);
        echo json_encode(obtenerRolPorId($id));
        break;

    case 'guardarRol':
        $datos = [
            'id' => $_POST['id'] ?? null,
            'nombre' => $_POST['nombre'] ?? '',
            'descripcion' => $_POST['descripcion'] ?? ''
        ];
        $permisos = $_POST['permisos'] ?? [];
        echo json_encode(['success' => guardarRol($datos, $permisos)]);
        break;

    case 'toggleEstadoRol':
        $id = intval($_POST['id']);
        echo json_encode(['success' => toggleEstadoRol($id)]);
        break;

    case 'listarRoles':
        echo json_encode(listarRoles($_POST));
        break;

    case 'obtenerUsuarioPorId':
        $id = intval($_POST['id']);
        echo json_encode(obtenerUsuarioPorId($id));
        break;

    case 'guardarUsuario':
        $datos = [
            'id' => $_POST['id'] ?? null,
            'nombre' => $_POST['nombre'] ?? '',
            'apellido' => $_POST['apellido'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? null,
            'rol_id' => $_POST['rol_id'] ?? null,
            'activo' => 1
        ];
        echo json_encode(['success' => guardarUsuario($datos)]);
        break;

    case 'toggleEstadoUsuario':
        $id = intval($_POST['id']);
        echo json_encode(['success' => toggleEstadoUsuario($id)]);
        break;

    case 'listarUsuarios':
        echo json_encode(listarUsuarios($_POST));
        break;

    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}
?>