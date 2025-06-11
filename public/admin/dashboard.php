<!-- ESTRUCTURA GENERAL DEL DASHBOARD: NAV Y SIDEBAR -->
<?php
require_once '../../includes/auth.php';
require_once __DIR__ . '/../../includes/config.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel de administración</title>
    <!-- Bootstrap y estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>admin/adminlte/css/stylesDash.css" rel="stylesheet" />
    <!-- Fuentes de texto -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lora:ital,wght@0,400..700;1,400..700&family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Raleway:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- Otros -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- Libreria íconos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-text navbar-blue fixed-top">
        <div class="container-fluid px-4">
            <!-- Logo -->
            <div class="d-flex align-items-center">
                <img src="<?= BASE_URL ?>assets/images/logo/LogoVFS2.png" alt="Logo VFS" class="img-fluid me-2 logo" style="max-height: 40px;">
            </div>
            <!-- Título central -->
            <div class="d-flex align-items-center">
                <h4 class="m-0">Panel de Administración</h4>
            </div>
            <!-- Botón hamburguesa -->
            <button class="btn btn-outline me-3" id="toggleSidebar">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </nav>

    <div class="wrapper bg-light">
            <!-- Sidebar -->
            <div class="sidebar sidebar-text" id="sidebar">
                <ul class="nav flex-column">
                     <?php if (in_array($_SESSION['rol_id'], [1, 5])): ?> 
                        <li class="nav-item">
                        <a class="nav-link active" data-section="inicio">
                            <i class="bi bi-house fs-6" style="margin-right: 5px"></i>
                            Dashboard
                        </a>
                    </li>
                        <?php endif; ?>
                    
                    <?php if (in_array($_SESSION['rol_id'], [1,3, 4, 5])): ?>
                         <li class="nav-item">
                        <a class="nav-link" data-section="mensajes">
                            <i class="bi bi-chat-square-text fs-6" style="margin-right: 5px"></i>
                            Mensajes <span id="badge-mensajes" class="badge bg-danger d-none"></span>
                        </a>
                    </li>
                        
                        <?php endif; ?>
                   
                  <?php if (in_array($_SESSION['rol_id'], [1, 2, 5])): ?>
    <li class="nav-item">
        <a class="nav-link" data-section="postulaciones">
            <i class="bi bi-bookmark-check fs-6" style="margin-right: 5px"></i>
            Postulaciones <span id="badge-postulaciones" class="badge bg-danger d-none"></span>
        </a>
    </li>
<?php endif; ?>
  <?php if (in_array($_SESSION['rol_id'], [1,3,4,5])): ?> 
        <li class="nav-item">
                        <a class="nav-link" data-section="clientes">
                            <i class="bi bi-people-fill fs-6" style="margin-right: 5px"></i>
                            Clientes
                        </a>
                    </li>
    <?php endif; ?>
                    
                    <?php if ($_SESSION['rol_id'] === 1): ?>
                    <li class="nav-item">
                        <a class="nav-link" data-section="usuarios">
                            <i class="bi bi-person-vcard fs-6" style="margin-right: 5px"></i>
                            Usuarios
                        </a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>logout.php">
                            <i class="bi bi-arrow-bar-left fs-6" style="margin-right: 5px"></i>
                            Cerrar sesión
                        </a>
                    </li>
                </ul>
            </div>
        
        <!-- Contenido dinámico -->
        <div class="main-content" id="contenido-dinamico">
            <div class="main-content" id="contenido-dinamico">
                <div class="text-center text-muted">
                    <h4>Bienvenido al panel de administración</h4>
                    <p>Selecciona una sección del menú para comenzar.</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Footer -->


    <!-- Scripts de DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // Cargar sección por defecto
            $('#contenido-dinamico').load('inicioResumen.php');

            // Navegación entre secciones
            $('.nav-link[data-section]').click(function(e) {
                e.preventDefault();
                const section = $(this).data('section');
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
                if (section === 'inicio') {
                    $('#contenido-dinamico').load('inicioResumen.php');
                } else {
                    $('#contenido-dinamico').load(section + '.php');
                }

                // Ocultar sidebar en móvil al hacer clic
                if (window.innerWidth <= 768) {
                    $('#sidebar').removeClass('show');
                }
            });

            // Botón para mostrar/ocultar sidebar en móvil
            // $('#toggleSidebar').click(function() {
                // $('#sidebar').toggleClass('show');
            // });

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
    <!-- Boton hamburguesa -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleSidebar');

        function handleResize() {
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('hidden');
                sidebar.classList.remove('collapsed');
                sidebar.classList.remove('show'); // Oculto por defecto
            } else {
                sidebar.classList.remove('show');
                sidebar.classList.remove('hidden'); // Mostrado por defecto
            }
        }

        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                // En pantallas pequeñas, togglear clase show
                sidebar.classList.toggle('show');
            } else {
                // En pantallas grandes, togglear clase hidden
                sidebar.classList.toggle('hidden');
            }
        });

        window.addEventListener('resize', handleResize);
        handleResize();
    </script>


</body>

</html>