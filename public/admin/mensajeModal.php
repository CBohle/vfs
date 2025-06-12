<?php
session_start();
$rol_id = $_SESSION['rol_id'] ?? null;
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/mensajesController.php';

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
?>
<!-- Botón oculto que luego se moverá dinámicamente al header -->
<div id="botonImportanteHTML" style="display: none;">
    <button id="btnImportante"
        class="btn <?= $btnClase ?> btn-sm d-flex align-items-center"
        onclick="toggleImportante(<?= $msg['id'] ?>, <?= $esImportante ?>)">
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
                    <span class="project"><?= htmlspecialchars($msg['servicio'] ?? '') ?></span>
                    <small class="text-muted ms-2">Creado: <?= htmlspecialchars($msg['fecha_creacion'] ?? '') ?></small>
                </div>
                <div class="text-end">
                    <span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($msg['estado'] ?? '') ?></span>
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
                            <span class="text-muted ms-2"><?= htmlspecialchars($msg['fecha_respuesta'] ?? '') ?></span>
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
                    <textarea name="respuesta" class="form-control mb-2" required placeholder="Escribe tu respuesta..."></textarea>
                    <button type="submit" class="btn btn-primary btn-sm">Enviar respuesta</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function toggleImportante(id, estadoActual) {
    const nuevoValor = estadoActual === 1 ? 0 : 1;

    $.post('mensajesAjax.php', {
        accion: 'importante',
        mensaje_id: id,
        importante: nuevoValor
    }, function(response) {
        if (response.success) {
            const boton = $('#btnImportante');
            const icono = $('#iconoImportante');
            const texto = $('#textoImportante');

            if (nuevoValor === 1) {
                boton.removeClass('btn-warning').addClass('btn-outline-warning');
                icono.removeClass('bi-star').addClass('bi-star-fill');
                texto.text('Marcar como no importante');
            } else {
                boton.removeClass('btn-outline-warning').addClass('btn-warning');
                icono.removeClass('bi-star-fill').addClass('bi-star');
                texto.text('Marcar como importante');
            }

            boton.attr('onclick', `toggleImportante(${id}, ${nuevoValor})`);

            if (typeof tabla !== 'undefined' && tabla !== null) {
                tabla.ajax.reload(null, false);
            }
        } else {
            alert('No se pudo actualizar el estado de importancia.');
        }
    }, 'json');
}
$('#formRespuesta').on('submit', function(e) {
    e.preventDefault();

    const formData = $(this).serialize();
    const id = $('input[name="mensaje_id"]').val();

    $.post('mensajesAjax.php', formData, function(respuesta) {
        if (respuesta.success) {
            // Eliminar el formulario de respuesta
            $('#formRespuesta').remove();

            // Crear el bloque de respuesta HTML
            const replyHTML = `
                <div class="reply">
                    <div class="reply-box" style="width: 100%; position: relative;">
                        <div class="reply-header d-flex justify-content-between align-items-center">
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                <span class="reply-name">${respuesta.admin_nombre} ${respuesta.admin_apellido}</span>
                                <span class="rol">${respuesta.rol}</span>
                            </div>
                            <span class="text-muted ms-2">${respuesta.fecha}</span>
                        </div>
                        <span class="reply-text">${respuesta.respuesta}</span>
                    </div>
                </div>
            `;

            // Insertar el bloque de respuesta justo después del encabezado del mensaje
            $('#resultado_filtro_mensaje').append(replyHTML);

            // Opcional: mostrar mensaje de éxito
            $('#resultado_filtro_mensaje').prepend(`
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Respuesta enviada correctamente.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            `);

            // Recargar tabla sin perder estado
            if (typeof tabla !== 'undefined') {
                tabla.ajax.reload(null, false);
            }

        } else {
            alert('No se pudo guardar la respuesta.');
        }
    }, 'json');
});
</script>