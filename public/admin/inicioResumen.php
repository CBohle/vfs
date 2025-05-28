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

<div class="container-fluid px-3 py-2">
    <div id="contenido-dinamico" class="mx-auto w-100" style="max-width: 100%;">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="mb-0">Dashboard</h2>
        </div>
    </div>
</div>

<div class="alert alert-warning" role="alert">
    📬 Tienes <strong><?= $mensajes_pendientes ?></strong> mensajes <strong>pendientes</strong> por revisar.
</div>

<div class="alert alert-info" role="alert">
    📄 Hay <strong><?= $postulaciones_pendientes ?></strong> <strong>postulaciones nuevas</strong> sin procesar.
</div>