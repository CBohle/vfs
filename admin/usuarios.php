<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/Controller/usuariosController.php';
require_once __DIR__ . '/../includes/auth.php';
$roles = obtenerRoles();
requiereRol([1]);
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
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4">Administración de Usuarios y Roles</h2>

        <ul class="nav nav-tabs" id="tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="roles-tab" data-bs-toggle="tab" data-bs-target="#usuarios" type="button" role="tab">Usuarios</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="usuarios-tab" data-bs-toggle="tab" data-bs-target="#roles" type="button" role="tab">Roles</button>
            </li>
        </ul>

        <div class="tab-content pt-3">
            <div class="tab-pane fade" id="roles" role="tabpanel">
                <div class="row">
                    <div class="col-lg-7 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Roles Registrados</span>
                                <button class="btn btn-sm btn-primary" onclick="mostrarFormularioRol()">Nuevo Rol</button>
                            </div>
                            <div class="table-responsive">
                                <table id="tablaRoles" class="table table-hover w-100">
                                    <thead>
                                        <tr>
                                            <th>Nombre del Rol</th>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach (['mensajes', 'postulaciones', 'clientes', 'usuarios', 'roles'] as $modulo): ?>
                                                <tr>
                                                    <td class="text-start"><?= ucfirst($modulo) ?></td>
                                                    <?php foreach (['ver', 'modificar', 'crear', 'eliminar'] as $accion): ?>
                                                        <td><input type="checkbox" class="form-check-input" name="permisos[<?= $modulo ?>][<?= $accion ?>]"></td>
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
            <div class="tab-pane fade show active" id="usuarios" role="tabpanel">
                <div class="row">
                    <div class="col-lg-7 mb-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Usuarios Registrados</span>
                                <button class="btn btn-sm btn-primary" onclick="mostrarFormularioUsuario()">Nuevo Usuario</button>
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
                                    <input type="text" class="form-control" id="emailUsuario" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nombreUsuario" class="form-label">Nombre del Usuario</label>
                                    <input type="text" class="form-control" id="nombreUsuario" name="nombre" required>
                                </div>
                                <div class="mb-3">
                                    <label for="nombreUsuario" class="form-label">Apellido del Usuario</label>
                                    <input type="text" class="form-control" id="apellidoUsuario" name="nombre" required>
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
                                </div>
                                <div class="mb-3">
                                    <label for="confirmarPasswordUsuario" class="form-label">Confirmar Contraseña</label>
                                    <input type="password" class="form-control" id="confirmarPasswordUsuario" name="confirmar_password" required>
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
            <script>
                if (typeof tablaRoles === 'undefined') {
                    var tablaRoles;
                    var tablaUsuarios;
                }

                function inicializarTablaRoles() {
                    if ($.fn.DataTable.isDataTable('#tablaRoles')) tablaRoles.destroy();

                    tablaRoles = $('#tablaRoles').DataTable({
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
                                data: 'descripcion'
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
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="activo">Activo</span>`;
                                        case 'inactivo':
                                        case '0':
                                        case 'false':
                                            clase += 'bg-warning text-dark';
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="inactivo">Inactivo</span>`;
                                        default:
                                            clase += 'bg-secondary';
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="desconocido">Desconocido</span>`;
                                    }
                                }
                            },
                            {
                                data: null,
                                className: 'text-center',
                                render: data => `<button class="btn btn-sm btn-primary" onclick="verRol(${data.id})"><i class="bi bi-eye"></i></button>`
                            }
                        ],
                        dom: '<"datatable-container"t><"datatable-footer d-flex justify-content-end mt-2"p>',
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                        },
                        drawCallback: function () {
                            const paginador = $('#tablaRoles_wrapper .dataTables_paginate');
                            if (paginador.length) {
                            $('#contenedorPaginacionRoles').html(paginador);
                            }
                        },
                        pagingType: 'simple_numbers',
                        pageLength: 10
                    });
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
                                    switch (estado) {
                                        case 'activo':
                                        case '1':
                                        case 'true':
                                            clase += 'bg-success';
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="activo">Activo</span>`;
                                        case 'inactivo':
                                        case '0':
                                        case 'false':
                                            clase += 'bg-warning text-dark';
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="inactivo">Inactivo</span>`;
                                        default:
                                            clase += 'bg-secondary';
                                            return `<span class="${clase}" data-id="${row.id}" data-activo="desconocido">Desconocido</span>`;
                                    }
                                }
                            },
                            {
                                data: null,
                                className: 'text-center',
                                render: data => `<button class="btn btn-sm btn-primary" onclick="verUsuario(${data.id})"><i class="bi bi-eye"></i></button>`
                            }
                        ],
                        dom: '<"datatable-container"t><"datatable-footer d-flex justify-content-end mt-2"p>',
                        language: {
                            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                        },
                        drawCallback: function () {
                            const paginador = $('#tablaUsuarios_wrapper .dataTables_paginate');
                            if (paginador.length) {
                                $('#contenedorPaginacionUsuarios').html(paginador);
                            }
                        },
                        pagingType: 'simple_numbers',
                        pageLength: 10
                    });
                }

                function mostrarFormularioRol() {
                    $('#formRol')[0].reset();
                    $('#formRolTitulo').text('Nuevo Rol');
                    $('#rol_id').val('');
                    $('#formularioRolContainer').show();
                    $('#nombreRol').prop('disabled', false);
                    $('input[name^="permisos"]').prop('checked', false).prop('disabled', false);
                    $('#formRol .btn[type="submit"]').show();
                }

                function ocultarFormularioRol() {
                    $('#formularioRolContainer').hide();
                }

                function verRol(id) {
                    $.post('usuariosAjax.php', {
                        accion: 'obtenerRolPorId',
                        id
                    }, function(data) {
                        mostrarFormularioRol();
                        $('#formRolTitulo').text('Editar Rol');
                        $('#rol_id').val(data.id);
                        $('#nombreRol').val(data.nombre).prop('disabled', true);
                        $('#descripcionRol').val(data.descripcion).prop('disabled', false);
                        $('input[name^="permisos"]').prop('checked', false).prop('disabled', false);
                        if (data.permisos) {
                            for (let modulo in data.permisos) {
                                for (let accion in data.permisos[modulo]) {
                                    $(`input[name="permisos[${modulo}][${accion}]"]`).prop('checked', true);
                                }
                            }
                        }
                    }, 'json');
                }

                $('#formRol').on('submit', function(e) {
                    e.preventDefault();
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

                    $.post('usuariosAjax.php', {
                        accion: 'guardarRol',
                        id: $('#rol_id').val(),
                        nombre: $('#nombreRol').val().trim(),
                        descripcion: $('#descripcionRol').val().trim(),
                        permisos
                    }, function(response) {
                        if (response.success) {
                            alert('Rol guardado correctamente');
                            ocultarFormularioRol();
                            tablaRoles.ajax.reload();
                        } else {
                            alert('Error al guardar');
                        }
                    }, 'json');
                });

                $('#tablaRoles').on('click', '.estado-toggle', function() {
                    const id = $(this).data('id');
                    $.post('usuariosAjax.php', {
                        accion: 'toggleEstadoRol',
                        id
                    }, function(response) {
                        if (response.success) {
                            tablaRoles.ajax.reload(null, false);
                        } else {
                            alert('Error al cambiar estado');
                        }
                    }, 'json');
                });

                $(document).ready(function() {
                    inicializarTablaRoles();
                    inicializarTablaUsuarios();
                });

                function mostrarFormularioUsuario() {
                    $('#formUsuario')[0].reset();
                    $('#formUsuarioTitulo').text('Nuevo Usuario');
                    $('#usuario_id').val('');
                    $('#formularioUsuarioContainer').show();
                    $('#emailUsuario').prop('disabled', false);
                    $('#formUsuario .btn[type="submit"]').show();
                }

                function ocultarFormularioUsuario() {
                    $('#formularioUsuarioContainer').hide();
                }

                function verUsuario(id) {
                    $.post('usuariosAjax.php', {
                        accion: 'obtenerUsuarioPorId',
                        id
                    }, function(data) {
                        mostrarFormularioUsuario();
                        $('#formUsuarioTitulo').text('Editar Usuario');
                        $('#usuario_id').val(data.id);
                        $('#emailUsuario').val(data.email).prop('disabled', true);
                        $('#nombreUsuario').val(data.nombre).prop('disabled', false);
                        $('#apellidoUsuario').val(data.apellido).prop('disabled', false);
                        $('#rolUsuario').val(data.rol_id).prop('disabled', false);
                    }, 'json');
                }
                $('#formUsuario').on('submit', function(e) {
                    e.preventDefault();

                    const password = $('#passwordUsuario').val().trim();
                    const confirmarPassword = $('#confirmarPasswordUsuario').val().trim();

                    if (password !== confirmarPassword) {
                        alert('Las contraseñas no coinciden. Por favor, verifica ambos campos.');
                        return; // NO continúa con la creación del usuario
                    }

                    // Si las contraseñas coinciden, continúa con la petición AJAX
                    $.post('usuariosAjax.php', {
                        accion: 'guardarUsuario',
                        id: $('#usuario_id').val(),
                        email: $('#emailUsuario').val().trim(),
                        nombre: $('#nombreUsuario').val().trim(),
                        apellido: $('#apellidoUsuario').val().trim(),
                        password: password,
                        rol_id: $('#rolUsuario').val()
                    }, function(response) {
                        if (response.success) {
                            alert('Usuario guardado correctamente');
                            ocultarFormularioUsuario();
                            tablaUsuarios.ajax.reload();
                        } else {
                            alert('Error al guardar');
                        }
                    }, 'json');
                });
                $(document).on('click', '.estado-click', function () {
                    const id = $(this).data('id');
                    const activoRaw = $(this).data('activo');
                    if (typeof activoRaw !== 'string') return;

                        const estadoActual = activoRaw.toLowerCase();
                        let FnuevoEstado = '';

                        if (estadoActual === 'activo') {
                            nuevoEstado = 'inactivo';
                        } else if (estadoActual === 'inactivo') {
                            nuevoEstado = 'activo';
                        } else {
                            return;
                        }

                        $.post('usuariosAjax.php', {
                            accion: 'cambiar_estado',
                            id: id,
                            estado: nuevoEstado
                        }, function (response) {
                            if (response.success) {
                                // ⚡ animación sin recargar toda la tabla
                                const $span = $(`.estado-click[data-id="${id}"]`);
                                const nuevaClase = nuevoEstado === 'activo' ? 'bg-success' : 'bg-warning text-dark';
                                const nuevoTexto = nuevoEstado.charAt(0).toUpperCase() + nuevoEstado.slice(1);

                                $span.fadeOut(200, function () {
                                    $span.removeClass('bg-success bg-warning text-dark')
                                        .addClass(nuevaClase)
                                        .text(nuevoTexto)
                                        .data('activo', nuevoEstado)
                                        .fadeIn(200);
                                });
                            } else {
                                alert('No se pudo cambiar el estado.');
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