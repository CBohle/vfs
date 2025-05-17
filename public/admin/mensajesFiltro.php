<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/mensajesController.php';

if (!isset($_POST["buscar"])) {
    echo '<h3 class="text-center text-muted">No se ha enviado ningún filtro.</h3>';
    exit;
}

$query = "
    SELECT 
        mensajes.*, 
        respuestas.respuesta, 
        respuestas.fecha_respuesta, 
        usuarios_admin.nombre AS admin_nombre, 
        usuarios_admin.apellido AS admin_apellido, 
        usuarios_admin.rol AS admin_rol
    FROM mensajes
    LEFT JOIN respuestas ON respuestas.mensaje_id = mensajes.id
    LEFT JOIN usuarios_admin ON usuarios_admin.id = respuestas.usuario_admin_id
    WHERE mensajes.nombre != ''
";

if (!empty($_POST['estado']) && $_POST['estado'] != 'Todos') {
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
    $query .= " AND mensajes.estado = '$estado'";
}

if (!empty($_POST['servicio']) && $_POST['servicio'] != 'Todos') {
    $servicio = mysqli_real_escape_string($conexion, $_POST['servicio']);
    $query .= " AND mensajes.servicio = '$servicio'";
}

if (!empty($_POST['orden']) && $_POST['orden'] != 'Todos') {
    $orden = $_POST['orden'] === 'ASC' ? 'ASC' : 'DESC';
    $query .= " ORDER BY mensajes.fecha_creacion $orden";
} else {
    $query .= " ORDER BY mensajes.fecha_creacion DESC";
}

$result = mysqli_query($conexion, $query);

if (!$result) {
    echo '<h3 class="text-danger">Error en la consulta: ' . mysqli_error($conexion) . '</h3>';
    exit;
}

if ($result->num_rows === 0) {
    echo '<h3 class="text-center text-muted">No hay mensajes disponibles.</h3>';
    exit;
}

// Crear array con todos los mensajes
$mensajes = [];
while ($msg = $result->fetch_assoc()) {
    $mensajes[] = $msg;
}

// Incluir la plantilla que muestra los mensajes
include 'mensajesLista.php';
