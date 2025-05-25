<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/usuariosController.php';

$modoEdicion = false;
$rol = [
    'id' => '',
    'nombre' => '',
    'descripcion' => '',
    'permisos' => []
];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $rol = obtenerRolPorId(intval($_GET['id']));
    $modoEdicion = true;
}

$modulos = ['mensajes', 'postulaciones', 'clientes', 'usuarios', 'roles'];
$acciones = ['ver', 'modificar', 'crear', 'eliminar'];
?>

<div class="modal-dialog modal-lg">
    <form id="formRol" class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title"><?= $modoEdicion ? 'Editar Rol' : 'Crear Rol' ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <div class="mb-3">
                <label for="nombreRol" class="form-label">Nombre del Rol</label>

                <?php if ($modoEdicion): ?>
                    <!-- Muestra solo el texto en edición -->
                    <p class="form-control-plaintext"><?= htmlspecialchars($rol['nombre']) ?></p>
                    <!-- Campo oculto para que el valor se envíe en el formulario -->
                    <input type="hidden" name="nombre" value="<?= htmlspecialchars($rol['nombre']) ?>">
                <?php else: ?>
                    <!-- Campo editable para nuevo rol -->
                    <input type="text" class="form-control" id="nombreRol" name="nombre" required>
                <?php endif; ?>
            </div>
            <h6>Permisos por módulo:</h6>
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Módulo</th>
                            <?php foreach ($acciones as $accion): ?>
                                <th><?= ucfirst($accion) ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($modulos as $modulo): ?>
                            <tr>
                                <td class="text-start"><?= ucfirst($modulo) ?></td>
                                <?php foreach ($acciones as $accion): ?>
                                    <td>
                                        <input type="checkbox"
                                               class="form-check-input"
                                               name="permisos[<?= $modulo ?>][<?= $accion ?>]"
                                               <?= isset($rol['permisos'][$modulo][$accion]) ? 'checked' : '' ?>>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="id" value="<?= $rol['id'] ?>">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-primary"><?= $modoEdicion ? 'Actualizar' : 'Crear' ?> Rol</button>
        </div>
    </form>
</div>