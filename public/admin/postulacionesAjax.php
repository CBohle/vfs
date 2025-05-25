<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/postulacionesController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

// --- Inicializar variables ---
$params = [];
$paramTypes = '';
$where = "WHERE nombre != ''";
$columns = ['importante', 'id', 'rut', 'nombre', 'apellido', 'email', 'estudio', 'ano_titulacion', 'anos_experiencia_tasacion', 'disponibilidad_comuna', 'disponibilidad_region', 'movilizacion_propia', 'estado', 'fecha_creacion'];
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
if ($estado !== 'eliminado') {
    $where .= " AND estado != 'eliminado'";
}
if (!empty($estado) && $estado !== 'Todos') {
    $where .= " AND estado = ?";
    $paramTypes .= 's';
    $params[] = $estado;
}
if ($importante !== '') {
    $where .= " AND importante = ?";
    $paramTypes .= 'i';
    $params[] = intval($importante);
}
if (!empty($search)) {
    $where .= " AND (rut LIKE ? OR nombre LIKE ? OR apellido LIKE ? OR email LIKE ? OR estudio LIKE ? OR estado LIKE ?)";
    $paramTypes .= 'ssssss';
    $params = array_merge($params, array_fill(0, 6, "%$search%"));
}

// --- Eliminar, marcar importante, recuperar ---
if (isset($_POST['accion'])) {
    $accion = $_POST['accion'];
    $id = intval($_POST['id'] ?? 0);
    if ($accion === 'eliminar' && $id) {
        echo json_encode(['success' => actualizar_estado($id, 'eliminado')]);
        exit;
    }
    if ($accion === 'importante' && isset($_POST['postulacion_id'], $_POST['importante'])) {
        $id = intval($_POST['postulacion_id']);
        $importante = intval($_POST['importante']);
        echo json_encode(['success' => actualizar_importante($id, $importante)]);
        exit;
    }
    if ($accion === 'recuperar' && $id) {
        echo actualizar_estado_postulacion($id);
        exit;
    }
}

// --- Contar total filtrado ---
$sqlCount = "SELECT COUNT(*) as total FROM curriculum $where";
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
$sqlData = "SELECT importante, id, rut, nombre, apellido, email, estudio, ano_titulacion, anos_experiencia_tasacion, disponibilidad_comuna, disponibilidad_region, movilizacion_propia, estado, archivo, fecha_creacion FROM curriculum $where $orderClause LIMIT ?, ?";
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

// --- Contar total general ---
$totalData = $conexion->query("SELECT COUNT(*) as total FROM curriculum WHERE nombre != '' AND estado != 'eliminado'")->fetch_assoc()['total'] ?? 0;

// Totales
$totalPendientes = obtener_postulaciones_pendientes();
$total = obtener_total_postulaciones();

// --- Respuesta ---
$response = [
    "draw" => $draw,
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data,
    "totalPendientesPostulaciones" => $totalPendientes,
    "totalPostulaciones" => $total
];
echo json_encode($response);
?>
