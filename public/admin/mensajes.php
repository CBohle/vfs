<?php
require_once __DIR__ . '/../../includes/auth.php';
// require_once __DIR__ . '/../../includes/db.php'; // ← Activa cuando uses base de datos real

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['estado'])) {
    $id = $_POST['id'];
    $nuevoEstado = $_POST['estado'];

    // Consulta real:
    /*
    $stmt = $conn->prepare("UPDATE mensajes SET estado = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevoEstado, $id);
    $stmt->execute();
    $stmt->close();
    */
    // Si se envía por AJAX no redirijas.
    exit;
}

// Simulación de mensajes (reemplazar por BD)
$mensajes = [
    ["id" => 1, "nombre" => "Juan Pérez", "email" => "juan@mail.com", "fecha" => "2025-05-03", "mensaje" => "Hola, quiero más info", "estado" => "pendiente"],
    ["id" => 2, "nombre" => "Ana López", "email" => "ana@mail.com", "fecha" => "2025-05-02", "mensaje" => "Consulta sobre servicios", "estado" => "leído"],
    ["id" => 3, "nombre" => "Carlos Rojas", "email" => "carlos@mail.com", "fecha" => "2025-05-01", "mensaje" => "Quiero agendar", "estado" => "respondido"]
];
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
                        <span class="badge
                            <?= $msg['estado'] === 'respondido' ? 'bg-success' :
                                 ($msg['estado'] === 'leído' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                            <?= ucfirst($msg['estado']) ?>
                        </span>
                    </td>
                    <td>
                        <form method="POST" class="d-flex gap-2 align-items-center">
                            <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                            <select name="estado" class="form-select form-select-sm">
                                <option value="pendiente" <?= $msg['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                <option value="leído" <?= $msg['estado'] === 'leído' ? 'selected' : '' ?>>Leído</option>
                                <option value="respondido" <?= $msg['estado'] === 'respondido' ? 'selected' : '' ?>>Respondido</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-outline-dark">Guardar</button>
                        </form>
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
