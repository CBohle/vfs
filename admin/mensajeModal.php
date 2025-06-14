<?php
session_start();
$rol_id = $_SESSION['rol_id'] ?? null;
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/Controller/mensajesController.php';

$id = intval($_GET['id'] ?? 0);

echo '<style>

.header2 {
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.header2-info {
  display: flex;
  align-items: start;
}
.header2 .name {
  font-weight: bold;
  font-size: 20px;
  margin: 0;
}
.header2 .email {
  font-size: 12px;
  color: #7c7878;
}
.project {
  background: #fcd663;
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 500;
  display: inline-block;
  margin-left: 15px;
}
.tag {
  background: #cfc5fd;
  color: #4c3fb2;
  border-radius: 8px;
  font-size: 12px;
  padding: 2px 6px;
  margin-left: 8px;
}
.reply {
  margin-top: 10px;
}
.reply-box {
  background-color: #f8f9fa;
  border: 1px solid #dee2e6;
  border-radius: 0.5rem;
  padding: 1rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
  max-width: 100%;
}
.reply-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 0.5rem;
}
.reply-name {
  font-weight: 600;
  color: #0d6efd;
}
.rol {
  font-size: 0.875rem;
  color: #6c757d;
  margin-left: 0.5rem;
}
.reply-text {
  white-space: pre-wrap;
  color: #343a40;
}
.texto-largo-contenido {
  word-break: break-word;
}
.estado-badge {
  padding: 4px 8px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 500;
}
#btnImportante:hover #iconoImportante {
  transform: scale(1.3) rotate(10deg);
  transition: transform 0.3s ease;
}
@keyframes balanceoEstrella {
  0% { transform: rotate(0deg); }
  25% { transform: rotate(15deg); }
  50% { transform: rotate(-15deg); }
  75% { transform: rotate(10deg); }
  100% { transform: rotate(0deg); }
}

#iconoImportante.balanceando {
  animation: balanceoEstrella 0.5s ease-in-out;
}
</style>';

$sql = "SELECT 
    mensajes.*, 
    respuestas.respuesta, 
    respuestas.fecha_respuesta, 
    usuarios_admin.nombre AS admin_nombre, 
    usuarios_admin.apellido AS admin_apellido, 
    roles.nombre AS rol
FROM mensajes
LEFT JOIN respuestas ON respuestas.mensaje_id = mensajes.id
LEFT JOIN usuarios_admin ON usuarios_admin.id = respuestas.usuario_admin_id
LEFT JOIN roles ON roles.id = usuarios_admin.rol_id
WHERE mensajes.id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$msg = $result->fetch_assoc();

if (!$msg) {
    echo '<p class="text-danger">Mensaje no encontrado.</p>';
    exit;
}

// Clases dinámicas
$estadoClass = obtenerClaseEstado($msg['estado'] ?? '');
$esImportante = $msg['importante'] ?? 0;
$btnClase = $esImportante ? 'btn-outline-warning' : 'btn-warning';
$iconoClase = $esImportante ? 'bi-star-fill' : 'bi-star';
$textoImportante = $esImportante ? 'Marcar como no importante' : 'Marcar como importante';

function formatearFecha($fechaOriginal) {
    $dt = new DateTime($fechaOriginal);
    return $dt->format('d-m-Y H:i');
}
?>
<!-- Botón oculto que luego se moverá dinámicamente al header -->
<div id="botonImportanteHTML" style="display: none;">
    <button id="btnImportante"
        class="btn <?= $btnClase ?> btn-sm d-flex align-items-center"
        onclick="toggleImportanteMensaje(<?= $msg['id'] ?>, <?= $esImportante ?>)">
        <i id="iconoImportante" class="bi <?= $iconoClase ?> me-2"></i>
        <span id="textoImportante"><?= $textoImportante ?></span>
    </button>
</div>

      <div class="card-body" id="resultado_filtro_mensaje">
        <div class="mb-3 position-relative">
            <div class="header2">
                <div class="header2-info">
                    <div>
                        <h4 class="name mb-1"><?= htmlspecialchars($msg['nombre'] ?? '') ?> <?= htmlspecialchars($msg['apellido'] ?? '') ?></h4>
                        <div class="email"><?= htmlspecialchars($msg['email'] ?? '') ?></div>
                    </div>
                    <span class="project"><?= ucfirst(htmlspecialchars($msg['servicio'] ?? '')) ?></span>
                    <small class="text-muted ms-2">Creado: <?= isset($msg['fecha_creacion']) ? formatearFecha($msg['fecha_creacion']) : '' ?></small>
                </div>
                <div class="text-end">
                    <span class="badge <?= $estadoClass ?> estado-badge"><?= ucfirst(htmlspecialchars($msg['estado'] ?? '')) ?></span>
                </div>
            </div>
            <br>
            <p class="mb-2 texto-largo-contenido"><?= htmlspecialchars($msg['mensaje'] ?? '') ?></p>
            <?php if (!empty($msg['respuesta'])): ?>
                <div class="reply">
                    <div class="reply-box" style="width: 100%; position: relative;">
                        <div class="reply-header d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="reply-name"><?= htmlspecialchars($msg['admin_nombre'] ?? '') ?> <?= htmlspecialchars($msg['admin_apellido'] ?? '') ?></span>
                                <span class="rol"><?= htmlspecialchars($msg['rol'] ?? '') ?></span>
                            </div>
                            <span class="text-muted ms-2"><?= isset($msg['fecha_respuesta']) ? formatearFecha($msg['fecha_respuesta']) : '' ?></span>
                        </div>
                        <span class="reply-text texto-largo-contenido"><?= htmlspecialchars($msg['respuesta'] ?? '') ?></span>
                    </div>
                </div>
            <?php elseif ($msg['estado'] !== 'eliminado'): ?>
                <!-- Mostrar formulario solo si no está eliminado -->
               <?php if ($rol_id == 4): ?>
                    <div class="mt-3 alert alert-warning">
                        No tienes permisos para responder mensajes.
                    </div>
                <?php else: ?>
                    <form id="formRespuesta" class="mt-3">
                    <input type="hidden" name="accion" value="guardarRespuesta">
                    <input type="hidden" name="mensaje_id" value="<?= $msg['id'] ?>">
                    <textarea id="respuesta_texto" name="respuesta" class="form-control mb-2" required placeholder="Escribe tu respuesta..."></textarea>
                    <button type="submit" class="btn btn-primary btn-sm">Enviar respuesta</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>