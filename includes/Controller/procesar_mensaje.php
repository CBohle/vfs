<?php
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre   = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $servicio = trim($_POST['servicio'] ?? '');
    $mensaje  = trim($_POST['mensaje'] ?? '');

    if ($nombre && $apellido && $email && $telefono && $servicio && $mensaje) {
        $estado = 'pendiente'; // Estado por defecto

        $stmt = $conexion->prepare("INSERT INTO mensajes (nombre, apellido, email, telefono, servicio, mensaje, estado) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die('Error al preparar la consulta: ' . $conexion->error);
        }

        $stmt->bind_param("sssssss", $nombre, $apellido, $email, $telefono, $servicio, $mensaje, $estado);

        if (!$stmt->execute()) {
            die('Error al ejecutar la consulta: ' . $stmt->error);
        }

        $stmt->close();
        header('Location: ../../public/index.php?mensaje=enviado');
        exit;
    } else {
        header('Location: ../../public/index.php?error=campos');
        exit;
    }
}
