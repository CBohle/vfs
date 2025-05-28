<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/mensajesController.php';

$total_mensajes = obtener_total_mensajes();
$pendientes_mensajes = obtener_mensajes_pendientes();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mensajes de Contacto</title>
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
</head>

<body class="bg-light">
    <div class="container-fluid px-3 py-2">
        <div id="contenido-dinamico" class="mx-auto w-100" style="max-width: 100%;">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h2 class="mb-0">Mensajes de Contacto</h2>
                <!-- Botones de exportación -->
                <div id="exportButtons" class="d-flex gap-1"></div>
            </div>

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
                                    <option value="pendiente">Pendiente</option>
                                    <option value="leido">Leído</option>
                                    <option value="respondido">Respondido</option>
                                    <option value="eliminado">Eliminado</option>
                                </select>
                                <label for="filtro_estado">Estado</label>
                            </div>
                            <div class="form-floating">
                                <select class="form-select form-select-sm" id="filtro_servicio" aria-label="Servicio">
                                    <option value="" hidden selected>Seleccione</option>
                                    <option value="Tasacion de bienes raices">Tasación de bienes raíces</option>
                                    <option value="Ambos">Ambos servicios</option>
                                    <option value="Otros">Otros servicios</option>
                                </select>
                                <label for="filtro_servicio">Servicio</label>
                            </div>
                            <div class="form-floating">
                                <select class="form-select form-select-sm" id="filtro_orden" aria-label="Orden">
                                    <option value="DESC" hidden selected>Seleccionar</option>
                                    <option value="DESC">Más Reciente</option>
                                    <option value="ASC">Más Antiguo</option>
                                </select>
                                <label for="filtro_orden">Orden</label>
                            </div>
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
                                <label class="d-none">Acciones</label> <!-- etiqueta invisible para mantener estructura -->
                            </div>
                        </div>
                        <!-- Grupo 2: Buscar -->
                        <div class="form-floating">
                            <input type="text" class="form-floating form-control form-control-sm" id="filtro_busqueda" placeholder="Buscar palabra clave">
                            <label for="filtro_busqueda">Buscar palabra clave</label>
                        </div>

                        <!-- Grupo 3: Mensajes pendientes -->
                        <div class="form-floating align-items-center">
                            <label class="form-label d-block invisible">.</label>
                            <button type="button" class="btn btn-warning btn-sm w-100" onclick="$('#filtro_estado').val('pendiente');$('#filtro_servicio').val('');$('#filtro_orden').val('DESC');$('#filtro_importante').val(''); filtrar();">
                                <h6 class="mb-1 fw-semibold">
                                    <span id="mensajesPorResponder"><?= $pendientes_mensajes ?> de <?= $total_mensajes ?></span>
                                </h6>
                                <p class="text-muted mb-0 small">Mensajes pendientes</p>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <div class="row gy-2 gx-3 align-items-end justify-content-between flex-wrap">
                    <section id="tabla-mensajes">
                        <div class="card">
                            <div class="card-body">
                                <div id="loaderTabla" class="text-center my-3">
                                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div>
                                    <p class="mt-2 mb-0 text-muted">Cargando tabla...</p>
                                </div>
                                <div class="table-responsive" style="overflow-x: auto;">
                                    <table id="tablaMensajes" class="table table-striped table-hover align-middle nowrap w-100" style="min-width: 1200px;">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center">★</th>
                                                <th>ID</th>
                                                <th>Servicio</th>
                                                <th>Nombre</th>
                                                <th>Email</th>
                                                <th>Mensaje</th>
                                                <th>Estado</th>
                                                <th>Fecha</th>
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
    <!-- Modal para Ver Mensaje -->
    <div class="modal fade" id="modalVerMensaje" tabindex="-1" aria-labelledby="modalVerMensajeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h5 class="modal-title mb-0" id="modalVerMensajeLabel">Detalle de la Postulación</h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div id="botonImportanteWrapper" style="min-width: 200px;"></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                </div>
                <div class="modal-body" id="contenidoModalMensaje">
                    <!-- Aquí se carga el contenido dinámico con AJAX -->
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

    <script>
        window.tabla = window.tabla || null;

        function inicializarTablaMensajes() {
            if ($.fn.DataTable.isDataTable('#tablaMensajes')) {
                tabla.clear().destroy();
            }

            tabla = $('#tablaMensajes').DataTable({
                responsive: false,
                scrollX: true,
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: 'mensajesAjax.php',
                    type: 'POST',
                    data: function(d) {
                        d.estado = $('#filtro_estado').val();
                        d.servicio = $('#filtro_servicio').val();
                        d.orden = $('#filtro_orden').val();
                        d.importante = $('#filtro_importante').val();
                        d.search.value = $('#filtro_busqueda').val(); // 🔍 filtro personalizado
                    },
                    dataSrc: function(json) {
                        if (json.totalPendientes !== undefined && json.totalMensajes !== undefined) {
                            $('#mensajesPorResponder').text(json.totalPendientes + ' de ' + json.totalMensajes);
                        }
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
                        data: 'id'
                    },
                    {
                        data: 'servicio'
                    },
                    {
                        data: 'nombre'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'mensaje',
                        className: 'col-mensaje',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<div class="truncado-3-lineas">${data}</div>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'estado',
                        render: function(data) {
                            let clase = 'badge ';
                            switch (data.toLowerCase()) {
                                case 'respondido':
                                    clase += 'bg-success';
                                    break;
                                case 'leido':
                                    clase += 'bg-primary';
                                    break;
                                case 'pendiente':
                                    clase += 'bg-warning text-dark';
                                    break;
                                case 'eliminado':
                                    clase += 'bg-secondary';
                                    break;
                                default:
                                    clase += 'bg-light text-dark';
                            }
                            return `<span class="${clase}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                        }
                    },
                    {
                        data: 'fecha'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            if (row.estado.toLowerCase() === 'eliminado') {
                                return `
                    <button class="btn btn-sm btn-primary me-1" title="Ver" onclick="verMensaje(${row.id})">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-success" title="Recuperar" onclick="recuperarMensaje(${row.id})">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </button>
                    `;
                            } else {
                                return `
                    <button class="btn btn-sm btn-primary me-1" title="Ver" onclick="verMensaje(${row.id})">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarMensaje(${row.id})">
                        <i class="bi bi-trash"></i>
                    </button>
                    `;
                            }
                        },
                    }
                ],
                order: [
                    [0, 'desc'],
                    [7, 'desc']
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                dom: '<"d-flex justify-content-end mb-2"l>Bfrtip',
                lengthMenu: [10, 30, 50, 100],
                buttons: [{
                        extend: 'copy',
                        text: '<i class="bi bi-clipboard me-1"></i> Copiar',
                        className: 'btn btn-primary btn-sm me-2'
                    },
                    {
                        extend: 'excel',
                        text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
                        className: 'btn btn-success btn-sm'
                    }
                ],
                initComplete: function() {
                    $('#loaderTabla').hide();
                    $('#tablaMensajes').css('visibility', 'visible');
                    tabla.columns.adjust().draw();
                    tabla.buttons().container().appendTo('#exportButtons');
                }
            });
        }

        // Evento para búsqueda en tiempo real
        $(document).on('keyup', '#filtro_busqueda', function() {
            if (tabla) tabla.draw(); // Redibuja tabla con nuevo filtro
        });

        function filtrar() {
            inicializarTablaMensajes();
        }

        function resetearFiltros() {
            $('#filtro_estado').val('');
            $('#filtro_servicio').val('');
            $('#filtro_orden').val('DESC');
            $('#filtro_busqueda').val('');
            inicializarTablaMensajes();
        }

        function verMensaje(id) {
            alert(`Abrir modal de mensaje con ID ${id}`);
            // Aquí puedes abrir un modal o redirigir
            $('#contenidoModalMensaje').html('<p class="text-center text-muted">Cargando...</p>');
            $('#modalVerMensaje').modal('show');

            $.get('mensajeModal.php', {
                id
            }, function(respuesta) {
                $('#contenidoModalMensaje').html(respuesta);

                const botonHTML = $('#contenidoModalMensaje').find('#botonImportanteHTML').html();
                $('#botonImportanteWrapper').html(botonHTML);
            }).fail(function() {
                $('#contenidoModalMensaje').html('<p class="text-danger">Error al cargar el mensaje.</p>');
            });
        }

        function eliminarMensaje(id) {
            if (confirm("¿Estás seguro de que deseas eliminar este mensaje?")) {
                $.post('mensajesAjax.php', {
                    accion: 'eliminar',
                    id: id
                }, function(response) {
                    if (response.success) {
                        alert("Mensaje eliminado correctamente.");
                        tabla.ajax.reload(null, false); // solo recarga datos sin redireccionar
                    } else {
                        alert("Hubo un error al intentar eliminar el mensaje.");
                    }
                }, 'json');
            }
        }

        function verMensaje(id) {
            $('#contenidoModalMensaje').html('<p class="text-center text-muted">Cargando...</p>');
            $('#modalVerMensaje').modal('show');

            $.get('mensajeModal.php', {
                id: id
            }, function(respuesta) {
                $('#contenidoModalMensaje').html(respuesta);
            }).fail(function() {
                $('#contenidoModalMensaje').html('<p class="text-danger">Error al cargar el mensaje.</p>');
            });
        }

        function recuperarMensaje(id) {
            if (confirm("¿Deseas recuperar este mensaje?")) {
                $.post('mensajesAjax.php', {
                    accion: 'recuperar',
                    id: id
                }, function(response) {
                    if (response.success) {
                        tabla.ajax.reload(null, false);
                        alert('Mensaje recuperado con éxito.');
                    } else {
                        alert('No se pudo recuperar el mensaje.');
                    }
                }, 'json');
            }
        }
        $(document).ready(function() {
            inicializarTablaMensajes();
        });
        $(document).on('click', '.marcarImportante', function() {
            const id = $(this).data('id');
            const valorActual = $(this).data('valor');
            const nuevoValor = valorActual == 1 ? 0 : 1;

            $.post('mensajesAjax.php', {
                accion: 'importante',
                mensaje_id: id,
                importante: nuevoValor
            }, function(response) {
                if (response.success) {
                    tabla.ajax.reload(null, false); // recarga sin perder la página actual
                } else {
                    alert('No se pudo actualizar el estado de importancia.');
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