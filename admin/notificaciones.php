<?php
require_once __DIR__ . '/../includes/db.php';

$response = [
    'mensajes' => 0,
    'postulaciones' => 0,
];

// Consulta mensajes pendientes
$res1 = $conexion->query("SELECT COUNT(*) AS total FROM mensajes WHERE estado = 'pendiente'");
if ($res1) {
    $response['mensajes'] = $res1->fetch_assoc()['total'];
    $res1->free();
}

// Consulta postulaciones pendientes
$res2 = $conexion->query("SELECT COUNT(*) AS total FROM curriculum WHERE estado = 'pendiente'");
if ($res2) {
    $response['postulaciones'] = $res2->fetch_assoc()['total'];
    $res2->free();
}

header('Content-Type: application/json');
echo json_encode($response);
