<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/usuariosController.php';
$roles = obtenerRoles();
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
            font-size: 0.9rem;
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
            display: none !important;
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
            padding: 1rem 0.75rem;
        }

        table.dataTable thead th {
            background-color: #f0f2f5;
            font-weight: 600;
            color: #495057;
            padding: 0.75rem;
        }

        #tablaRolesWrapper .card-body {
            padding: 0;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container py-4">
        <h2 class="mb-4">Administración de Usuarios y Roles</h2>
        <div class="row mt-3">
            <div class="col-lg-7 mb-3">
                <div class="card" id="tablaRolesWrapper">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Roles Registrados</span>
                        <button class="btn btn-sm btn-primary" onclick="mostrarFormularioRol()">Nuevo Rol</button>
                    </div>
                    <div class="card-body">
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

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        if (typeof tabla === 'undefined') {
            var tabla;
        }

        function inicializarTablaRoles() {
            if ($.fn.DataTable.isDataTable('#tablaRoles')) {
                tabla.destroy();
            }

            tabla = $('#tablaRoles').DataTable({
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
                        render: function(data, type, row) {
                            const estadoTexto = (data == 1 || data === true) ? 'Activo' : 'Inactivo';
                            const badgeClass = (data == 1 || data === true) ? 'bg-success' : 'bg-secondary';
                            return `<span class="badge ${badgeClass} estado-toggle" data-id="${row.id}" style="cursor: pointer;">${estadoTexto}</span>`;
                        }
                    },
                    {
                        data: null,
                        className: 'text-center',
                        render: function(data) {
                            return `<button class="btn btn-sm btn-primary me-1" onclick="verRol(${data.id})"><i class="bi bi-eye"></i></button>`;
                        }
                    }
                ],
                dom: 't',
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        }

        function mostrarFormularioRol() {
            $('#formRol')[0].reset();
            $('#rol_id').val('');
            $('#formRolTitulo').text('Nuevo Rol');
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

            const id = $('#rol_id').val();
            const nombre = $('#nombreRol').val().trim();
            const descripcion = $('#descripcionRol').val().trim();
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
                id,
                nombre,
                descripcion,
                permisos
            }, function(response) {
                if (response.success) {
                    alert('Rol guardado correctamente');
                    ocultarFormularioRol();
                    tabla.ajax.reload();
                } else {
                    alert('Error al guardar');
                }
            }, 'json');
        });

        $(document).ready(() => {
            inicializarTablaRoles();
        });
        $('#tablaRoles').on('click', '.estado-toggle', function() {
            const span = $(this);
            const id = span.data('id');

            $.post('usuariosAjax.php', {
                accion: 'toggleEstadoRol',
                id: id
            }, function(response) {
                if (response.success) {
                    // Recargar solo la fila si quieres eficiencia
                    $('#tablaRoles').DataTable().ajax.reload(null, false);
                } else {
                    alert('No se pudo cambiar el estado');
                }
            }, 'json');
        });
    </script>
</body>

</html>