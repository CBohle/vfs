<!-- ESTRUCTURA GENERAL DEL DASHBOARD: NAV Y SIDEBAR -->
<?php
require_once '../includes/auth.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/Controller/usuariosController.php';
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
    <script>
        const BASE_ADMIN_URL = "<?= BASE_ADMIN_URL ?>";
        const ROL_ID = <?= $_SESSION['rol_id'] ?>;
        const PERMISOS = <?= json_encode($_SESSION['permisos'] ?? []) ?>;
        //console.log("PERMISOS cargados desde dashboard:", PERMISOS);
    </script>
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
                        <li class="nav-item">
                        <a class="nav-link active" data-section="inicioResumen">
                            <i class="bi bi-house fs-6" style="margin-right: 5px"></i>
                            Dashboard
                        </a>
                    </li>
                    
                    <?php if (tienePermiso('mensajes', 'ver')): ?>
                        <li class="nav-item">
                            <a class="nav-link" data-section="mensajes">
                                <i class="bi bi-chat-square-text fs-6" style="margin-right: 5px"></i>
                                Mensajes <span id="badge-mensajes" class="badge bg-danger d-none"></span>
                            </a>
                        </li>
                        
                    <?php endif; ?>
                   
                  <?php if (tienePermiso('postulaciones', 'ver')): ?>
    <li class="nav-item">
        <a class="nav-link" data-section="postulaciones">
            <i class="bi bi-bookmark-check fs-6" style="margin-right: 5px"></i>
            Postulaciones <span id="badge-postulaciones" class="badge bg-danger d-none"></span>
        </a>
    </li>
<?php endif; ?>
  <?php if (tienePermiso('clientes', 'ver')): ?>
        <li class="nav-item">
                        <a class="nav-link" data-section="clientes">
                            <i class="bi bi-people-fill fs-6" style="margin-right: 5px"></i>
                            Clientes
                        </a>
                    </li>
    <?php endif; ?>
                    
                <?php if (tienePermiso('usuarios', 'ver') || tienePermiso('roles', 'ver')): ?>    
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
        <div class="main-content" id="contenido-dinamico"></div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Función centralizada para cargar cualquier sección
        function cargarSeccion(section) {
            $('.nav-link').removeClass('active');
            $('.nav-link[data-section="' + section + '"]').addClass('active');

            $('#contenido-dinamico').empty();

            $('#contenido-dinamico').load(section + '.php', function () {
                if (sessionStorage.getItem('recargaExitosa') === '1') {
                    sessionStorage.removeItem('recargaExitosa');
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: 'Cambios aplicados correctamente',
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });
                }

                // Scripts específicos por sección
                const scripts = {
                    mensajes: {
                        src: 'adminlte/assets/js/mensajes.js',
                        onload: () => {
                            if (typeof cargarVistaMensajes === 'function') cargarVistaMensajes();
                        }
                    },
                    postulaciones: {
                        src: 'adminlte/assets/js/postulaciones.js',
                        onload: () => {
                            delete window.estadoFiltroPostulaciones;
                            if (typeof cargarVistaPostulaciones === 'function') cargarVistaPostulaciones();
                        }
                    },
                    clientes: {
                        src: 'adminlte/assets/js/clientes.js',
                        onload: () => {
                            const intervalo = setInterval(() => {
                                if (document.querySelector('#tablaClientes')) {
                                    clearInterval(intervalo);
                                    if (typeof inicializarTablaClientes === 'function') inicializarTablaClientes();
                                }
                            }, 100);
                        }
                    },
                    usuarios: {
                        src: 'adminlte/assets/js/usuarios.js',
                        onload: () => {
                            if (typeof inicializarVistaUsuarios === 'function') inicializarVistaUsuarios();
                        }
                    }
                };

                if (scripts[section]) {
                    $('script[src*="' + section + '.js"]').remove();
                    const script = document.createElement('script');
                    script.src = BASE_ADMIN_URL + scripts[section].src + '?v=' + new Date().getTime();
                    script.onload = scripts[section].onload;
                    document.body.appendChild(script);
                }
            });
        }

        $(document).ready(function () {
            const urlParams = new URLSearchParams(window.location.search);
            const seccionParam = urlParams.get('seccion');

            if (seccionParam) {
                cargarSeccion(seccionParam);
            } else {
                cargarSeccion('inicioResumen');
            }

            // Menú lateral: navegación
            $('.nav-link[data-section]').click(function (e) {
                e.preventDefault();
                const section = $(this).data('section');
                cargarSeccion(section);
            });

            // Botones personalizados en tarjetas
            $('#verMensajes').click(() => cargarSeccion('mensajes'));
            $('#verPostulaciones').click(() => cargarSeccion('postulaciones'));

            // Notificaciones
            function actualizarNotificaciones() {
                $.ajax({
                    url: 'notificaciones.php',
                    type: 'GET',
                    success: function (data) {
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