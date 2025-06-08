<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 0); // Cambiar a 1 para ver errores en desarrollo
ini_set('display_startup_errors', 0); // Cambiar a 1 para ver errores en desarrollo
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/mensajesController.php';
require_once __DIR__ . '/../../vendor/autoload.php'; // Asegúrate de que exista
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


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
if ($_POST['accion'] === 'importante' && isset($_POST['mensaje_id'], $_POST['importante'])) {
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
if (isset($_POST['mensaje_id'], $_POST['respuesta']) && empty($_POST['accion'])) {
    $mensaje_id = intval($_POST['mensaje_id']);
    $respuesta = trim($_POST['respuesta']);
    $usuario_admin_id = $_SESSION['usuario_id'] ?? 0;

    // Obtener datos del cliente
    $stmt = $conexion->prepare("SELECT email, nombre, apellido FROM mensajes WHERE id = ?");
    $stmt->bind_param("i", $mensaje_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $cliente = $result->fetch_assoc();

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
        }

        echo json_encode([
            'success' => true,
            'respuesta' => $respuesta,
            'fecha' => date('d-m-Y H:i'),
            'admin_nombre' => $admin['nombre'] ?? '',
            'admin_apellido' => $admin['apellido'] ?? '',
            'rol' => $admin['rol'] ?? ''
        ]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al insertar respuesta']);
    }
    exit;
}


// Consulta con filtros
$columns = ['importante', 'id', 'servicio', 'nombre', 'email', 'mensaje', 'estado', 'fecha_creacion'];
$draw = intval($_POST['draw'] ?? 0);
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);

$orderColumnIndex = $_POST['order'][0]['column'] ?? 7;
$orderDir = in_array(strtolower($_POST['order'][0]['dir'] ?? ''), ['asc', 'desc']) ? $_POST['order'][0]['dir'] : 'desc';
$orderColumn = $columns[$orderColumnIndex] ?? 'fecha_creacion';

$estado = $_POST['estado'] ?? '';
$servicio = $_POST['servicio'] ?? '';
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
    $where .= " AND (nombre LIKE ? OR email LIKE ? OR mensaje LIKE ? OR servicio LIKE ? OR estado LIKE ?)";
    $paramTypes .= "sssss";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// --- COUNT con filtros ---
$sqlCount = "SELECT COUNT(*) as total FROM mensajes $where";
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

$sqlData = "SELECT importante, id, servicio, nombre, email, mensaje, estado, fecha_creacion
            FROM mensajes
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
        'servicio' => $row['servicio'],
        'nombre' => $row['nombre'],
        'email' => $row['email'],
        'mensaje' => $row['mensaje'],
        'estado' => $row['estado'],
        'fecha' => date('d-m-Y', strtotime($row['fecha_creacion'])),
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
