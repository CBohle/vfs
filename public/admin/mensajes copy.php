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

        .card2 {
          background: white;
          border-radius: 16px;
          padding: 16px;
          margin-bottom: 20px;
          box-shadow: 0 4px 8px rgba(0,0,0,0.05);
          position: relative;
        }
        
        
        .header2 {
          display: flex;
          align-items: center;
          justify-content: space-between;
        }

        .header2 .name {
          font-weight: bold;
        }

        .header2 .tag {
          background: #cfc5fd;
          color: #4c3fb2;
          border-radius: 8px;
          font-size: 12px;
          padding: 2px 6px;
          margin-left: 8px;
        }
        .text-light {
          font-size: 12px;
          color:rgb(192, 30, 30);
          margin-left: 4px;
        }
        .project {
          background: #fcd663;
          padding: 4px 8px;
          border-radius: 6px;
          font-size: 13px;
          font-weight: 500;
          display: inline-block;
          margin-bottom: 4px;
        }

        .task {
          display: block;
          font-size: 15px;
          margin-top: 4px;
          margin-bottom: 8px;
        }

        .info {
          font-size: 12px;
          color: #888;
          display: flex;
          gap: 10px;
          flex-wrap: wrap;
        }

        .reply {
          background: #f4f7fb;
          border-radius: 12px;
          padding: 10px;
          display: flex;
          align-items: flex-start;
          gap: 10px;
          margin-top: 12px;
        }

        .reply img {
          width: 30px;
          height: 30px;
          border-radius: 50%;
        }

        .reply-name {
          font-weight: 500;
        }

        .reply-text {
          font-size: 14px;
        }
        
        .chat-btn, .star-btn {
          background: #d6f3fb;
          border: none;
          border-radius: 50%;
          width: 28px;
          height: 28px;
          cursor: pointer;
          position: relative;
        }

        .star-btn::after {
          content: attr(data-count);
          position: absolute;
          top: -6px;
          right: -6px;
          font-size: 10px;
          background: #eee;
          color: #000;
          padding: 2px 4px;
          border-radius: 8px;
        }

        .star-btn {
          background: #f1e9fe;
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
                            <td><?= htmlspecialchars($msg['fecha_creacion']) ?></td>
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

    <div class="container">
      <div class="card2">
        <div class="header2">
          <div>
            <div>
              <span class="name">Jhonas lean</span>
              <span class="text-light">The issue</span>
            </div>
            <span class="project">Projectx-3</span>
          </div>
          <div>
            <span class="tag updated">Updated</span>
            <span class="time">Now</span>
          </div>
        </div>
        <div class="content">
          <span class="task">Design a website for gpt-10</span>
        </div>
        <div class="reply">
          <div class="reply-box">
            <div>
              <span class="reply-name">Thomas lean</span>
              <span class="text-light">The issue</span>
            </div>
            <span class="reply-text">Thank you for rapping up this today! 😊</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
