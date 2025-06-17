<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/Controller/clientesController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

if (isset($_POST['accion']) && $_POST['accion'] === 'crear') {
    $nombre = $_POST['nombre_cliente'] ?? '';
    $apellido = $_POST['apellido_cliente'] ?? '';
    $tipo_persona = $_POST['tipo_persona'] ?? '';
    $nombre_empresa = $_POST['nombre_empresa'] ?? '';
    $email = $_POST['email_contacto'] ?? '';
    $telefono = $_POST['telefono_contacto'] ?? '';
    $tipo_activo = $_POST['tipo_activo'] ?? '';
    $detalle_activos = $_POST['detalle_activos'] ?? '';
    $notas = $_POST['notas'] ?? '';
    $usuario_id = $_SESSION['usuario_id'] ?? null;

    if (!$usuario_id) {
        echo json_encode(['success' => false, 'error' => 'Usuario no autenticado']);
        exit;
    }
    $sql = "INSERT INTO clientes 
        (tipo_persona, nombre_empresa, nombre_contacto, apellido_contacto, email_contacto, telefono_contacto, tipo_activos, detalle_activos, notas, estado, usuario_admin_id, fecha_creacion) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'activo', ?, NOW())";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('sssssssssi', $tipo_persona, $nombre_empresa, $nombre, $apellido, $email, $telefono, $tipo_activo, $detalle_activos, $notas, $usuario_id);

    /*if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    exit;*/

    if (!$stmt->execute()) {
        echo json_encode([
            'success' => false,
            'error' => $stmt->error,
            'sql' => $sql,
            'data' => [
                'tipo_persona' => $tipo_persona,
                'nombre_empresa' => $nombre_empresa,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'telefono' => $telefono,
                'tipo_activo' => $tipo_activo,
                'detalle_activos' => $detalle_activos,
                'notas' => $notas,
                'usuario_id' => $usuario_id,
            ]
        ]);
        exit;
    }

    echo json_encode(['success' => true]);
    exit;
}
if (isset($_POST['accion']) && $_POST['accion'] === 'actualizar') {
    $id = intval($_POST['id'] ?? 0);
    $nombre = trim($_POST['nombre_cliente'] ?? '');
    $apellido = trim($_POST['apellido_cliente'] ?? '');
    $tipo_persona = $_POST['tipo_persona'] ?? '';
    $nombre_empresa = trim($_POST['nombre_empresa'] ?? '');
    $email = trim($_POST['email_contacto'] ?? '');
    $telefono = trim($_POST['telefono_contacto'] ?? '');
    $tipo_activo = $_POST['tipo_activo'] ?? '';
    $detalle_activos = $_POST['detalle_activos'] ?? '';
    $notas = trim($_POST['notas'] ?? '');

    $stmt = $conexion->prepare("SELECT * FROM clientes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $actual = $stmt->get_result()->fetch_assoc();

    if (!$actual) {
        echo json_encode(['success' => false, 'error' => 'Cliente no encontrado.']);
        exit;
    }
    if (trim($detalle_activos) === '') {
        $detalle_activos = $actual['detalle_activos'] ?? '';
    }

    if (
        $actual['nombre_contacto'] === $nombre &&
        $actual['apellido_contacto'] === $apellido &&
        $actual['tipo_persona'] === $tipo_persona &&
        $actual['nombre_empresa'] === $nombre_empresa &&
        $actual['email_contacto'] === $email &&
        $actual['telefono_contacto'] === $telefono &&
        $actual['tipo_activos'] === $tipo_activo &&
        $actual['detalle_activos'] === $detalle_activos &&
        $actual['notas'] === $notas
    ) {
        echo json_encode([
            'success' => false,
            'type' => 'warning',
            'message' => 'No se detectaron cambios.'
        ]);
        exit;
    }

    $sql = "UPDATE clientes SET 
        tipo_persona = ?, nombre_empresa = ?, nombre_contacto = ?, apellido_contacto = ?, 
        email_contacto = ?, telefono_contacto = ?, tipo_activos = ?, detalle_activos = ?, 
        notas = ?, fecha_modificacion = NOW() 
        WHERE id = ?";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param('sssssssssi', $tipo_persona, $nombre_empresa, $nombre, $apellido, $email, $telefono, $tipo_activo, $detalle_activos, $notas, $id);

    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
        exit;
    }

    echo json_encode(['success' => true]);
    exit;
}
// --- Inicializar variables ---
$params = [];
$paramTypes = '';
$where = "WHERE nombre_contacto != ''";
$columns = [
    'importante',            // 0 → ícono estrella
    'importante',            // 1 → texto (oculta)
    'id',                    // 2
    'tipo_persona',          // 3
    'nombre_empresa',        // 4
    'nombre_contacto',       // 5 → nombre + apellido
    'email_contacto',        // 6
    'telefono_contacto',     // 7
    'tipo_activos',          // 8
    'detalle_activos',       // 9
    'estado',                // 10
    'fecha_creacion',        // 11 → oculta
    'fecha_modificacion',    // 12 → oculta
    'email_usuario',         // 13 → oculta
    'nombre_usuario',        // 14 → oculta
    'notas'                  // 15 → oculta
];
$draw = intval($_POST['draw'] ?? 0);
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$orderColumnIndex = $_POST['order'][0]['column'] ?? 12;
$orderDir = in_array(strtolower($_POST['order'][0]['dir'] ?? ''), ['asc', 'desc']) ? $_POST['order'][0]['dir'] : 'desc';
$orderColumn = $columns[$orderColumnIndex] ?? 'fecha_creacion';
$estado = $_POST['estado'] ?? '';
$importante = $_POST['importante'] ?? '';
$search = trim($_POST['search']['value'] ?? '');

// Filtros
if (empty($estado)) {
    $where .= " AND estado != 'eliminado'";
}
if (!empty($estado) && $estado !== 'Todos') {
    $where .= " AND estado = ?";
    $paramTypes .= 's';
    $params[] = $estado;
} else {
    $where .= " AND estado != 'eliminado'";
}
if ($importante !== '') {
    $where .= " AND importante = ?";
    $paramTypes .= 'i';
    $params[] = intval($importante);
}
if (!empty($search)) {
    $where .= " AND (tipo_persona LIKE ? OR nombre_empresa LIKE ? OR nombre_contacto LIKE ? OR apellido_contacto LIKE ? OR email_contacto LIKE ? OR telefono_contacto LIKE ? OR tipo_activos LIKE ? OR detalle_activos LIKE ? OR estado LIKE ?)";
    $paramTypes .= 'sssssssss';
    $params = array_merge($params, array_fill(0, 9, "%$search%"));
}

// --- Eliminar, marcar importante, recuperar ---
if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $id = intval($_POST['id'] ?? 0);
    if ($accion === 'eliminar' && $id) {
        echo json_encode(['success' => actualizar_estado($id, 'eliminado')]);
        exit;
    }
    if ($accion === 'importante' && isset($_POST['cliente_id'], $_POST['importante'])) {
        $id = intval($_POST['cliente_id']);
        $importante = intval($_POST['importante']);
        echo json_encode(['success' => actualizar_importante($id, $importante)]);
        exit;
    }
    if ($accion === 'recuperar' && $id) {
        echo actualizar_estado_cliente($id);
        exit;
    }
    if ($accion === 'cambiar_estado' && $id && isset($_POST['estado'])) {
        $nuevoEstado = $_POST['estado'];
        $stmt = $conexion->prepare("UPDATE clientes SET estado = ?, fecha_modificacion = NOW() WHERE id = ?");
        $stmt->bind_param("si", $nuevoEstado, $id);
        $success = $stmt->execute();
        echo json_encode(['success' => $success]);
        exit;
    }
}

// --- Contar total filtrado ---
$sqlCount = "SELECT COUNT(*) as total FROM clientes $where";
$stmtCount = $conexion->prepare($sqlCount);
if (!$stmtCount) {
    echo json_encode(['error' => 'Error en SQL COUNT: ' . $conexion->error]);
    exit;
}
if (!empty($paramTypes)) {
    $stmtCount->bind_param($paramTypes, ...$params);
}
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$totalFiltered = ($row = $resultCount->fetch_assoc()) ? intval($row['total']) : 0;
$stmtCount->close();

// --- Datos filtrados ---
$orderClause = "ORDER BY $orderColumn $orderDir, importante DESC";
$sqlData = "SELECT 
        c.importante,
        c.id,
        c.tipo_persona,
        c.nombre_empresa,
        c.nombre_contacto,
        c.apellido_contacto,
        c.email_contacto,
        c.telefono_contacto,
        c.tipo_activos,
        c.detalle_activos,
        c.estado,
        c.fecha_creacion,
        c.fecha_modificacion,
        c.notas,
        u.nombre AS nombre_usuario,
        u.apellido AS apellido_usuario,
        u.email AS email_usuario
    FROM clientes c
    LEFT JOIN usuarios_admin u ON u.id = c.usuario_admin_id
    $where
    $orderClause
    LIMIT ?, ?";
$stmtData = $conexion->prepare($sqlData);
if (!$stmtData) {
    echo json_encode(['error' => 'Error en SQL DATA: ' . $conexion->error]);
    exit;
}
$paramTypesWithLimits = $paramTypes . 'ii';
$paramsWithLimits = array_merge($params, [$start, $length]);
if (!empty($paramTypes)) {
    $stmtData->bind_param($paramTypesWithLimits, ...$paramsWithLimits);
} else {
    $stmtData->bind_param('ii', $start, $length);
}
$stmtData->execute();
$resultData = $stmtData->get_result();

$data = [];
while ($row = $resultData->fetch_assoc()) {
    $data[] = [
        'importante' => $row['importante'],
        'importante_texto' => ($row['importante'] ?? 0) == 1 ? 'Sí' : 'No',
        'id' => $row['id'],
        'tipo_persona' => $row['tipo_persona'],
        'nombre_empresa' => $row['nombre_empresa'],
        'nombre_contacto' => $row['nombre_contacto'],
        'apellido_contacto' => $row['apellido_contacto'],
        'email_contacto' => $row['email_contacto'],
        'telefono_contacto' => $row['telefono_contacto'],
        'tipo_activos' => $row['tipo_activos'],
        'detalle_activos' => $row['detalle_activos'],
        'estado' => $row['estado'],
        'fecha' => date('d-m-Y', strtotime($row['fecha_creacion'])),
        'fecha_modificacion' => date('d-m-Y', strtotime($row['fecha_modificacion'])),
        'email_usuario_creador' => $row['email_usuario'],
        'nombre_usuario_creador' => $row['nombre_usuario'] . ' ' . $row['apellido_usuario'],
        'notas' => $row['notas'],
        'acciones' => ''
    ];
}
$stmtData->close();

// --- Contar total general ---
$totalData = $conexion->query("SELECT COUNT(*) as total FROM clientes WHERE nombre_contacto != '' AND estado != 'eliminado'")->fetch_assoc()['total'] ?? 0;

// Totales
//$totalPendientes = obtener_postulaciones_pendientes();
//$total = obtener_total_postulaciones();

// --- Respuesta ---
$response = [
    "draw" => $draw,
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data,
    //"totalPendientesPostulaciones" => $totalPendientes,
    //"totalPostulaciones" => $total
];
echo json_encode($response);
?>