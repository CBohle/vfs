<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/Controller/postulacionesController.php';

$id = intval($_GET['id'] ?? 0);

// Obtener datos de la postulación
$sql = "SELECT * FROM curriculum WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$msg = $result->fetch_assoc();

if (!$msg) {
    echo '<p class="text-danger">Postulación no encontrada.</p>';
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

<style>
    .seccion-postulacion {
        background-color: #e9f2ff;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 0.75rem;
    }

    .seccion-postulacion h5 {
        margin: -1.25rem -1.25rem 1rem -1.25rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem 0.5rem 0 0;
        background-color: #0d6efd;
        color:rgb(255, 255, 255) !important;
    }

    .dato-label {
        font-weight: 600;
        color: #495057;
    }

    .dato-valor {
        color: #212529;
    }

    .fila-dato {
        margin-bottom: 0.6rem;
    }
</style>

<!-- Contenido principal -->
<div class="row g-4 align-items-stretch">
    <!-- Columna Izquierda -->
    <div class="col-md-6 d-flex flex-column gap-1">
        <div class="seccion-postulacion h-100">
            <h5><i class="bi bi-person-badge me-2"></i>Información Personal</h5>
            <div class="fila-dato"><span class="dato-label">Nombre:</span> <?= htmlspecialchars($msg['nombre']) ?> <?= htmlspecialchars($msg['apellido']) ?></div>
            <div class="fila-dato"><span class="dato-label">RUT:</span> <?= htmlspecialchars($msg['rut']) ?></div>
            <div class="fila-dato"><span class="dato-label">Fecha de Nacimiento:</span> <?= $msg['fecha_nacimiento'] ? date('d-m-Y', strtotime($msg['fecha_nacimiento'])) : '—' ?></div>
        </div>

        <div class="seccion-postulacion h-100">
            <h5><i class="bi bi-envelope me-2"></i>Contacto</h5>

            <div class="d-flex align-items-start mb-3">
                <i class="bi bi-envelope-fill me-2 text-primary fs-5 mt-1"></i>
                <div>
                    <span class="dato-label">Email:</span><br>
                    <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" class="text-decoration-none text-dark">
                        <?= htmlspecialchars($msg['email']) ?>
                    </a>
                </div>
            </div>

            <div class="d-flex align-items-start mb-3">
                <i class="bi bi-telephone-fill me-2 text-success fs-5 mt-1"></i>
                <div>
                    <span class="dato-label">Teléfono:</span><br>
                    <a href="tel:<?= preg_replace('/\D/', '', $msg['telefono']) ?>" class="text-decoration-none text-dark">
                        <?= formatear_telefono($msg['telefono']) ?>
                    </a>
                </div>
            </div>

            <div class="d-flex align-items-start mb-3">
                <i class="bi bi-geo-alt-fill me-2 text-danger fs-5 mt-1"></i>
                <div>
                    <span class="dato-label">Dirección:</span><br>
                    <?= htmlspecialchars($msg['direccion']) ?><br>
                    <?= htmlspecialchars($msg['comuna']) ?>, <?= htmlspecialchars($msg['region']) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Columna Derecha -->
    <div class="col-md-6 d-flex flex-column gap-1">
        <div class="seccion-postulacion h-100">
            <h5><i class="bi bi-mortarboard me-2"></i>Formación Académica</h5>
            <div class="fila-dato"><span class="dato-label">Estudios:</span> <?= htmlspecialchars($msg['estudios']) ?></div>
            <div class="fila-dato"><span class="dato-label">Institución:</span> <?= htmlspecialchars($msg['institucion_educacional']) ?></div>
            <div class="fila-dato"><span class="dato-label">Año Titulación:</span> <?= htmlspecialchars($msg['ano_titulacion']) ?></div>
            <div class="fila-dato">
                <span class="dato-label">Formación en Tasación:</span>
                <?= $msg['formacion_tasacion'] ? '<i class="bi bi-check-circle-fill text-success ms-1"></i>' : '<i class="bi bi-x-circle-fill text-danger ms-1"></i>' ?>
            </div>
            <?php if ($msg['detalle_formacion']): ?>
                <div class="bg-white border rounded p-2 ms-3 mt-2">
                    <span class="dato-label small">Descripción de la formación:</span><br>
                    <span class="dato-valor small"><?= nl2br(htmlspecialchars($msg['detalle_formacion'])) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="seccion-postulacion h-100">
            <h5><i class="bi bi-briefcase me-2"></i>Experiencia</h5>
            <div class="fila-dato mt-3"><span class="dato-label">Años de Experiencia:</span> <?= htmlspecialchars($msg['anos_experiencia_tasacion']) ?></div>
            <div class="fila-dato"><span class="dato-label">Empresa de Tasación:</span> <?= htmlspecialchars($msg['otra_empresa']) ?></div>
        </div>

        <div class="seccion-postulacion h-100">
            <h5><i class="bi bi-calendar-check me-2"></i>Disponibilidad</h5>
            <div class="row text-center">
                <div class="col-4">
                    <span class="dato-label">Comuna</span><br>
                    <?= $msg['disponibilidad_comuna'] ? '<i class="bi bi-check-circle-fill text-success fs-5"></i>' : '<i class="bi bi-x-circle-fill text-danger fs-5"></i>' ?>
                </div>
                <div class="col-4">
                    <span class="dato-label">Región</span><br>
                    <?= $msg['disponibilidad_region'] ? '<i class="bi bi-check-circle-fill text-success fs-5"></i>' : '<i class="bi bi-x-circle-fill text-danger fs-5"></i>' ?>
                </div>
                <div class="col-4">
                    <span class="dato-label">Movilización</span><br>
                    <?= $msg['movilizacion_propia'] ? '<i class="bi bi-check-circle-fill text-success fs-5"></i>' : '<i class="bi bi-x-circle-fill text-danger fs-5"></i>' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Fila completa -->
    <div class="col-12">
        <div class="seccion-postulacion">
            <h5><i class="bi bi-info-circle me-2"></i>Otros Detalles</h5>
            <div class="row">
                <div class="col-md-6 fila-dato"><span class="dato-label">Fecha de Creación:</span> <?= htmlspecialchars($msg['fecha_creacion']) ?></div>
                <div class="col-md-6 fila-dato"><span class="dato-label">Última Modificación:</span> <?= htmlspecialchars($msg['fecha_modificacion']) ?></div>
                <div class="col-md-6 fila-dato"><span class="dato-label">Estado:</span> <span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($msg['estado']) ?></span></div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleImportante(id, estadoActual) {
    const nuevoValor = estadoActual === 1 ? 0 : 1;

    $.post('postulacionesAjax.php', {
        accion: 'importante',
        postulacion_id: id,
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

             if (typeof window.cargarVistaMensajes === 'function') {
                window.cargarVistaMensajes();
            }
        } else {
            alert('No se pudo actualizar el estado de importancia.');
        }
    }, 'json');
}
</script>
