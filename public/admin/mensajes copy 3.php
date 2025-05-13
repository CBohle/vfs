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
          margin: 0;
          padding: 0;
        }

        .header2 .tag {
          background: #cfc5fd;
          color: #4c3fb2;
          border-radius: 8px;
          font-size: 12px;
          padding: 2px 6px;
          margin-left: 8px;
        }
        .header2 h3 {
          font-size: 16px;
        }
        .header2 .email {
          font-size: 12px;
          color:rgb(124, 120, 120);
          margin-left: 8px;
        }
        .header2-info{
          display: flex;
          align-items: start;
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
        .rol{
          font-size: 12px;
          color: #888;
          margin-left: 8px;
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
    <a href="dashboard.php" class="btn btn-outline-secondary mt-4">← Volver al Dashboard</a>    
    </div>
    <div class="container">
    <?php foreach ($mensajes as $msg): ?>
        <div class="card2">
          <div class="header2">
            <div class="header2-info">
              <div>
                <h3 class="name"><?= htmlspecialchars($msg['nombre']) ?> <?= htmlspecialchars($msg['apellido']) ?></h3>
                <h4 class="email"><?= htmlspecialchars($msg['email']) ?></h4>
              </div>
              <span class="project"><?= htmlspecialchars($msg['servicio']) ?></span>
            </div>
            <div>
              <span class="tag updated"><?= htmlspecialchars($msg['estado']) ?></span>
              <span class="time">Now</span>
            </div>
          </div>
          <div class="content">
            <span class="task"><?= htmlspecialchars($msg['mensaje']) ?></span>
          </div>
          <?php if ($msg['estado'] == 'respondido'): ?>
          <div class="reply">
            <div class="reply-box">
              <div class="reply-header">
                <span class="reply-name"><?= htmlspecialchars($msg['admin_nombre']) ?> <?= htmlspecialchars($msg['admin_apellido']) ?></span>
                <span class="rol"><?= htmlspecialchars($msg['admin_rol']) ?></span>
              </div>
              <span class="reply-text"><?= htmlspecialchars($msg['respuesta']) ?></span>
              <small class="text-muted"><?= htmlspecialchars($msg['fecha_respuesta']) ?></small>
            </div>
          </div>
          <?php elseif ($msg['estado'] !== 'respondido'): ?>
            <button class="btn btn-sm btn-outline-primary mt-2" onclick="toggleReplyForm(<?= $msg['id'] ?>)">Responder</button>
            <form id="reply-form-<?= $msg['id'] ?>" class="mt-2 d-none" method="POST" action="responder.php">
                <input type="hidden" name="mensaje_id" value="<?= $msg['id'] ?>">
                <textarea name="respuesta" class="form-control mb-2" required placeholder="Escribe tu respuesta..."></textarea>
                <button type="submit" class="btn btn-success btn-sm">Enviar respuesta</button>
            </form>
          <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php if (empty($mensajes)): ?>
      <h3 colspan="6" class="text-center text-muted">No hay mensajes disponibles.</h3>
    <?php endif; ?>
    </div>
  </div>
  <script>
    function toggleReplyForm(id) {
      const form = document.getElementById('reply-form-' + id);
      form.classList.toggle('d-none');
    }
  </script>
</body>
</html>
