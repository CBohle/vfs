<?php
require_once '../../includes/auth.php';
require_once __DIR__ . '/../../includes/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de administración</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .nav-link {
            cursor: pointer;
        }
        .dashboard-box {
            margin-top: 50px;
            flex: 1;
        }
        .badge {
            font-size: 0.75rem;
            margin-left: 5px;
            vertical-align: middle;
        }
        footer {
            background-color: #343a40;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
        .navbar-brand img {
            max-height: 40px;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="<?= BASE_URL ?>assets/images/logo/LogoVFS.jpg" alt="Logo VFS">
            VFS - Admin
        </a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link active" data-section="inicio">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="mensajes">
                        Mensajes
                        <span id="badge-mensajes" class="badge bg-danger d-none"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="postulaciones">
                        Postulaciones
                        <span id="badge-postulaciones" class="badge bg-danger d-none"></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-section="clientes">Clientes</a>
                </li>
                <li class="nav-item"><a class="nav-link" data-section="usuarios">Usuarios</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>logout.php">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Contenido dinámico -->
<div class="container dashboard-box" id="contenido-dinamico">
    <div class="text-center text-muted">
        <h4>Bienvenido al panel de administración</h4>
        <p>Selecciona una sección del menú para comenzar.</p>
    </div>
</div>

<!-- Footer -->
<footer>
    © 2024 VFS. Todos los derechos reservados.
</footer>

<!-- Scripts de DataTables -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function () {
    // Carga de secciones al hacer clic
    $('.nav-link[data-section]').click(function (e) {
        e.preventDefault();
        const section = $(this).data('section');
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
        if (section === 'inicio') {
            $('#contenido-dinamico').load('inicioResumen.php');
        } else {
            $('#contenido-dinamico').load(section + '.php');
        }
    });

    // Cargar inicioResumen.php automáticamente al abrir la página
    $('#contenido-dinamico').load('inicioResumen.php');

    // Notificaciones
    function actualizarNotificaciones() {
        $.ajax({
            url: 'notificaciones.php',
            type: 'GET',
            success: function(data) {
                if (data.mensajes > 0) {
                    $('#badge-mensajes').text(data.mensajes).removeClass('d-none');
                } else {
                    $('#badge-mensajes').addClass('d-none');
                }
                if (data.postulaciones > 0) {
                    $('#badge-postulaciones').text(data.postulaciones).removeClass('d-none');
                } else {
                    $('#badge-postulaciones').addClass('d-none');
                }
            }
        });
    }

    actualizarNotificaciones();
    setInterval(actualizarNotificaciones, 30000); // cada 30 segundos
});
</script>

</body>
</html>
