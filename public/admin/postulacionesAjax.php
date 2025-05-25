<?php
header('Content-Type: application/json');
ini_set('display_errors', 0);// Cambiar a 1 para ver errores en desarrollo
ini_set('display_startup_errors', 0); // Cambiar a 1 para ver errores en desarrollo
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/postulacionesController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

// Eliminar postulación
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    if (actualizar_estado($id, 'eliminado')) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// Marcar como importante
if ($_POST['accion'] === 'importante' && isset($_POST['postulacion_id'], $_POST['importante'])) {
    $id = intval($_POST['postulacion_id']);
    $importante = intval($_POST['importante']);
    if (actualizar_importante($id, $importante)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}

// Recuperar postulacion y actualizar estado basado en la respuesta
if (isset($_POST['accion']) && $_POST['accion'] === 'recuperar' && isset($_POST['id'])) {
    $id = intval($_POST['id']);
    echo actualizar_estado_postulacion($id);
    exit;
}

// Consulta con filtros
$columns = ['importante', 'id', 'rut', 'nombre','apellido', 'email', 'estudio','ano_titulacion','anos_experiencia_tasacion','disponibilidad_comuna','disponibilidad_region','movilizacion_propia','estado','fecha_creacion'];
$draw = intval($_POST['draw'] ?? 0);
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);

$orderColumnIndex = $_POST['order'][0]['column'] ?? 12;
$orderDir = in_array(strtolower($_POST['order'][0]['dir'] ?? ''), ['asc', 'desc']) ? $_POST['order'][0]['dir'] : 'desc';
$orderColumn = $columns[$orderColumnIndex] ?? 'fecha_creacion';

$estado = $_POST['estado'] ?? '';
$importante = $_POST['importante'] ?? '';
$search = trim($_POST['search']['value'] ?? '');

// Construcción del WHERE con filtros y búsqueda
$where = "WHERE nombre != ''";

// Solo excluir eliminados si no estamos buscando específicamente los eliminados
if ($estado !== 'eliminado') {
    $where .= " AND estado != 'eliminado'";
}

// Filtro por estado
if ($estado !== '' && $estado !== 'Todos') {
    $where .= " AND estado = ?";
    $paramTypes .= 's';
    $params[] = $estado;
}

// Filtro por importante
if ($importante !== '') {
    $where .= " AND importante = ?";
    $paramTypes .= 'i';
    $params[] = intval($importante);
}
// Filtro de búsqueda
if (!empty($search)) {
    $where .= " AND (rut LIKE ? OR nombre LIKE ? OR apellido LIKE ? OR email LIKE ? OR estudio LIKE ? OR estado LIKE ?)";
    $paramTypes .= "ssssss";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// --- COUNT con filtros ---
$sqlCount = "SELECT COUNT(*) as total FROM curriculum $where";
$stmtCount = $conexion->prepare($sqlCount);
if ($paramTypes) {
    $stmtCount->bind_param($paramTypes, ...$params);
}
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$totalFiltered = ($row = $resultCount->fetch_assoc()) ? intval($row['total']) : 0;
$stmtCount->close();

// --- SELECT con filtros, orden y paginación ---
$secondaryOrder = ($orderColumn === 'fecha_creacion') ? ', importante DESC' : '';
$orderClause = "ORDER BY $orderColumn $orderDir $secondaryOrder";

$sqlData = "SELECT importante, id, rut, nombre, apellido, email, estudio, ano_titulacion, anos_experiencia_tasacion, disponibilidad_comuna, disponibilidad_region, movilizacion_propia, estado, archivo, fecha_creacion
            FROM curriculum
            $where
            $orderClause
            LIMIT ?, ?";
$stmtData = $conexion->prepare($sqlData);

$paramsWithLimits = $params;
$paramTypesWithLimits = $paramTypes;
$paramsWithLimits[] = $start;
$paramsWithLimits[] = $length;
$paramTypesWithLimits .= 'ii';

$stmtData->bind_param($paramTypesWithLimits, ...$paramsWithLimits);
$stmtData->execute();
$resultData = $stmtData->get_result();

$data = [];
while ($row = $resultData->fetch_assoc()) {
    $data[] = [
        'importante' => $row['importante'],
        'id' => $row['id'],
        'rut' => $row['rut'],
        'nombre' => $row['nombre'],
        'apellido' => $row['apellido'],
        'email' => $row['email'],
        'estudio' => $row['estudio'],
        'ano_titulacion' => $row['ano_titulacion'],
        'anos_experiencia_tasacion' => $row['anos_experiencia_tasacion'],
        'disponibilidad_comuna' => $row['disponibilidad_comuna'],
        'disponibilidad_region' => $row['disponibilidad_region'],
        'movilizacion_propia' => $row['movilizacion_propia'],
        'estado' => $row['estado'],
        'fecha' => date('d-m-Y', strtotime($row['fecha_creacion'])),
        'archivo' => $row['archivo'],
        'acciones' => ''
    ];
}
$stmtData->close();

// --- COUNT total sin filtros ---
$sqlTotal = "SELECT COUNT(*) as total FROM curriculum WHERE nombre != '' AND estado != 'eliminado'";
$resultTotal = $conexion->query($sqlTotal);
$totalData = ($fila = $resultTotal->fetch_assoc()) ? intval($fila['total']) : 0;

// Totales adicionales
$totalPendientesPostulaciones = obtener_postulaciones_pendientes();
$totalPostulaciones = obtener_total_postulaciones();

$response = [
    "draw" => $draw,
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data,
    "totalPendientesPostulaciones" => $totalPendientesPostulaciones,
    "totalPostulaciones" => $totalPostulaciones
];

echo json_encode($response);
