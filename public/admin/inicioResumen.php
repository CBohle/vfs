<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php'; // conexión con variable $conexion

$mensajes_pendientes = 0;
$postulaciones_pendientes = 0;

// Consulta real de mensajes pendientes
$stmt1 = $conexion->query("SELECT COUNT(*) AS total FROM mensajes WHERE estado = 'pendiente'");
if ($stmt1) {
    $mensajes_pendientes = $stmt1->fetch_assoc()['total'];
    $stmt1->free();
}

// Consulta real de postulaciones pendientes
$stmt2 = $conexion->query("SELECT COUNT(*) AS total FROM curriculum WHERE estado = 'pendiente'");
if ($stmt2) {
    $postulaciones_pendientes = $stmt2->fetch_assoc()['total'];
    $stmt2->free();
}
?>

<div class="alert alert-warning" role="alert">
    📬 Tienes <strong><?= $mensajes_pendientes ?></strong> mensajes <strong>pendientes</strong> por revisar.
</div>

<div class="alert alert-info" role="alert">
    📄 Hay <strong><?= $postulaciones_pendientes ?></strong> <strong>postulaciones nuevas</strong> sin procesar.
</div>
