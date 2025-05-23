<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['estado'])) {
    $id = mysqli_real_escape_string($conexion, $_POST['id']);
    $estado = mysqli_real_escape_string($conexion, $_POST['estado']);
    mysqli_query($conexion, "UPDATE mensajes SET estado = '$estado' WHERE id = $id");
    echo json_encode(['status' => 'ok']);
    exit;
}

$sql = "SELECT id, CONCAT(nombre, ' ', apellido) AS nombre, email, mensaje, estado, fecha_creacion AS fecha
        FROM mensajes
        WHERE estado != 'eliminado'
        ORDER BY fecha_creacion DESC";
$resultado = mysqli_query($conexion, $sql);

$mensajes = [];
while ($fila = mysqli_fetch_assoc($resultado)) {
    $mensajes[] = $fila;
}
?>

<h4 class="mb-4 text-muted">Mensajes de Contacto</h4>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Mensaje</th>
                    <th>Estado</th>
                    <th>Actualizar</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mensajes as $msg): ?>
                <tr>
                    <td><?= htmlspecialchars($msg['fecha']) ?></td>
                    <td><?= htmlspecialchars($msg['nombre']) ?></td>
                    <td><?= htmlspecialchars($msg['email']) ?></td>
                    <td><?= htmlspecialchars($msg['mensaje']) ?></td>
                    <td>
                        <span class="badge estado-badge <?= getBadgeClass($msg['estado']) ?>" data-id="<?= $msg['id'] ?>">
                            <?= ucfirst($msg['estado']) ?>
                        </span>
                    </td>
                    <td>
                        <select class="form-select form-select-sm estado-selector" data-id="<?= $msg['id'] ?>">
                            <option value="pendiente" <?= $msg['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                            <option value="leído" <?= $msg['estado'] === 'leído' ? 'selected' : '' ?>>Leído</option>
                            <option value="respondido" <?= $msg['estado'] === 'respondido' ? 'selected' : '' ?>>Respondido</option>
                        </select>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($mensajes)): ?>
                <tr><td colspan="6" class="text-center text-muted">No hay mensajes disponibles.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.querySelectorAll('.estado-selector').forEach(select => {
    select.addEventListener('change', () => {
        const id = select.dataset.id;
        const estado = select.value;

        fetch('mensajes.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}&estado=${estado}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'ok') {
                const badge = document.querySelector(`.estado-badge[data-id="${id}"]`);
                badge.className = 'badge estado-badge ' + getBadgeClassJS(estado);
                badge.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
            }
        });
    });
});

function getBadgeClassJS(estado) {
    if (estado === 'respondido') return 'bg-success';
    if (estado === 'leído') return 'bg-warning text-dark';
    if (estado === 'pendiente') return 'bg-secondary';
    return 'bg-light';
}
</script>

<?php
function getBadgeClass($estado) {
    return match ($estado) {
        'respondido' => 'bg-success',
        'leído' => 'bg-warning text-dark',
        'pendiente' => 'bg-secondary',
        default => 'bg-light',
    };
}
?>
