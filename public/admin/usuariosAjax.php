<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/usuariosController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

switch ($_POST['accion'] ?? '') {
    case 'obtenerRolPorId':
        echo json_encode(obtenerRolPorId(intval($_POST['id'])));
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
        $stmt = $conexion->prepare("UPDATE roles SET activo = NOT activo WHERE id = ?");
        $stmt->bind_param("i", $_POST['id']);
        echo json_encode(['success' => $stmt->execute()]);
        break;

    case 'listarRoles':
    default:
        $columns = ['id', 'nombre', 'descripcion', 'activo'];
        $start = intval($_POST['start'] ?? 0);
        $length = intval($_POST['length'] ?? 10);
        $orderIndex = $_POST['order'][0]['column'] ?? 0;
        $orderDir = in_array($_POST['order'][0]['dir'] ?? '', ['asc', 'desc']) ? $_POST['order'][0]['dir'] : 'asc';
        $orderColumn = $columns[$orderIndex] ?? 'nombre';

        $where = "WHERE nombre != ''"; // Mostrar todos los roles, sin importar si están activos
        $sqlData = "SELECT id, nombre, descripcion, activo FROM roles $where ORDER BY $orderColumn $orderDir LIMIT ?, ?";
        $stmt = $conexion->prepare($sqlData);
        $stmt->bind_param("ii", $start, $length);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);

        $total = $conexion->query("SELECT COUNT(*) as total FROM roles WHERE nombre != ''")->fetch_assoc();

        echo json_encode([
            "draw" => intval($_POST['draw'] ?? 0),
            "recordsTotal" => $total['total'],
            "recordsFiltered" => $total['total'],
            "data" => $data
        ]);
}
