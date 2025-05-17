<?php
if (empty($mensajes)) {
    echo '<div class="text-muted">No hay mensajes.</div>';
    return;
}

foreach ($mensajes as $i => $msg):
    $estadoClass = obtenerClaseEstado($msg['estado'] ?? '');
?>
<div class="mb-3">
    <div class="header2">
        <div class="header2-info">
            <div>
                <h4 class="name mb-1"><?= htmlspecialchars($msg['nombre'] ?? '') ?> <?= htmlspecialchars($msg['apellido'] ?? '') ?></h4>
                <div class="email"><?= htmlspecialchars($msg['email'] ?? '') ?></div>
            </div>
            <span class="project"><?= htmlspecialchars($msg['servicio'] ?? '') ?></span>
        </div>
        <div class="text-end">
            <span class="badge <?= $estadoClass ?>"><?= htmlspecialchars($msg['estado'] ?? '') ?></span>
            <small class="text-muted ms-2"><?= htmlspecialchars($msg['fecha_creacion'] ?? '') ?></small>
        </div>
    </div>
    <p class="mb-2"><?= htmlspecialchars($msg['mensaje'] ?? '') ?></p>
    <?php if (!empty($msg['respuesta'])): ?>
        <div class="reply">
            <div class="reply-box" style="width: 100%; position: relative;">
                <div class="reply-header">
                    <span class="reply-name"><?= htmlspecialchars($msg['admin_nombre'] ?? '') ?> <?= htmlspecialchars($msg['admin_apellido'] ?? '') ?></span>
                    <span class="rol"><?= htmlspecialchars($msg['admin_rol'] ?? '') ?></span>
                    <span class="text-muted"><?= htmlspecialchars($msg['fecha_respuesta'] ?? '') ?></span>
                </div>
                <span class="reply-text"><?= htmlspecialchars($msg['respuesta'] ?? '') ?></span>
            </div>
        </div>
    <?php else: ?>
        <button class="btn btn-sm btn-primary mt-2" id="btn-responder-<?= htmlspecialchars($msg['id']) ?>" onclick="this.style.display='none'; toggleReplyForm(<?= htmlspecialchars($msg['id']) ?>)">Responder</button>
        <form id="reply-form-<?= htmlspecialchars($msg['id']) ?>" class="mt-2 d-none" method="POST" action="responder.php">
            <input type="hidden" name="mensaje_id" value="<?= htmlspecialchars($msg['id']) ?>">
            <textarea name="respuesta" class="form-control mb-2" required placeholder="Escribe tu respuesta..."></textarea>
            <button type="submit" class="btn btn-primary btn-sm">Enviar respuesta</button>
        </form>
    <?php endif; ?>
</div>
<?php if ($i < count($mensajes) - 1): ?>
<hr>
<?php endif; ?>
<?php endforeach; ?>