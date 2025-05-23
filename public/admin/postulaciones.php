<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';

// Consultar datos reales
$sql = "SELECT id, nombre, apellido, email, telefono, region, comuna, estudio, institucion_educacional, ano_titulacion, anos_experiencia_tasacion, archivo, fecha_creacion 
        FROM curriculum 
        ORDER BY fecha_creacion DESC";

$resultado = $conexion->query($sql);
?>

<h4 class="mb-4 text-muted">Postulaciones Recibidas</h4>

<div class="card p-4">
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Región</th>
                    <th>Estudios</th>
                    <th>Año Titulación</th>
                    <th>Experiencia</th>
                    <th>CV</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($resultado && $resultado->num_rows > 0): ?>
                    <?php while ($fila = $resultado->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars(date('d-m-Y', strtotime($fila['fecha_creacion']))) ?></td>
                            <td><?= htmlspecialchars($fila['nombre'] . ' ' . $fila['apellido']) ?></td>
                            <td><?= htmlspecialchars($fila['email']) ?></td>
                            <td><?= htmlspecialchars($fila['telefono']) ?></td>
                            <td><?= htmlspecialchars($fila['region'] . ' - ' . $fila['comuna']) ?></td>
                            <td><?= htmlspecialchars($fila['estudio'] . ' en ' . $fila['institucion_educacional']) ?></td>
                            <td><?= htmlspecialchars($fila['ano_titulacion']) ?></td>
                            <td><?= htmlspecialchars($fila['anos_experiencia_tasacion']) ?> año(s)</td>
                            <td>
                                <?php if (!empty($fila['archivo'])): ?>
                                  <a href="../../<?= htmlspecialchars($fila['archivo']) ?>" target="_blank" class="btn btn-sm btn-primary">Ver CV</a>
                                <?php else: ?>
                                    <span class="text-muted">No adjunto</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">No hay postulaciones registradas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
