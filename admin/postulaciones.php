<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/Controller/postulacionesController.php';
require_once __DIR__ . '/../includes/auth.php';
$total_postulaciones = obtener_total_postulaciones();
$pendientes_postulaciones = obtener_postulaciones_pendientes();
requiereRol([1, 2,5]);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Postulaciones</title>
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

        a.marcarImportantePostulacion {
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
        if (typeof BASE_PATH === 'undefined') {
            var BASE_PATH = "/vfs/admin/";
        }
    </script>
</head>

<body class="bg-light">
    <div class="container-fluid px-3 py-2">
        <div id="contenido-dinamico" class="mx-auto w-100" style="max-width: 100%;">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h2 class="mb-0">Postulaciones</h2>
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
                                    <option value="pendiente">Pendiente</option>
                                    <option value="leido">Leído</option>
                                    <option value="eliminado">Eliminado</option>
                                </select>
                                <label for="filtro_estado">Estado</label>
                            </div>
                            <!--<div class="form-floating hidden">
                                <select class="form-select form-select-sm" id="filtro_orden" aria-label="Orden">
                                    <option value="" hidden selected>Seleccionar</option>
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
                        <!-- Grupo 3: Postulaciones pendientes -->
                        <div class="form-floating align-items-center">
                            <label class="form-label d-block invisible">.</label>
                            <button type="button" class="btn btn-warning btn-sm w-100" onclick="$('#filtro_estado').val('pendiente'); $('#filtro_orden').val('DESC');$('#filtro_importante').val('');filtrar();">
                                <h6 class="mb-1 fw-semibold">
                                    <span id="PostulacionesPorResponder"><?= $pendientes_postulaciones ?> de <?= $total_postulaciones ?></span>
                                </h6>
                                <p class="text-muted mb-0 small">Postulaciones pendientes</p>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Tabla postulaciones -->
            <div class="card-body">
                <div class="row gy-2 gx-3 align-items-end justify-content-between flex-wrap">
                    <section id="tabla-postulaciones">
                        <div class="card">
                            <div class="card-body">
                                <div id="loaderTabla" class="text-center my-3">
                                    <div class="spinner-border text-primary" role="status"><span class="visually-hidden"></span></div>
                                    <p class="mt-2 mb-0 text-muted">Cargando tabla...</p>
                                </div>
                                <div class="table-responsive" style="overflow-x: auto;">
                                    <table id="tablaPostulaciones" class="table table-striped table-hover align-middle nowrap w-100" style="min-width: 1200px;">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center">★</th>
                                                <th class="d-none">Importante</th> <!-- columna oculta -->
                                                <th>ID</th>
                                                <th>Rut</th>
                                                <th>Nombre</th>
                                                <th>Apellido</th>
                                                <th class="d-none">Fecha Nacimiento</th> <!-- columna oculta -->
                                                <th>Estudio</th>
                                                <th class="d-none">Institucion Educacional</th> <!-- columna oculta -->
                                                <th class="d-none">Año Titulación</th> <!-- columna oculta -->
                                                <th>Formación<br>Tasación</th>
                                                <th class="d-none">Formación Tasación</th> <!-- columna oculta -->
                                                <th class="d-none">Descripción Formación</th> <!-- columna oculta -->
                                                <th>Años<br>Experiencia</th>
                                                <th class="d-none">Empresa Tasacion</th> <!-- columna oculta -->
                                                <th>Disponibilidad<br>Comuna</th>
                                                <th class="d-none">Disponibilidad Comuna</th> <!-- columna oculta -->
                                                <th>Disponibilidad<br>Region</th>
                                                <th class="d-none">Disponibilidad Region</th> <!-- columna oculta -->
                                                <th>Movilización<br>Propia</th>   
                                                <th class="d-none">Movilizacion Propia</th> <!-- columna oculta -->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      
                                                <th class="d-none">Email</th> <!-- columna oculta -->
                                                <th class="d-none">Telefono</th> <!-- columna oculta -->
                                                <th class="d-none">Dirección</th> <!-- columna oculta -->
                                                <th class="d-none">Comuna</th> <!-- columna oculta -->
                                                <th class="d-none">Region</th> <!-- columna oculta -->
                                                <th>Estado</th>
                                                <th>Fecha</th>
                                                <th>Archivo</th>
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
    <!-- Modal para Ver Postulacion -->
    <div class="modal fade" id="modalVerPostulacion" tabindex="-1" aria-labelledby="modalVerPostulacionLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <h5 class="modal-title mb-0" id="modalVerPostulacionLabel">Detalle de la Postulación</h5>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div id="botonImportanteWrapper" style="min-width: 200px;"></div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                </div>
                <!-- Modal Body -->
                <div class="modal-body" id="contenidoModalPostulacion">
                    <p class="text-center text-muted">Cargando...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para ver PDF con descarga -->
    <div class="modal fade" id="modalPDF" tabindex="-1" aria-labelledby="modalPDFLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between align-items-center">
                    <h5 class="modal-title mb-0" id="modalPDFLabel">Vista previa del archivo</h5>
                    <div class="d-flex gap-2">
                        <a id="btnDescargarPDF" href="#" download class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-download me-1"></i> Descargar
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                </div>
                <div class="modal-body" style="height: 80vh;">
                    <iframe id="iframePDF" src="" width="100%" height="100%" style="border: none;"></iframe>
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

    <!-- Footer -->
    <?php
    require_once __DIR__ . '/includes/footerAdmin.php';
    ?>
</body>

</html>