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
        $id = $_POST['id'] ?? null;
        $rolActual = $id ? obtenerRolPorId($id) : null;
        if ($rolActual && strtolower($rolActual['nombre']) === 'admin') {
            echo json_encode(['success' => false, 'error' => 'No se pueden modificar los permisos del rol admin']);
            exit;
        }
        $datos = [
            'id' => $id,
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
        $resultado = toggleEstadoUsuario($id);
        echo json_encode($resultado);
        break;

    case 'listarUsuarios':
        echo json_encode(listarUsuarios($_POST));
        break;
    case 'cambiar_estado':
        $id = intval($_POST['id']);
        $estado = $_POST['estado'];
        $tipo = $_POST['tipo'] ?? 'usuario';

        if ($tipo === 'usuario') {
            $usuario = obtenerUsuarioPorId($id);

            if ($estado === 'inactivo') {
                // Verificar si es un admin
                
                $rolAdmin = obtenerRolPorNombre('admin');
                if (!$usuario) {
                    echo json_encode(['success' => false, 'error' => 'Usuario no encontrado.']);
                    exit;
                }
                if (!$rolAdmin) {
                    echo json_encode(['success' => false, 'error' => 'Rol admin no existe.']);
                    exit;
                }
                if ($usuario['rol_id'] == $rolAdmin['id']) {
                    $sql = "SELECT COUNT(*) as total FROM usuarios_admin WHERE activo = 1 AND rol_id = ?";
                    $stmt = $conexion->prepare($sql);
                    $stmt->bind_param("i", $rolAdmin['id']);
                    $stmt->execute();
                    $result = $stmt->get_result()->fetch_assoc();
                    if ($result['total'] <= 1) {
                        echo json_encode(['success' => false, 'error' => 'Debe haber al menos un usuario admin activo.']);
                        exit;
                    }
                }
            }

            echo json_encode(cambiarEstadoUsuario($id, $estado));
        } else {
            echo json_encode(cambiarEstadoRol($id, $estado));
        }
        break;
    case 'eliminarRol':
        $id = intval($_POST['id']);
        $rol = obtenerRolPorId($id);

        if (strtolower($rol['nombre']) === 'admin') {
            echo json_encode(['success' => false, 'error' => 'No se puede eliminar el rol admin']);
        } else {
            echo json_encode(['success' => eliminarRol($id)]);
        }
        break;
    case 'eliminarUsuario':
        $id = intval($_POST['id']);
        echo json_encode(['success' => eliminarUsuario($id)]);
        break;
    default:
        echo json_encode(['error' => 'Acción no válida']);
        break;
}
?>