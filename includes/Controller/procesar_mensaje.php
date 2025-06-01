<?php
require_once __DIR__ . '/../db.php';

// Configura la respuesta como JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $servicio = trim($_POST['servicio'] ?? '');
    $mensaje  = trim($_POST['mensaje'] ?? '');

    if ($nombre && $apellido && $email && $telefono && $servicio && $mensaje) {
        $estado = 'pendiente';

        $stmt = $conexion->prepare("INSERT INTO mensajes (nombre, apellido, email, telefono, servicio, mensaje, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            echo json_encode(['success' => false, 'error' => 'Error al preparar la consulta: ' . $conexion->error]);
            exit;
        }

        $stmt->bind_param("sssssss", $nombre, $apellido, $email, $telefono, $servicio, $mensaje, $estado);

        if (!$stmt->execute()) {
            echo json_encode(['success' => false, 'error' => 'Error al ejecutar la consulta: ' . $stmt->error]);
            $stmt->close();
            exit;
        }

        $stmt->close();
        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']);
        exit;
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método no permitido.']);
    exit;
}
?>
