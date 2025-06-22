<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/Controller/clientesController.php';
require_once __DIR__ . '/../includes/auth.php';
/* Verifica si el usuario tiene permisos de administrador
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
    header('Location: /admin/login.php');
    exit;
} */
if (!tienePermiso('clientes', 'ver')) {
    echo '
        <div class="container my-5">
            <div class="alert alert-danger text-center p-4" role="alert" style="font-size: 1.25rem;">
                <i class="bi bi-shield-lock-fill fs-1 mb-2 d-block"></i>
                <strong>Acceso denegado</strong><br>
                No tienes permiso para ver esta sección.
            </div>
        </div>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-size: 1rem;
        }

        table.dataTable tbody tr:hover {
            background-color: #f8f9fa;
        }

        a.marcarImportante {
            cursor: pointer;
            text-decoration: none;
        }

        div.dataTables_filter {
            display: none;
        }

        .col-mensaje {
            width: 25%;
            white-space: normal;
        }

        .truncado-3-lineas {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            line-clamp: 3;
            /* ← esta línea soluciona la advertencia */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal;
            line-height: 1.2em;
            max-height: 3.6em;
            text-align: justify;
        }

        .navbar {
            font-size: 1.0rem;
        }

        #contenido-dinamico {
            width: 100%;
            max-width: 1700px;
            /* Limita el ancho en desktop */
            padding: 1rem;
            margin-top: 3px;
            margin-bottom: 3px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
        }
    </style>
    <script>
        window.PERMISOS = window.PERMISOS || <?= json_encode($_SESSION['permisos'] ?? []) ?>;
    </script>
</head>

<body class="bg-light">
    <div class="container-fluid px-3 py-2">
        <div id="contenido-dinamico" class="mx-auto w-100" style="max-width: 100%;">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 gap-sm-3 gap-md-4">
                <h2 class="mb-0">Clientes</h2>
                <!-- Botones de exportación -->
                <div id="exportButtons" class="d-flex gap-1"></div>
            </div>
            <!-- Filtros -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filtros</h5>
                </div>
                <div class="card-body">
                    <form class="d-flex justify-content-between flex-wrap align-items-center gap-3 mt-0 pt-0">
                        <!-- Grupo 1: Filtros -->
                        <div class="d-flex flex-wrap gap-3">
                            <div class="form-floating">
                                <select class="form-select" id="filtro_estado" aria-label="Estado">
                                    <option value="" hidden>Seleccionar</option>
                                    <option value="Activo">Activo</option>
                                    <option value="Inactivo">Inactivo</option>
                                    <option value="Eliminado">Eliminado</option>
                                </select>
                                <label for="filtro_estado">Estado</label>
                            </div>
                            <!--<div class="form-floating">
                                <select class="form-select form-select-sm" id="filtro_orden" aria-label="Orden">
                                    <option value="DESC" hidden selected>Seleccionar</option>
                                    <option value="DESC">Más Reciente</option>
                                    <option value="ASC">Más Antiguo</option>
                                </select>
                                <label for="filtro_orden">Orden</label>
                            </div>-->
                            <div class="form-floating">
                                <select class="form-select form-select-sm" id="filtro_importante" aria-label="Importante">
                                    <option value="" hidden selected>Seleccionar</option>
                                    <option value="1">Sí</option>
                                    <option value="0">No</option>
                                </select>
                                <label for="filtro_importante">Importante</label>
                            </div>
                            <div class="form-floating d-flex align-items-center">
                                <div class="d-flex align-items-end gap-2">
                                    <div>
                                        <button class="btn btn-primary btn-sm" type="button" onclick="filtrar()">Filtrar</button>
                                    </div>
                                    <div>
                                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="resetearFiltros()">Borrar filtros</button>
                                    </div>
                                </div>
                                <label class="d-none">Acciones</label>
                            </div>
                        </div>
                        <!-- Grupo 2: Buscar -->
                        <div class="form-floating">
                            <input type="text" class="form-floating form-control form-control-sm" id="filtro_busqueda" placeholder="Buscar palabra clave">
                            <label for="filtro_busqueda">Buscar palabra clave</label>
                        </div>
                        <!-- Grupo 3: Crear nuevo cliente -->
                        <div class="form-floating align-items-center">
                            <label class="form-label d-block invisible">.</label>
                            <button type="button" class="btn btn-info btn-sm w-100" style="color:rgb(255, 255, 255)" onclick="crearCliente()">
                                <h6 class="mb-1 fw-semibold">
                                    <span id="ClientePorCrear">Nuevo Cliente</span>
                                </h6>
                            </button>
                        </div>   

                    </form>
                </div>
            </div>
            <!-- Tabla clientes -->
            <div class="card-body">
                <div class="row gy-2 gx-3 align-items-end justify-content-between flex-wrap">
                    <section id="tabla-clientes">
                        <div class="card">
                            <div class="card-body">
                                <div id="loaderTabla" class="text-center my-3">
                                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div>
                                    <p class="mt-2 mb-0 text-muted">Cargando tabla...</p>
                                </div>
                                <div class="table-responsive" style="overflow-x: auto;">
                                    <table id="tablaClientes" class="table table-striped table-hover align-middle nowrap w-100" style="min-width: 1200px;">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center">★</th>
                                                <th class="d-none">Importante</th> <!-- columna oculta -->
                                                <th>ID</th>
                                                <th>Tipo de<br>Persona</th>
                                                <th>Nombre de<br>Empresa</th>
                                                <th>Nombre de<br>Contacto</th>
                                                <th>Correo</th>
                                                <th>Telefono</th>
                                                <th>Tipo de<br>Activo</th>
                                                <th>Detalle de<br>Activo</th>
                                                <th>Estado</th>
                                                <th class="d-none">Fecha Creación</th> <!-- columna oculta -->
                                                <th class="d-none">Fecha Modificación</th> <!-- columna oculta -->
                                                <th class="d-none">Email Usuario Creador</th> <!-- columna oculta -->
                                                <th class="d-none">Nombre Usuario Creador</th> <!-- columna oculta -->
                                                <th class="d-none">Notas</th> <!-- columna oculta -->
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para Ver Clientes -->
    <div class="modal fade" id="modalVerCliente" tabindex="-1" aria-labelledby="modalVerClienteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable modal-fullscreen-sm-down">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h5 class="modal-title mb-0 px-3" id="modalVerClienteLabel">Detalle del cliente</h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div id="botonImportanteWrapper" style="min-width: 200px;"></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                </div>
                <!-- Modal Body -->
                <div class="modal-body" id="contenidoModalCliente">
                    <p class="text-center text-muted">Cargando...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        window.tablaClientes = window.tablaClientes || null;

        function inicializarTablaClientes() {
            if (!$.fn.DataTable.isDataTable('#tablaClientes')) {
                tablaClientes = $('#tablaClientes').DataTable({
                    rowId: function(row) {
                        return 'row_' + row.id;
                    },
                    responsive: false,
                    scrollX: true,
                    processing: true,
                    serverSide: true,
                    destroy: true,
                    ajax: {
                        url: 'clientesAjax.php',
                        type: 'POST',
                        data: function(d) {
                            d.estado = $('#filtro_estado').val();
                            d.orden = $('#filtro_orden').val();
                            d.importante = $('#filtro_importante').val();
                            d.search.value = $('#filtro_busqueda').val(); // 🔍 filtro personalizado
                        },
                        dataSrc: function(json) {
                            //if (json.totalPendientesPostulaciones !== undefined && json.totalPostulaciones !== undefined) {
                            //    $('#PostulacionesPorResponder').text(json.totalPendientesPostulaciones + ' de ' + json.totalPostulaciones);
                            //}
                            return json.data;
                        }
                    },
                    columns: [{
                            data: 'importante',
                            orderable: true,
                            searchable: false,
                            className: 'text-center',
                            render: function(data, type, row) {
                                const icon = data == 1 ? 'bi-star-fill text-warning' : 'bi-star text-muted';
                                return `<i class="bi ${icon} marcarImportante" data-id="${row.id}" data-valor="${data}" style="cursor:pointer;"></i>`;
                            }
                        },
                        {
                            data: "importante_texto",
                            visible: false,
                            render: function(data) {
                                return data;
                            }
                        },
                        {
                            data: 'id'
                        },
                        {
                            data: 'tipo_persona',
                        },
                        {
                            data: 'nombre_empresa',
                        },
                        {
                            render: function(data, type, row) {
                                return `${row.nombre_contacto} ${row.apellido_contacto}`;
                            },
                        },
                        {
                            data: 'email_contacto',
                        },
                        {
                            data: 'telefono_contacto',
                        },
                        {
                            data: 'tipo_activos',
                        },
                        {
                            data: 'detalle_activos',
                        },
                        {
                            data: 'estado',
                            className: 'text-center estado-toggle',
                            render: function(data, type, row) {
                                let clase = 'badge estado-click ';
                                switch (data.toLowerCase()) {
                                    case 'activo':
                                        clase += 'bg-success';
                                        break;
                                    case 'inactivo':
                                        clase += 'bg-warning text-dark';
                                        break;
                                    case 'eliminado':
                                        clase += 'bg-secondary';
                                        break;
                                }
                                return `<span class="${clase}" data-id="${row.id}" data-estado="${data}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                            }
                        },
                        {
                            data: 'fecha',
                            visible: false,
                            render: function(data) {
                                return data;
                            }
                        },
                        {
                            data: 'fecha_modificacion',
                            visible: false,
                            render: function(data) {
                                return data;
                            }
                        },
                        {
                            data: 'email_usuario_creador',
                            visible: false,
                            render: function(data) {
                                return data;
                            }
                        },
                        {
                            data: 'nombre_usuario_creador',
                            visible: false,
                            render: function(data) {
                                return data;
                            }
                        },
                        {
                            data: 'notas',
                            visible: false,
                            render: function(data) {
                                return data;
                            }
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: function(data, type, row) {
                                let botones = `
                                    <button class="btn btn-sm btn-primary me-1" title="Ver" onclick="verCliente(${row.id})">
                                        <i class="bi bi-eye"></i>
                                    </button>`;

                                const puedeModificar = PERMISOS['clientes']?.includes('modificar');
                                const puedeEliminar = PERMISOS['clientes']?.includes('eliminar');

                                if (puedeModificar && row.estado.toLowerCase() !== 'eliminado') {
                                    botones += `
                                    <button class="btn btn-sm btn-warning me-1" title="Editar" onclick="editarCliente(${row.id})">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>`;
                                }
                                if (row.estado.toLowerCase() === 'eliminado') {
                                    botones += `
                                        <button class="btn btn-sm btn-success" title="Recuperar" onclick="recuperarCliente(${row.id})">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                        </button>`;
                                } else if (puedeEliminar) {
                                    botones += `
                                        <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarCliente(${row.id})">
                                            <i class="bi bi-trash"></i>
                                        </button>`;
                                }

                                return botones;
                            }
                        }
                    ],
                    order: [
                        [0, 'desc'],
                        [3, 'desc'],
                        [4, 'asc'],
                        [5, 'asc']
                    ],
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                    },
                    dom: '<"d-flex justify-content-end mb-2"l>Bfrtip',
                    lengthMenu: [10, 30, 50, 100],
                    buttons: [{
                            extend: 'copy',
                            text: '<i class="bi bi-clipboard me-1"></i> Copiar',
                            className: 'btn btn-primary btn-sm me-2',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], // Seleccion de columnas a copiar
                                format: {
                                    body: function(data, row, column, node) {
                                        return typeof data === 'string' ? data.replace(/<.*?>/g, '') : data;
                                    }
                                }
                            }
                        },
                        {
                            extend: 'excel',
                            text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
                            className: 'btn btn-success btn-sm',
                            exportOptions: {
                                columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15], // Seleccion de columnas a copiar
                                format: {
                                    body: function(data, row, column, node) {
                                        return typeof data === 'string' ? data.replace(/<.*?>/g, '') : data;
                                    }
                                }
                            }
                        }
                    ],
                    initComplete: function() {
                        $('#loaderTabla').hide();
                        $('#tablaClientes thead').show();
                        $('#tablaClientes_wrapper').show();
                        tablaClientes.columns.adjust().draw();
                        tablaClientes.buttons().container().appendTo('#exportButtons');
                    },
                    drawCallback: function() {
                        registrarEventosEstadoCliente();
                    }
                });
            } else {
                tablaClientes.ajax.reload(null, false);
            }
        }

        $(document).ready(function() {
            if (typeof $.fn.DataTable === 'undefined') {
                //console.error("DataTables no está cargado aún.");
                return;
            }

            inicializarTablaClientes();
        });

        // Evento para búsqueda en tiempo real
        $(document).on('keyup', '#filtro_busqueda', function() {
            if (tablaClientes) tablaClientes.draw(); // Redibuja tabla con nuevo filtro
        });

        function filtrar() {
            inicializarTablaClientes();
        }

        function resetearFiltros() {
            $('#filtro_estado').val('');
            $('#filtro_orden').val('DESC');
            $('#filtro_importante').val('');
            $('#filtro_busqueda').val('');
            inicializarTablaClientes();
        }

        function eliminarCliente(id) {
            Swal.fire({
                title: '¿Eliminar cliente?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('clientesAjax.php', {
                        accion: 'eliminar',
                        id: id
                    }, function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cliente eliminado',
                                text: 'El cliente fue eliminado correctamente.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            tablaClientes.ajax.reload(null, false); // Recarga sin redirigir
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al eliminar',
                                text: 'Hubo un problema al intentar eliminar el cliente.',
                                confirmButtonText: 'Cerrar'
                            });
                        }
                    }, 'json').fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'No se pudo contactar con el servidor.',
                            confirmButtonText: 'Cerrar'
                        });
                    });
                }
            });
        }


        function verCliente(id) {
            $('#contenidoModalCliente').html('<p class="text-center text-muted">Cargando...</p>');
            $('#modalVerCliente').modal('show');

            $.get('clienteModal.php', {
                id,
                modo: 'ver'
            }, function(respuesta) {
                $('#contenidoModalCliente').html(respuesta);

                const botonHTML = $('#contenidoModalCliente').find('#botonImportanteHTML').html();
                $('#botonImportanteWrapper').html(botonHTML);

                // Re-ejecutar lógica para cargar detalle activo
                const tipoActivo = $('#tipo_activo').val();
                const detalleActualInput = $('#detalle_activos').data('valor') || '';

                const opciones = {
                    "Propiedad Residencial": ["Casa", "Departamento", "Parcela Agroresidencial", "Sitio Urbano", "Sitio Rural"],
                    "Inmueble Comercial": ["Oficina", "Local Comercial"],
                    "Activo Industrial": ["Bodega", "Industria", "Terreno Industrial"],
                    "Bien Especial": ["Hotel", "Clínica", "Colegio", "Otros inmuebles de uso específico"]
                };

                const detalleSelect = $('#detalle_activos');
                detalleSelect.empty();

                if (opciones[tipoActivo]) {
                    detalleSelect.append('<option value="" hidden selected>Seleccionar</option>');
                    opciones[tipoActivo].forEach(detalle => {
                        const selected = detalle === detalleActualInput ? 'selected' : '';
                        detalleSelect.append(`<option value="${detalle}" ${selected}>${detalle}</option>`);
                    });
                } else {
                    detalleSelect.append('<option value="">Selecciona un tipo válido</option>');
                }

            }).fail(function() {
                $('#contenidoModalCliente').html('<p class="text-danger">Error al cargar el cliente.</p>');
            });
        }

        function cargarEventosSelectActivo() {
            const tipoActivoSelect = $('#tipo_activo');
            const detalleSelect = $('#detalle_activos');

            const opciones = {
                "Propiedad Residencial": ["Casa", "Departamento", "Parcela Agroresidencial", "Sitio Urbano", "Sitio Rural"],
                "Inmueble Comercial": ["Oficina", "Local Comercial"],
                "Activo Industrial": ["Bodega", "Industria", "Terreno Industrial"],
                "Bien Especial": ["Hotel", "Clínica", "Colegio", "Otros inmuebles de uso específico"]
            };

            tipoActivoSelect.on('change', function() {
                const tipoActivo = $(this).val();
                detalleSelect.empty();
                detalleSelect.val('');
                detalleSelect.removeClass('is-valid is-invalid');

                if (opciones[tipoActivo]) {
                    detalleSelect.append('<option value="" hidden selected>Seleccionar</option>');
                    opciones[tipoActivo].forEach(function(opcion) {
                        detalleSelect.append(`<option value="${opcion}">${opcion}</option>`);
                    });
                } else {
                    detalleSelect.append('<option value="">Selecciona un tipo válido</option>');
                }
            });
            // También ejecuta esto al cargar por primera vez (por si ya hay un valor seleccionado)
            tipoActivoSelect.trigger('change');
        }

        function recuperarCliente(id) {
            Swal.fire({
                title: '¿Recuperar cliente?',
                text: 'El cliente volverá a estar disponible en el sistema.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754', // verde Bootstrap
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, recuperar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('clientesAjax.php', {
                        accion: 'recuperar',
                        id: id
                    }, function(response) {
                        if (response.success) {
                            tablaClientes.ajax.reload(null, false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Cliente recuperado',
                                text: 'El cliente fue recuperado con éxito.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error al recuperar',
                                text: 'No se pudo recuperar el cliente.',
                                confirmButtonText: 'Cerrar'
                            });
                        }
                    }, 'json').fail(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de conexión',
                            text: 'No se pudo contactar con el servidor.',
                            confirmButtonText: 'Cerrar'
                        });
                    });
                }
            });
        }

        function registrarEventosEstadoCliente() {
            $(document).off('click', '.estado-click');

            $(document).on('click', '.estado-click', function() {
                const $span = $(this);
                const id = $span.data('id');
                const estadoActual = $span.data('estado');

                if (typeof estadoActual !== 'string') return;

                const estadoLower = estadoActual.toLowerCase();
                let nuevoEstado = '';

                if (estadoLower === 'activo') {
                    nuevoEstado = 'inactivo';
                } else if (estadoLower === 'inactivo') {
                    nuevoEstado = 'activo';
                } else {
                    return;
                }

                // ✅ Confirmar antes de enviar la petición
                Swal.fire({
                    title: '¿Confirmar cambio de estado?',
                    text: `¿Deseas cambiar el estado del cliente a "${nuevoEstado}"?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, cambiar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('clientesAjax.php', {
                            accion: 'cambiar_estado',
                            id: id,
                            estado: nuevoEstado
                        }, function(response) {
                            if (response.success) {
                                const nuevaClase = nuevoEstado === 'activo' ? 'bg-success' : 'bg-warning text-dark';
                                const nuevoTexto = nuevoEstado.charAt(0).toUpperCase() + nuevoEstado.slice(1);

                                $span.fadeOut(200, function() {
                                    $span
                                        .removeClass('bg-success bg-warning text-dark')
                                        .addClass(nuevaClase)
                                        .text(nuevoTexto)
                                        .data('estado', nuevoEstado)
                                        .fadeIn(200);
                                });

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Estado actualizado',
                                    text: `El cliente fue marcado como ${nuevoTexto}.`,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error al cambiar estado',
                                    text: response.error || 'No se pudo cambiar el estado del cliente.',
                                });
                            }
                        }, 'json').fail(function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error de conexión',
                                text: 'No se pudo contactar con el servidor.',
                            });
                        });
                    }
                });
            });
        }

        $(document).on('click', '.marcarImportante', function() {
            const icono = $(this);
            const id = icono.attr('data-id');
            const valorActual = parseInt(icono.attr('data-valor')); // ✅ leerlo como número directamente del atributo

            const nuevoValor = valorActual === 1 ? 0 : 1;

            $.post('clientesAjax.php', {
                accion: 'importante',
                cliente_id: id,
                importante: nuevoValor
            }, function(response) {
                if (response.success) {
                    icono
                        .removeClass('bi-star bi-star-fill text-warning text-muted')
                        .addClass(nuevoValor === 1 ? 'bi-star-fill text-warning' : 'bi-star text-muted')
                        .attr('data-valor', nuevoValor) // ✅ actualizar atributo real
                        .addClass('balanceando');

                    setTimeout(() => {
                        icono.removeClass('balanceando');
                    }, 500);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al actualizar',
                        text: 'No se pudo actualizar el estado de importancia.',
                        confirmButtonText: 'Cerrar'
                    });
                }
            }, 'json');
        });
    </script>
    <!-- Footer -->
    <?php
    require_once __DIR__ . '/includes/footerAdmin.php';
    ?>
</body>

</html>