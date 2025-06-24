<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/Controller/usuariosController.php';
require_once __DIR__ . '/../includes/auth.php';
if (!tienePermiso('usuarios', 'ver') && !tienePermiso('roles', 'ver')) {
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
// Cargar roles activos para el formulario
$roles = [];
$stmt = $conexion->prepare("SELECT id, nombre FROM roles WHERE activo = 1 ORDER BY nombre");
$stmt->execute();
$resultado = $stmt->get_result();

while ($fila = $resultado->fetch_assoc()) {
    $roles[] = $fila;
}
// Permisos adicionales para crear, modificar y eliminar
$puedeCrearUsuarios = tienePermiso('usuarios', 'crear');
$puedeModificarUsuarios = tienePermiso('usuarios', 'modificar');
$puedeEliminarUsuarios = tienePermiso('usuarios', 'eliminar');

$puedeCrearRoles = tienePermiso('roles', 'crear');
$puedeModificarRoles = tienePermiso('roles', 'modificar');
$puedeEliminarRoles = tienePermiso('roles', 'eliminar');
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Gestión de Usuarios y Roles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        body {
            font-size: 1rem;
        }

        .card {
            border-radius: 0.5rem;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .table td.descripcion {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            line-clamp: 3;
            -webkit-line-clamp: 3;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: normal !important;
            line-height: 1.3rem;
            max-height: calc(1.3rem * 3);
        }

        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            justify-content: end;
            margin-top: 0.5rem;
        }

        table.dataTable {
            border-collapse: separate !important;
            border-spacing: 0 0.5rem !important;
            background-color: transparent;
        }

        table.dataTable tbody tr {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        table.dataTable tbody tr td {
            border: none !important;
            padding: 0.25rem 0.5rem !important;
        }

        table.dataTable thead th {
            background-color: #f0f2f5;
            border-bottom: none;
            font-weight: 600;
            color: #495057;
            padding: 0.75rem;
        }

        /* Estilo base del paginador */
        .dataTables_paginate .paginate_button {
            padding: 0.4rem 0.75rem;
            margin: 0 2px;
            border-radius: 0.375rem;
            border: 1px solid transparent;
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 0.875rem;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .dataTables_paginate .paginate_button:not(.current):not(.disabled):hover {
            background-color: #e2e6ea;
            color: #495057;
        }

        .dataTables_paginate .paginate_button.current {
            background-color: #0d6efd;
            color: #fff !important;
            border-color: #0d6efd;
            font-weight: bold;
        }

        .dataTables_paginate .paginate_button.previous:not(.disabled),
        .dataTables_paginate .paginate_button.next:not(.disabled) {
            background-color: #0d6efd;
            color: #fff !important;
            border-color: #0d6efd;
        }

        .dataTables_paginate .paginate_button.previous:not(.disabled):hover,
        .dataTables_paginate .paginate_button.next:not(.disabled):hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            color: #fff !important;
        }

        .dataTables_paginate .paginate_button.disabled {
            background-color: #f1f3f5;
            color: #adb5bd !important;
            border-color: #dee2e6;
            cursor: not-allowed;
        }

        .disabled-cell {
            opacity: 0.5;
            pointer-events: none;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4">Administración de Usuarios y Roles</h2>

        <?php
        $puedeVerUsuarios = tienePermiso('usuarios', 'ver');
        $puedeVerRoles = tienePermiso('roles', 'ver');
        $primeraPestana = $puedeVerUsuarios ? 'usuarios' : 'roles';
        ?>
        <ul class="nav nav-tabs" id="tabs" role="tablist">
            <?php if ($puedeVerUsuarios): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $primeraPestana === 'usuarios' ? 'active' : '' ?>" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab">Usuarios</button>
                </li>
            <?php endif; ?>
            <?php if ($puedeVerRoles): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $primeraPestana === 'roles' ? 'active' : '' ?>" id="roles-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">Roles</button>
                </li>
            <?php endif; ?>
        </ul>


        <div class="tab-content pt-3">
            <div class="tab-pane fade <?= $primeraPestana === 'roles' ? 'show active' : '' ?>" id="roles" role="tabpanel">
                <div class="row">
                    <div class="col-lg-7 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Roles Registrados</span>
                                <button class="btn btn-sm btn-primary" onclick="mostrarFormularioRol('crear')">Nuevo Rol</button>
                            </div>
                            <div class="table-responsive">
                                <table id="tablaRoles" class="table table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th>Rol</th>
                                            <th>Descripción</th>
                                            <th>Estatus</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div id="contenedorPaginacionRoles" class="pt-2 mt-3 d-flex justify-content-end"></div>
                    </div>
                    <div class="col-lg-5 mb-3" id="formularioRolContainer" style="display: none;">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0" id="formRolTitulo">Nuevo Rol</h5>
                                <button class="btn-close" onclick="ocultarFormularioRol()"></button>
                            </div>
                            <form id="formRol" class="card-body">
                                <div class="mb-3">
                                    <label for="nombreRol" class="form-label">Nombre del Rol</label>
                                    <input type="text" class="form-control" id="nombreRol" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="descripcionRol" class="form-label">Descripción</label>
                                    <input type="text" class="form-control" id="descripcionRol" name="descripcion">
                                </div>
                                <h6>Permisos por módulo:</h6>
                                <div class="table-responsive mb-3">
                                    <table class="table table-bordered text-center align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Módulo</th>
                                                <th>Ver</th>
                                                <th>Modificar</th>
                                                <th>Crear</th>
                                                <th>Eliminar</th>
                                                <th>Aviso</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (['mensajes', 'postulaciones', 'clientes', 'usuarios', 'roles'] as $modulo): ?>
                                                <tr>
                                                    <td class="text-start"><?= ucfirst($modulo) ?></td>
                                                    <?php foreach (['ver', 'modificar', 'crear', 'eliminar','aviso'] as $accion): ?>
                                                        <?php
                                                        $bloqueado = false;
                                                        if (
                                                            ($accion === 'crear' && in_array($modulo, ['mensajes', 'postulaciones'])) ||
                                                            ($accion === 'modificar' && $modulo === 'postulaciones') || 
                                                            ($accion === 'aviso' && in_array($modulo, ['clientes', 'usuarios', 'roles']))
                                                        ) {
                                                            $bloqueado = true;
                                                        }
                                                        ?>
                                                        <td class="text-center align-middle">
                                                            <?php if ($bloqueado): ?>
                                                                <span class="bloqueado-permiso" title="Este permiso no se puede asignar">✖</span>
                                                            <?php else: ?>
                                                                <input type="checkbox"
                                                                    class="form-check-input permiso-checkbox"
                                                                    data-modulo="<?= $modulo ?>"
                                                                    data-accion="<?= $accion ?>"
                                                                    name="permisos[<?= $modulo ?>][<?= $accion ?>]"
                                                                    <?= (!empty($rolPermisos[$modulo][$accion]) ? 'checked' : '') ?>>
                                                            <?php endif; ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <input type="hidden" name="rol_id" id="rol_id">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" onclick="ocultarFormularioRol()">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Rol</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade <?= $primeraPestana === 'usuarios' ? 'show active' : '' ?>" id="usuarios" role="tabpanel">
                <div class="row">
                    <div class="col-lg-7 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Usuarios Registrados</span>
                                <button class="btn btn-sm btn-primary" onclick="mostrarFormularioUsuario('crear')">Nuevo Usuario</button>
                            </div>
                            <div class="table-responsive">
                                <table id="tablaUsuarios" class="table table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>Email</th>
                                            <th>Rol</th>
                                            <th>Activo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <div id="contenedorPaginacionUsuarios" class="pt-2 mt-3 d-flex justify-content-end"></div>
                    </div>
                    <!-- Creación de nuevo usuario -->
                    <div class="col-lg-5 mb-3" id="formularioUsuarioContainer" style="display: none;">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0" id="formUsuarioTitulo">Nuevo Usuario</h5>
                                <button class="btn-close" onclick="ocultarFormularioUsuario()"></button>
                            </div>
                            <form id="formUsuario" class="card-body">
                                <div class="mb-3">
                                    <label for="nombreUsuario" class="form-label">Email del Usuario</label>
                                    <input type="text" class="form-control" id="emailUsuario" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nombreUsuario" class="form-label">Nombre del Usuario</label>
                                    <input type="text" class="form-control" id="nombreUsuario" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nombreUsuario" class="form-label">Apellido del Usuario</label>
                                    <input type="text" class="form-control" id="apellidoUsuario" name="apellido" required>
                                </div>
                                <div class="mb-3">
                                    <label for="rolUsuario" class="form-label">Rol</label>
                                    <select class="form-select" id="rolUsuario" name="rol_id" required>
                                        <option value="" hidden>Seleccione un rol</option>
                                        <?php foreach ($roles as $rol): ?>
                                            <option value="<?= $rol['id'] ?>"><?= htmlspecialchars($rol['nombre']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="passwordUsuario" class="form-label">Contraseña</label>
                                    <input type="password" class="form-control" id="passwordUsuario" name="password" required>
                                    <small id="passwordUsuarioHelp" class="form-text text-warning"></small>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmarPasswordUsuario" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="confirmarPasswordUsuario" name="confirmar_password" required>
                                    <small id="confirmarHelp" class="form-text text-warning"></small>
                                </div>

                                <input type="hidden" name="usuario_id" id="usuario_id">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="button" class="btn btn-secondary" onclick="ocultarFormularioUsuario()">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                window.PERMISOS = <?= json_encode($_SESSION['permisos'] ?? []) ?>;
                //console.log("PERMISOS cargados desde dashboard:", window.PERMISOS);
            </script>
            <script>
                if (typeof tablaRoles === 'undefined') {
                    var tablaRoles;
                    var tablaUsuarios;
                }

                function inicializarTablaRoles() {
                    if ($.fn.DataTable.isDataTable('#tablaRoles')) tablaRoles.destroy();

                    tablaRoles = $('#tablaRoles').DataTable({
                        autoWidth: false,
                        serverSide: true,
                        ajax: {
                            url: 'usuariosAjax.php',
                            type: 'POST',
                            data: {
                                accion: 'listarRoles'
                            },
                            dataSrc: 'data'
                        },
                        columns: [{
                                data: 'nombre'
                            },
                            {
                                data: 'descripcion',
                                className: 'descripcion'
                            },
                            {
                                data: 'activo',
                                className: 'text-center estado-toggle',
                                render: function(data, type, row) {
                                    const estado = String(data).toLowerCase();
                                    let clase = 'badge estado-click ';
                                    switch (estado) {
                                        case 'activo':
                                        case '1':
                                        case 'true':
                                            clase += 'bg-success';
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="activo" data-tipo="rol">Activo</span>`;
                                        case 'inactivo':
                                        case '0':
                                        case 'false':
                                            clase += 'bg-warning text-dark';
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="inactivo" data-tipo="rol">Inactivo</span>`;
                                        default:
                                            clase += 'bg-secondary';
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="desconocido">Desconocido</span>`;
                                    }
                                    return `<span class="${clase}" data-id="${row.id}" data-activo="${estado}" data-tipo="${row.rol ? 'usuario' : 'rol'}">${estado.charAt(0).toUpperCase() + estado.slice(1)}</span>`;
                                }
                            },
                            {
                                data: null,
                                className: 'text-center',
                                render: function(data, type, row) {
                                    let botones = `<button class="btn btn-sm btn-primary me-1" onclick="verRol(${row.id})"><i class="bi bi-eye"></i></button>`;
                                    if (PERMISOS?.roles?.includes('eliminar')) {
                                        botones += `<button class="btn btn-sm btn-danger" onclick="eliminarRol(${row.id})"><i class="bi bi-trash"></i></button>`;
                                    }
                                    return botones;
                                }
                            }
                        ],
                        dom: '<"datatable-container"t><"datatable-footer d-flex justify-content-end mt-2"p>',
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                        },
                        drawCallback: function() {
                            const paginador = $('#tablaRoles_wrapper .dataTables_paginate');
                            if (paginador.length) {
                                $('#contenedorPaginacionRoles').html(paginador);
                            }
                        },
                        pagingType: 'simple_numbers',
                        pageLength: 10,
                        columnDefs: [{
                                targets: 2,
                                width: '100px'
                            }, // Estatus
                            {
                                targets: 3,
                                width: '110px'
                            } // Acciones
                        ],
                    });
                }
                window.mostrarFormularioRol = function(modo = 'crear', rolData = null) {
                    if (modo === 'crear' && !PERMISOS?.roles?.includes('crear')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Acceso restringido',
                            text: 'No tienes permisos para crear nuevos roles.',
                            confirmButtonText: 'Cerrar'
                        });
                        return;
                    }

                    if (modo === 'modificar' && !PERMISOS?.roles?.includes('modificar')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Acceso restringido',
                            text: 'No tienes permisos para editar roles.',
                            confirmButtonText: 'Cerrar'
                        });
                        return;
                    }

                    $('#formRol')[0].reset();
                    $('#formRolTitulo').text(modo === 'crear' ? 'Nuevo Rol' : 'Editar Rol');
                    $('#rol_id').val(rolData?.id || '');
                    $('#nombreRol').val(rolData?.nombre || '').prop('disabled', modo === 'modificar');
                    $('#descripcionRol').val(rolData?.descripcion || '').prop('disabled', false);

                    $('input[name^="permisos"]').prop('checked', false).prop('disabled', false);

                    if (rolData?.permisos) {
                        for (let modulo in rolData.permisos) {
                            for (let accion in rolData.permisos[modulo]) {
                                $(`input[name="permisos[${modulo}][${accion}]"]`).prop('checked', true);
                            }
                        }
                    }

                    $('#formRol .btn[type="submit"]').show();
                    $('#formularioRolContainer').show();
                }

                function inicializarTablaUsuarios() {
                    if ($.fn.DataTable.isDataTable('#tablaUsuarios')) tablaUsuarios.destroy();

                    tablaUsuarios = $('#tablaUsuarios').DataTable({
                        serverSide: true,
                        ajax: {
                            url: 'usuariosAjax.php',
                            type: 'POST',
                            data: {
                                accion: 'listarUsuarios'
                            },
                            dataSrc: 'data'
                        },
                        columns: [{
                                data: 'nombre'
                            },
                            {
                                data: 'apellido'
                            },
                            {
                                data: 'email'
                            },
                            {
                                data: 'rol'
                            },
                            {
                                data: 'activo',
                                className: 'text-center estado-toggle',
                                render: function(data, type, row) {
                                    const estado = String(data).toLowerCase();
                                    let clase = 'badge estado-click ';
                                    let tipo = 'usuario';
                                    switch (estado) {
                                        case 'activo':
                                        case '1':
                                        case 'true':
                                            clase += 'bg-success';
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="activo" data-tipo="${tipo}">Activo</span>`;
                                        case 'inactivo':
                                        case '0':
                                        case 'false':
                                            clase += 'bg-warning text-dark';
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="inactivo" data-tipo="${tipo}">Inactivo</span>`;
                                        default:
                                            clase += 'bg-secondary';
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="desconocido" data-tipo="${tipo}">Desconocido</span>`;
                                    }
                                }
                            },
                            {
                                data: null,
                                className: 'text-center',
                                render: function(data, type, row) {
                                    let botones = `<button class="btn btn-sm btn-primary me-1" onclick="verUsuario(${row.id})"><i class="bi bi-eye"></i></button>`;
                                    if (PERMISOS?.usuarios?.includes('eliminar')) {
                                        botones += `<button class="btn btn-sm btn-danger" onclick="eliminarUsuario(${row.id})"><i class="bi bi-trash"></i></button>`;
                                    }
                                    return botones;
                                }
                            }
                        ],
                        dom: '<"datatable-container"t><"datatable-footer d-flex justify-content-end mt-2"p>',
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                        },
                        drawCallback: function() {
                            const paginador = $('#tablaUsuarios_wrapper .dataTables_paginate');
                            if (paginador.length) {
                                $('#contenedorPaginacionUsuarios').html(paginador);
                            }
                        },
                        pagingType: 'simple_numbers',
                        pageLength: 10
                    });
                }

                function ocultarFormularioRol() {
                    $('#formularioRolContainer').hide();
                }

                $(document).ready(function() {
                    function actualizarDependencia(modulo) {
                        const ver = $(`input[data-modulo="${modulo}"][data-accion="ver"]`);
                        const modificar = $(`input[data-modulo="${modulo}"][data-accion="modificar"]`);
                        const crear = $(`input[data-modulo="${modulo}"][data-accion="crear"]`);
                        const eliminar = $(`input[data-modulo="${modulo}"][data-accion="eliminar"]`);

                        // Si modificar/crear/eliminar está marcado → marcar/ver "ver"
                        if (modificar.prop('checked') || crear.prop('checked') || eliminar.prop('checked')) {
                            ver.prop('checked', true).prop('disabled', true);
                        } else {
                            // Si no hay ninguno activo, permitir desmarcar "ver"
                            ver.prop('disabled', false);
                        }
                    }

                    // Detecta cualquier cambio en checkboxes
                    $('.permiso-checkbox').on('change', function() {
                        const modulo = $(this).data('modulo');
                        actualizarDependencia(modulo);
                    });

                    // Inicializa lógica para todos los módulos al cargar
                    const modulos = [...new Set($('.permiso-checkbox').map(function() {
                        return $(this).data('modulo');
                    }).get())];

                    modulos.forEach(actualizarDependencia);
                });

                function verRol(id) {
                    $.post('usuariosAjax.php', {
                        accion: 'obtenerRolPorId',
                        id
                    }, function(data) {
                        mostrarFormularioRol('modificar', data);
                    }, 'json');
                }

                $('#formRol').on('submit', function(e) {
                    e.preventDefault();

                    const esNuevo = $('#rol_id').val() === '';
                    if (esNuevo && !PERMISOS?.roles?.includes('crear')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sin permiso',
                            text: 'No tienes permiso para crear roles.'
                        });
                        return;
                    } else if (!esNuevo && !PERMISOS?.roles?.includes('modificar')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sin permiso',
                            text: 'No tienes permiso para modificar roles.'
                        });
                        return;
                    }
                    const permisos = {};
                    $('input[name^="permisos"]').each(function() {
                        if (this.checked) {
                            const match = this.name.match(/permisos\[(\w+)\]\[(\w+)\]/);
                            if (match) {
                                const [_, modulo, accion] = match;
                                if (!permisos[modulo]) permisos[modulo] = {};
                                permisos[modulo][accion] = true;
                            }
                        }
                    });

                    const datosRol = {
                        id: $('#rol_id').val(),
                        nombre: $('#nombreRol').val().trim(),
                        descripcion: $('#descripcionRol').val().trim(),
                        permisos,
                    };

                    guardarRolYActualizarPermisos(datosRol);
                });
                window.mostrarFormularioUsuario = function(modo = 'crear', usuarioData = null) {
                    if (modo === 'crear' && !PERMISOS?.usuarios?.includes('crear')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Acceso restringido',
                            text: 'No tienes permisos para crear nuevos usuarios.',
                            confirmButtonText: 'Cerrar'
                        });
                        return;
                    }

                    if (modo === 'modificar' && !PERMISOS?.usuarios?.includes('modificar')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Acceso restringido',
                            text: 'No tienes permisos para editar usuarios.',
                            confirmButtonText: 'Cerrar'
                        });
                        return;
                    }

                    $('#formUsuario')[0].reset();
                    $('#formUsuarioTitulo').text(modo === 'crear' ? 'Nuevo Usuario' : 'Editar Usuario');
                    $('#usuario_id').val(usuarioData?.id || '');
                    $('#nombreUsuario').val(usuarioData?.nombre || '');
                    $('#apellidoUsuario').val(usuarioData?.apellido || '');
                    $('#emailUsuario').val(usuarioData?.email || '').prop('disabled', modo === 'modificar');
                    $('#estadoUsuario').val(usuarioData?.estado || 'activo');
                    $('#rolUsuario').val(usuarioData?.rol_id || '');
                    $('#formUsuario .btn[type="submit"]').show();
                    $('#formularioUsuarioContainer').show();
                }
                $(document).ready(function() {
                    inicializarTablaRoles();
                    inicializarTablaUsuarios();
                });

                function ocultarFormularioUsuario() {
                    $('#formularioUsuarioContainer').hide();
                }

                function verUsuario(id) {
                    $.post('usuariosAjax.php', {
                        accion: 'obtenerUsuarioPorId',
                        id
                    }, function(data) {
                        mostrarFormularioUsuario('modificar', data);
                    }, 'json');
                }
                $('#formUsuario').on('submit', function(e) {
                    e.preventDefault();

                    const esNuevo = $('#usuario_id').val() === '';
                    if (esNuevo && !PERMISOS?.usuarios?.includes('crear')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sin permiso',
                            text: 'No tienes permiso para crear usuarios.'
                        });
                        return;
                    } else if (!esNuevo && !PERMISOS?.usuarios?.includes('modificar')) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sin permiso',
                            text: 'No tienes permiso para modificar usuarios.'
                        });
                        return;
                    }

                    const password = $('#passwordUsuario').val().trim();
                    const confirmarPassword = $('#confirmarPasswordUsuario').val().trim();

                    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;

                    if (!regex.test(password)) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Contraseña insegura',
                            html: 'La contraseña debe tener al menos:<br>- 8 caracteres<br>- Una mayúscula<br>- Una minúscula<br>- Un número<br>- Un carácter especial'
                        });
                        return;
                    }

                    if (password !== confirmarPassword) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Contraseñas no coinciden',
                            text: 'Por favor, verifica ambos campos.'
                        });
                        return;
                    }


                    const datosUsuario = {
                        id: $('#usuario_id').val(),
                        email: $('#emailUsuario').val().trim(),
                        nombre: $('#nombreUsuario').val().trim(),
                        apellido: $('#apellidoUsuario').val().trim(),
                        password: password,
                        rol_id: $('#rolUsuario').val()
                    };

                    guardarUsuarioYActualizar(datosUsuario);
                });
                $(document).on('click', '.estado-click', function() {
                    const badge = $(this);
                    const id = badge.data("id");
                    const tipo = badge.data("tipo"); // 'usuario' o 'rol'
                    const estadoActual = badge.data("activo");
                    const nuevoEstado = estadoActual === "activo" ? "inactivo" : "activo";
                    const estado = $(this).text().trim().toLowerCase() === "activo" ? "inactivo" : "activo";

                    // ✅ Validación de permiso antes de continuar
                    if (
                        (tipo === 'usuario' && !PERMISOS?.usuarios?.includes('modificar')) ||
                        (tipo === 'rol' && !PERMISOS?.roles?.includes('modificar'))
                    ) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sin permiso',
                            text: `No tienes permiso para modificar ${tipo === 'usuario' ? 'usuarios' : 'roles'}.`
                        });
                        return;
                    }
                    const nombre = tipo === 'usuario' ? 'usuario' : 'rol';

                    Swal.fire({
                        title: `¿Confirmar cambio de estado?`,
                        text: `¿Deseas marcar este ${nombre} como "${nuevoEstado}"?`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, cambiar",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post("usuariosAjax.php", {
                                accion: "cambiar_estado",
                                id,
                                estado,
                                tipo
                            }, function(respuesta) {
                                if (respuesta.success) {
                                    Swal.fire("Éxito", "Estado actualizado correctamente.", "success");
                                    if (tipo === "usuario") {
                                        tablaUsuarios.ajax.reload();
                                    } else if (tipo === "rol") {
                                        tablaRoles.ajax.reload();
                                    }
                                } else {
                                    Swal.fire("Error", respuesta.error || "No se pudo cambiar el estado", "error");
                                }
                            }, "json").fail(function() {
                                Swal.fire("Error", "No se pudo conectar al servidor.", "error");
                            });
                        }
                    });
                });

                function eliminarRol(id) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Esta acción eliminará el rol de forma permanente.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post('usuariosAjax.php', {
                                accion: 'eliminarRol',
                                id
                            }, function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Rol eliminado',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    tablaRoles.ajax.reload();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.error || 'No se pudo eliminar el rol.'
                                    });
                                }
                            }, 'json').fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de conexión',
                                    text: 'No se pudo conectar al servidor.'
                                });
                            });
                        }
                    });
                }

                function eliminarUsuario(id) {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: 'Esta acción eliminará al usuario de forma permanente.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.post("usuariosAjax.php", {
                                accion: "eliminarUsuario",
                                id: id
                            }, function(respuesta) {
                                if (respuesta.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Usuario eliminado',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    tablaUsuarios.ajax.reload();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: respuesta.error || 'Ocurrió un error inesperado.'
                                    });
                                }
                            }, "json").fail(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error de conexión',
                                    text: 'No se pudo conectar al servidor.'
                                });
                            });
                        }
                    });
                }
            </script>
            <!-- Footer -->
            <?php
            require_once __DIR__ . '/includes/footerAdmin.php';
            ?>
</body>

</html>