<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 1); // Cambiar a 1 para ver errores en desarrollo
ini_set('display_startup_errors', 1); // Cambiar a 1 para ver errores en desarrollo
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/Controller/mensajesController.php';
require_once __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_POST['accion']) && empty($_POST['draw'])) {
    echo json_encode(['success' => false, 'error' => 'Acción no especificada']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

// Eliminar mensaje
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminar' && isset($_POST['id'])) {
    if ($_SESSION['rol_id'] == 4) {
        echo json_encode(['success' => false, 'error' => 'Sin permisos para eliminar']);
        exit;
    }
    $id = intval($_POST['id']);
    if (actualizar_estado($id, 'eliminado')) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}


// Marcar como importante
if (isset($_POST['accion']) && $_POST['accion'] === 'importante' && isset($_POST['mensaje_id'], $_POST['importante'])) {
    if ($_SESSION['rol_id'] == 4) {
        echo json_encode(['success' => false, 'error' => 'Sin permisos para marcar como importante']);
        exit;
    }
    $id = intval($_POST['mensaje_id']);
    $importante = intval($_POST['importante']);
    if (actualizar_importante($id, $importante)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
    exit;
}


// Recuperar mensaje y actualizar estado basado en la respuesta
if (isset($_POST['accion']) && $_POST['accion'] === 'recuperar' && isset($_POST['id'])) {
    if ($_SESSION['rol_id'] == 4) {
        echo json_encode(['success' => false, 'error' => 'Sin permisos para recuperar']);
        exit;
    }
    $id = intval($_POST['id']);
    echo actualizar_estado_mensaje($id);
    exit;
}


// Guardar respuesta a un mensaje
if (isset($_POST['accion']) && $_POST['accion'] === 'guardarRespuesta' && isset($_POST['mensaje_id'], $_POST['respuesta'])) {
    $mensaje_id = intval($_POST['mensaje_id']);
    $respuesta = trim($_POST['respuesta']);
    $usuario_admin_id = $_SESSION['usuario_id'] ?? 0;

    // Obtener datos del cliente
    $stmt = $conexion->prepare("SELECT email, nombre, apellido FROM mensajes WHERE id = ?");
    $stmt->bind_param("i", $mensaje_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();
    // Verificar si ya existe una respuesta para este mensaje
    $stmt = $conexion->prepare("SELECT COUNT(*) as total FROM respuestas WHERE mensaje_id = ?");
    $stmt->bind_param("i", $mensaje_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $existe = $result->fetch_assoc()['total'] ?? 0;

    if ($existe > 0) {
        echo json_encode([
            'success' => false,
            'error' => 'Este mensaje ya ha sido respondido.'
        ]);
        exit;
    }
    // Guardar respuesta
    $stmt = $conexion->prepare("INSERT INTO respuestas (mensaje_id, usuario_admin_id, respuesta) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $mensaje_id, $usuario_admin_id, $respuesta);
    $resultado = $stmt->execute();
    if ($resultado) {
        $conexion->query("UPDATE mensajes SET estado = 'respondido' WHERE id = $mensaje_id");
        // Datos del admin
        $stmt = $conexion->prepare("
            SELECT ua.nombre, ua.apellido, r.nombre AS rol
            FROM usuarios_admin ua
            JOIN roles r ON ua.rol_id = r.id
            WHERE ua.id = ?
        ");
        $stmt->bind_param("i", $usuario_admin_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $admin = $result->fetch_assoc();

        // Enviar correo
        /*
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'mail.vfs.cl'; // Cambia por smtp.hostingblue si lo usas
            $mail->SMTPAuth = true;
            $mail->Username = 'contacto@vfs.cl';
            $mail->Password = 'ContactoVFS1234.';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 465;

            $mail->setFrom('contacto@vfs.cl', 'VFS-Admin');
            $mail->addAddress($cliente['email'], $cliente['nombre'] . ' ' . $cliente['apellido']);
            $mail->Subject = 'Respuesta a tu consulta en VFS';
            $mail->Body = "Hola {$cliente['nombre']},\n\nEsta es nuestra respuesta a tu consulta:\n\n$respuesta\n\nGracias por contactarnos.";

            $mail->send();
        } catch (Exception $e) {
             error_log("ERROR SMTP: " . $mail->ErrorInfo); // Se registra en error_log
            echo json_encode([
                'success' => false,
                'error' => 'Error al enviar correo: ' . $mail->ErrorInfo
            ]);
            exit;
        }*/

        echo json_encode([
            'success' => true,
            'respuesta' => $respuesta,
            'fecha' => date('d-m-Y H:i'),
            'admin_nombre' => $admin['nombre'] ?? '',
            'admin_apellido' => $admin['apellido'] ?? '',
            'rol' => $admin['rol'] ?? ''
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Error al insertar respuesta: ' . $stmt->error
        ]);
    }
    exit;
}
if (isset($_POST['accion']) && $_POST['accion'] === 'marcarLeido' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Solo cambiar si está en estado 'pendiente'
    $stmt = $conexion->prepare("UPDATE mensajes SET estado = 'leido' WHERE id = ? AND estado = 'pendiente'");
    $stmt->bind_param("i", $id);
    $resultado = $stmt->execute();

    echo json_encode(['success' => $resultado]);
    exit;
}
// Consulta con filtros
$columns = ['m.importante', 'm.importante', 'm.id', 'm.servicio', 'm.nombre', 'm.email', 'm.telefono', 'm.mensaje', 'r.respuesta', 'r.fecha_respuesta', 'ua.nombre', 'rl.nombre', 'm.estado', 'm.fecha_creacion', 'm.id'];
$draw = intval($_POST['draw'] ?? 0);
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);

if (!isset($_POST['order']) || empty($_POST['order'])) {
    // Orden inicial por defecto
    $orderClause = "ORDER BY m.importante DESC, m.fecha_creacion DESC";
} else {
    $orderings = [];
    foreach ($_POST['order'] as $orderItem) {
        $idx = intval($orderItem['column']);
        $dir = strtolower($orderItem['dir']) === 'asc' ? 'ASC' : 'DESC';
        $col = $columns[$idx] ?? '';
        if ($col) {
            $orderings[] = "$col $dir";
        }
    }
    $orderClause = "ORDER BY " . implode(', ', $orderings);
}

$estado = $_POST['estado'] ?? '';
$servicio = $_POST['servicio'] ?? '';
$importante = $_POST['importante'] ?? '';
$search = trim($_POST['search']['value'] ?? '');

$paramTypes = '';
$params = [];

// Construcción del WHERE con filtros y búsqueda
$where = "WHERE m.nombre != ''";

// Solo excluir eliminados si no estamos buscando específicamente los eliminados
if ($estado !== 'eliminado') {
    $where .= " AND estado != 'eliminado'";
}

// Filtro por estado
if (isset($_POST['estadoMultiple']) && is_array($_POST['estadoMultiple']) && count($_POST['estadoMultiple']) > 0) {
    $estados = $_POST['estadoMultiple'];
    $placeholders = implode(',', array_fill(0, count($estados), '?'));
    $where .= " AND estado IN ($placeholders)";
    $paramTypes .= str_repeat('s', count($estados));
    $params = array_merge($params, $estados);
} elseif ($estado !== '') {
    $where .= " AND estado = ?";
    $paramTypes .= 's';
    $params[] = $estado;
}

// Filtro por servicio
if ($servicio !== '' && $servicio !== 'Todos') {
    $where .= " AND servicio = ?";
    $paramTypes .= 's';
    $params[] = $servicio;
}

// Filtro por importante
if ($importante !== '') {
    $where .= " AND importante = ?";
    $paramTypes .= 'i';
    $params[] = intval($importante);
}
// Filtro de búsqueda
if (!empty($search)) {
    $where .= " AND (m.nombre LIKE ? OR m.email LIKE ? OR m.mensaje LIKE ? OR m.servicio LIKE ? OR m.estado LIKE ? OR DATE_FORMAT(m.fecha_creacion, '%d-%m-%Y') LIKE ?)";
    $paramTypes .= "ssssss";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// --- COUNT con filtros ---
$sqlCount = "SELECT COUNT(*) as total FROM mensajes m $where";
$stmtCount = $conexion->prepare($sqlCount);
if ($paramTypes) {
    $stmtCount->bind_param($paramTypes, ...$params);
}
$stmtCount->execute();
$resultCount = $stmtCount->get_result();
$totalFiltered = ($row = $resultCount->fetch_assoc()) ? intval($row['total']) : 0;
$stmtCount->close();

// --- SELECT con filtros, orden y paginación ---
$sqlData = "
    SELECT 
        m.importante,
        m.id,
        m.servicio,
        m.nombre,
        m.apellido,
        m.email,
        m.telefono,
        m.mensaje,
        m.estado,
        m.fecha_creacion,
        r.respuesta,
        r.fecha_respuesta,
        ua.nombre AS admin_nombre,
        ua.apellido AS admin_apellido,
        rl.nombre AS rol
    FROM mensajes m
    LEFT JOIN respuestas r ON r.mensaje_id = m.id
    LEFT JOIN usuarios_admin ua ON ua.id = r.usuario_admin_id
    LEFT JOIN roles rl ON rl.id = ua.rol_id
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
        'DT_RowId' => 'row_' . $row['id'],
        'importante' => $row['importante'],
        'importante_texto' => ($row['importante'] ?? 0) == 1 ? 'Sí' : 'No',
        'id' => $row['id'],
        'servicio' => $row['servicio'],
        'nombre' => $row['nombre'] . ' ' . $row['apellido'],
        'email' => $row['email'],
        'telefono' => $row['telefono'] ?? '',
        'mensaje' => $row['mensaje'],
        'estado' => $row['estado'],
        'fecha' => date('d-m-Y', strtotime($row['fecha_creacion'])),
        'respuesta' => $row['respuesta'] ?? '',
        'fecha_respuesta' => $row['fecha_respuesta'] ?? '',
        'admin' => ($row['admin_nombre'] ?? '') . ' ' . ($row['admin_apellido'] ?? ''),
        'rol' => $row['rol'] ?? '',
        'acciones' => ''
    ];
}
$stmtData->close();

// --- COUNT total sin filtros ---
$sqlTotal = "SELECT COUNT(*) as total FROM mensajes WHERE nombre != '' AND estado != 'eliminado'";
$resultTotal = $conexion->query($sqlTotal);
$totalData = ($fila = $resultTotal->fetch_assoc()) ? intval($fila['total']) : 0;

// Totales adicionales
$totalPendientes = obtener_mensajes_pendientes();
$totalMensajes = obtener_total_mensajes();

$response = [
    "draw" => $draw,
    "recordsTotal" => $totalData,
    "recordsFiltered" => $totalFiltered,
    "data" => $data,
    "totalPendientes" => $totalPendientes,
    "totalMensajes" => $totalMensajes
];

echo json_encode($response);
