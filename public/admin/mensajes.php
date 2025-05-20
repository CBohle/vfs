<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/mensajesController.php';

$mensajes = ver_mensajes();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mensajes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        #tablaMensajes {
            display: none;
        }

        .loader {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 150px;
            font-size: 1.2rem;
            color: #555;
        }
    </style>
</head>

<body class="p-4">
    <h2 class="mb-4">Mensajes Recibidos</h2>

    <!-- Loader -->
    <div id="loaderTabla" class="loader">
        Cargando tabla...
    </div>

    <div class="card p-3">
        <table id="tablaMensajes" class="table table-striped datatables-basic w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Servicio</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Mensaje</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($mensajes as $mensaje): ?>
                    <tr>
                        <td><?= htmlspecialchars($mensaje['mensaje_id']) ?></td>
                        <td><?= htmlspecialchars($mensaje['servicio']) ?></td>
                        <td><?= htmlspecialchars($mensaje['nombre']) ?></td>
                        <td><?= htmlspecialchars($mensaje['email']) ?></td>
                        <td><?= htmlspecialchars(substr($mensaje['mensaje'], 0, 40)) ?>...</td>
                        <td>
                            <span class="badge <?= obtenerClaseEstado($mensaje['estado']) ?>">
                                <?= ucfirst($mensaje['estado']) ?>
                            </span>
                        </td>
                        <td><?= date('d-m-Y', strtotime($mensaje['fecha_creacion'])) ?></td>
                        <td class="d-flex gap-1">
                            <?php if ($mensaje['estado'] !== 'eliminado'): ?>
                                <!-- Botón Editar -->
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                    <i class="bi bi-eye"></i></button>
                                <!-- Botón Eliminar -->
                                <form method="POST" action="mensajes.php" onsubmit="return confirm('¿Estás seguro de eliminar este mensaje?');">
                                    <input type="hidden" name="id" value="<?= $mensaje['mensaje_id'] ?>">
                                    <input type="hidden" name="estado" value="eliminado">
                                    <button class="btn btn-danger" type="submit" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Título del Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>Este es un ejemplo funcional de contenido de modal.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <!--Primero dejar Jquery y luego bootstrap-->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <script>
         $(document).ready(function () {
        const tabla = $('#tablaMensajes');

        try {
            tabla.DataTable({
                responsive: true,
                dom: 'Blfrtip',
                buttons: [
                    { extend: 'copy', text: 'Copiar' },
                    { extend: 'excel', text: 'Excel' }
                ],
                lengthMenu: [10, 30, 50, 100],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                initComplete: function () {
                    $('#loaderTabla').hide();
                    tabla.fadeIn();
                }
            });
        } catch (error) {
            console.error('Error al inicializar DataTable:', error);
            $('#loaderTabla').hide();  // Oculta loader aunque falle
            tabla.fadeIn();
        }
    });
    </script>
</body>

</html>