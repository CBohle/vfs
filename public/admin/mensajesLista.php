<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/mensajesController.php';
require_once __DIR__ . '/../../includes/config.php';
// Leer filtros recibidos por POST, o valores por defecto si no están
$estado = $_POST['estado'] ?? 'Todos';
$servicio = $_POST['servicio'] ?? 'Todos';
$orden = $_POST['orden'] ?? 'DESC';

// Construir consulta base
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

// Agregar filtros si no es 'Todos'
if ($estado !== 'Todos') {
    $estado_safe = mysqli_real_escape_string($conexion, $estado);
    $query .= " AND mensajes.estado = '$estado_safe'";
}

if ($servicio !== 'Todos') {
    $servicio_safe = mysqli_real_escape_string($conexion, $servicio);
    $query .= " AND mensajes.servicio = '$servicio_safe'";
}

// Orden
$orden_safe = ($orden === 'ASC') ? 'ASC' : 'DESC';
$query .= " ORDER BY mensajes.fecha_creacion $orden_safe";

$result = mysqli_query($conexion, $query);

if (!$result) {
    echo '<h3 class="text-danger">Error en la consulta: ' . mysqli_error($conexion) . '</h3>';
    exit;
}

if ($result->num_rows === 0) {
    echo '<h3 class="text-center text-muted">No hay mensajes disponibles.</h3>';
    exit;
}

// Crear array de mensajes
$mensajes = [];
while ($msg = $result->fetch_assoc()) {
    $mensajes[] = $msg;
}
?>

<!-- Aquí va el HTML que antes estaba en mensajesLista.php -->
<?php foreach ($mensajes as $i => $msg): ?>
    <?php $estadoClass = obtenerClaseEstado($msg['estado'] ?? ''); ?>
    <div class="mb-3 position-relative">
        <div class="header2">
            <div class="header2-info">
                <div>
                    <h4 class="name mb-1"><?= htmlspecialchars($msg['nombre'] ?? '') ?> <?= htmlspecialchars($msg['apellido'] ?? '') ?></h4>
                    <div class="email"><?= htmlspecialchars($msg['email'] ?? '') ?></div>
                </div>
                <span class="project"><?= htmlspecialchars($msg['servicio'] ?? '') ?></span>
                <small class="text-muted ms-2">Creado: <?= tiempo_transcurrido($msg['fecha_creacion'] ?? '') ?></small>
            </div>
            <div class="text-end">
                <span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($msg['estado'] ?? '') ?></span>
                <small class="text-danger ms-2 eliminar-link"
                    onclick="eliminarMensaje(<?= $msg['id'] ?>)">
                    Eliminar
                </small>
            </div>
        </div>
        <p class="mb-2"><?= htmlspecialchars($msg['mensaje'] ?? '') ?></p>
        <?php if (!empty($msg['respuesta'])): ?>
            <div class="reply">
                <div class="reply-box" style="width: 100%; position: relative;">
                    <div class="reply-header d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-wrap align-items-center gap-2">
                            <span class="reply-name"><?= htmlspecialchars($msg['admin_nombre'] ?? '') ?> <?= htmlspecialchars($msg['admin_apellido'] ?? '') ?></span>
                            <span class="rol"><?= htmlspecialchars($msg['admin_rol'] ?? '') ?></span>
                        </div>
                        <span class="text-muted ms-2"><?= tiempo_transcurrido($msg['fecha_respuesta'] ?? '') ?></span>
                    </div>
                    <span class="reply-text"><?= htmlspecialchars($msg['respuesta'] ?? '') ?></span>
                </div>
            </div>
        <?php else: ?>
            <button class="btn btn-sm btn-primary mt-2" id="btn-responder-<?= htmlspecialchars($msg['id']) ?>" onclick="this.style.display='none'; toggleReplyForm(<?= htmlspecialchars($msg['id']) ?>)">Responder</button>
            <form id="reply-form-<?= htmlspecialchars($msg['id']) ?>" class="mt-2 d-none" method="POST" action="responder.php">
                <input type="hidden" name="mensaje_id" value="<?= htmlspecialchars($msg['id']) ?>">
                <textarea name="respuesta" class="form-control mb-2" required placeholder="Escribe tu respuesta..."></textarea>
                <button type="submit" class="btn btn-primary btn-sm">Enviar respuesta</button>
            </form>
        <?php endif; ?>
    </div>
    <?php if ($i < count($mensajes) - 1): ?>
        <hr>
    <?php endif; ?>
<?php endforeach; ?>