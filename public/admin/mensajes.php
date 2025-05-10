<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php'; // ← Activa cuando uses base de datos real
require_once __DIR__ . '/../../includes/Controller/mensajesController.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mensajes de Contacto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: "Segoe UI", sans-serif;
        }
        .container {
            margin-top: 50px;
            max-width: 1100px;
        }
        .table thead {
            background-color: #e9ecef;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.85rem;
        }
        .card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body>
<div class="container">
    <h3 class="mb-4 text-muted">Mensajes de Contacto</h3>

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
                        <th>Actualizar Estado</th>
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
                                     ($msg['estado'] === 'leido' ? 'bg-warning text-dark' : 'bg-secondary') ?>">
                                <?= ucfirst($msg['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" class="d-flex gap-2 align-items-center">
                                <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                                <select name="estado" class="form-select form-select-sm">
                                    <option value="pendiente" <?= $msg['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                                    <option value="leído" <?= $msg['estado'] === 'leido' ? 'selected' : '' ?>>Leído</option>
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

    <a href="dashboard.php" class="btn btn-outline-secondary mt-4">← Volver al Dashboard</a>
</div>
</body>
</html>
