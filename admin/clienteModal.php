<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/Controller/clientesController.php';
$modo = $_GET['modo'] ?? 'ver';
$soloLectura = ($modo === 'ver');
$esEdicion = ($modo === 'editar');
$modoEdicion = ($modo === 'editar');
// Determinar si estamos viendo un cliente o creando uno
$id = intval($_GET['id'] ?? 0);

// Si estamos creando un cliente, inicializamos un arreglo vacío
if ($id > 0) {
    // Obtener los datos del cliente para verlo
    $sql = "SELECT 
            c.*, 
            u.nombre AS nombre_usuario_creador,
            u.email AS email_usuario_creador
        FROM clientes c
        LEFT JOIN usuarios_admin u ON c.usuario_admin_id = u.id
        WHERE c.id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $msg = $result->fetch_assoc();

    if (!$msg) {
        echo '<p class="text-danger">Cliente no encontrado.</p>';
        exit;
    }
} else {
    // Si estamos creando un cliente, inicializamos datos vacíos
    $msg = [
        'tipo_persona' => '',
        'nombre_empresa' => '',
        'nombre_contacto' => '',
        'apellido_contacto' => '',
        'email_contacto' => '',
        'telefono_contacto' => '',
        'tipo_activos' => '',
        'detalle_activos' => '',
        'notas' => '',
        'estado' => 'activo',
        'usuario_admin_id' => '',
        'fecha_creacion' => '',
        'fecha_modificacion' => ''
    ];
}
// Clases dinámicas
$estadoClass = obtenerClaseEstado($msg['estado'] ?? '');
$esImportante = $msg['importante'] ?? 0;
$btnClase = $esImportante ? 'btn-outline-warning' : 'btn-warning';
$iconoClase = $esImportante ? 'bi-star-fill' : 'bi-star';
$textoImportante = $esImportante ? 'Marcar como no importante' : 'Marcar como importante';
function formatearFecha($fechaOriginal)
{
    $dt = new DateTime($fechaOriginal);
    return $dt->format('d-m-Y H:i');
}
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
    .seccion-cliente {
        background-color: #e9f2ff;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 0.75rem;
    }

    .seccion-cliente h5 {
        margin: -1.25rem -1.25rem 1rem -1.25rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem 0.5rem 0 0;
        background-color: #0d6efd;
        color: rgb(255, 255, 255) !important;
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

    #btnImportante:hover #iconoImportante {
        transform: scale(1.3) rotate(10deg);
        transition: transform 0.3s ease;
    }

    @keyframes balanceoEstrella {
        0% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(15deg);
        }

        50% {
            transform: rotate(-15deg);
        }

        75% {
            transform: rotate(10deg);
        }

        100% {
            transform: rotate(0deg);
        }
    }

    #iconoImportante.balanceando {
        animation: balanceoEstrella 0.5s ease-in-out;
    }
</style>
<form id="formCliente" method="post">
    <input type="hidden" name="accion" value="<?= $esEdicion ? 'actualizar' : 'crear' ?>">
    <input type="hidden" name="id" value="<?= $id ?>">
    <!-- Contenido principal -->
    <div class="row g-4 align-items-stretch">
        <!-- Columna Izquierda -->
        <div class="col-md-6 d-flex flex-column gap-1">
            <!-- Información -->
            <div class="seccion-cliente h-100">
                <h5><i class="bi bi-person-badge me-2"></i>Información</h5>
                <!-- Nombre -->
                <div class="fila-dato">
                    <span class="dato-label">Nombre:</span>
                    <input type="text" class="form-control" id="nombre_cliente" name="nombre_cliente" value="<?= htmlspecialchars($msg['nombre_contacto']) ?>" <?= $soloLectura ? 'disabled' : '' ?> required>
                    <div class="invalid-feedback">Este campo es obligatorio</div>
                </div>
                <!-- Apellido -->
                <div class="fila-dato">
                    <span class="dato-label">Apellido:</span>
                    <input type="text" class="form-control" id="apellido_cliente" name="apellido_cliente" value="<?= htmlspecialchars($msg['apellido_contacto']) ?>" <?= $soloLectura ? 'disabled' : '' ?> required>
                    <div class="invalid-feedback">Este campo es obligatorio</div>
                </div>
                <!-- Tipo de persona -->
                <div class="fila-dato">
                    <span class="dato-label">Tipo Persona:</span>
                    <select class="form-select" aria-label="Tipo Persona" name="tipo_persona" required <?= $soloLectura ? 'disabled' : '' ?>>
                        <option value="" hidden selected>Seleccionar</option>
                        <option value="Natural" <?= $msg['tipo_persona'] === 'Natural' ? 'selected' : '' ?>>Natural</option>
                        <option value="Juridica" <?= $msg['tipo_persona'] === 'Juridica' ? 'selected' : '' ?>>Jurídica</option>
                    </select>
                    <div class="invalid-feedback">Selecciona una opción válida.</div>
                </div>
                <!-- Nombre empresa -->
                <div class="fila-dato">
                    <span class="dato-label">Nombre empresa:</span>
                    <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" value="<?= htmlspecialchars($msg['nombre_empresa']) ?>" <?= $soloLectura ? 'disabled' : '' ?> required>
                    <div class="invalid-feedback">Este campo es obligatorio</div>
                </div>
            </div>
            <!-- Contacto -->
            <div class="seccion-cliente h-100">
                <h5><i class="bi bi-envelope me-2"></i>Contacto</h5>
                <!-- Email -->
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-envelope-fill me-2 text-primary fs-5 mt-1"></i>
                    <div class="w-100 d-flex justify-content-between">
                        <span class="dato-label">Email:</span>
                        <input type="email" class="form-control w-75" id="email_contacto" name="email_contacto" value="<?= htmlspecialchars($msg['email_contacto']) ?>" <?= $soloLectura ? 'disabled' : '' ?> required>
                        <div class="invalid-feedback">Este campo es obligatorio</div>
                    </div>
                </div>
                <!-- Teléfono -->
                <div class="d-flex align-items-center mb-3">
                    <i class="bi bi-telephone-fill me-2 text-success fs-5 mt-1"></i>
                    <div class="w-100 d-flex justify-content-between">
                        <span class="dato-label">Teléfono:</span>
                        <input type="tel" class="form-control w-75" id="telefono_contacto" name="telefono_contacto" value="<?= htmlspecialchars($msg['telefono_contacto']) ?>" <?= $soloLectura ? 'disabled' : '' ?> required>
                        <div class="invalid-feedback">Este campo es obligatorio</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna Derecha -->
        <div class="col-md-6 d-flex flex-column gap-1">
            <!-- Activos -->
            <div class="seccion-cliente h-100">
                <h5><i class="bi bi-mortarboard me-2"></i>Activos</h5>
                <!-- Tipo de activo -->
                <div class="fila-dato">
                    <span class="dato-label">Tipo de Activo:</span>
                    <select class="form-select" id="tipo_activo" aria-label="Tipo de Activo" name="tipo_activo" required <?= $soloLectura ? 'disabled' : '' ?>>
                        <option value="" hidden selected>Seleccionar</option>
                        <option value="Propiedad Residencial" <?= $msg['tipo_activos'] === 'Propiedad Residencial' ? 'selected' : '' ?>>Propiedad Residencial</option>
                        <option value="Inmueble Comercial" <?= $msg['tipo_activos'] === 'Inmueble Comercial' ? 'selected' : '' ?>>Inmueble Comercial</option>
                        <option value="Activo Industrial" <?= $msg['tipo_activos'] === 'Activo Industrial' ? 'selected' : '' ?>>Activo Industrial</option>
                        <option value="Bien Especial" <?= $msg['tipo_activos'] === 'Bien Especial' ? 'selected' : '' ?>>Bien Especial</option>
                    </select>
                    <div class="invalid-feedback">Selecciona una opción válida.</div>
                </div>
                <!-- Detalle de activo -->
                <div class="fila-dato">
                    <span class="dato-label">Detalle Activo:</span>
                    <select class="form-select" id="detalle_activos" aria-label="Detalle de Activos" name="detalle_activos" required
                        data-valor="<?= $msg['detalle_activos'] ?? '' ?>"
                        <?= ($modo === 'ver') ? 'disabled' : '' ?>>
                    </select>
                    <div class="invalid-feedback">Selecciona una opción válida.</div>
                </div>
            </div>
            <div class="seccion-cliente h-100">
                <h5><i class="bi bi-briefcase me-2"></i>Notas</h5>
                <div class="fila-dato mt-3">
                    <textarea class="form-control" id="notas" name="notas" rows="10" <?= $soloLectura ? 'disabled' : '' ?>><?= htmlspecialchars($msg['notas']) ?></textarea>
                    <div class="invalid-feedback">Este campo es obligatorio.</div>
                </div>
            </div>
        </div>
    </div>


    <!-- Fila completa -->
    <div class="col-12">
        <div class="seccion-cliente">
            <h5><i class="bi bi-info-circle me-2"></i>Otros Detalles</h5>
            <div class="row">
                <div class="col-md-6 fila-dato">
                    <span class="dato-label">Usuario creador:</span>
                    <span class="dato-valor"><?= htmlspecialchars($msg['nombre_usuario_creador'] ?? '—') ?> (<?= htmlspecialchars($msg['email_usuario_creador'] ?? '') ?>)</span>
                </div>
                <div class=" col-md-6 fila-dato"><span class="dato-label">Estado:</span> <span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($msg['estado']) ?></span></div>
                <div class="col-md-6 fila-dato"><span class="dato-label">Fecha de Creación:</span> <?= formatearFecha($msg['fecha_creacion']) ?></div>
                <div class="col-md-6 fila-dato"><span class="dato-label">Última Modificación:</span> <?= formatearFecha($msg['fecha_modificacion']) ?></div>
            </div>
        </div>
    </div>
    </div>
</form>