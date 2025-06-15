function actualizarPermisosSesion() {
    return $.get('../includes/Controller/obtenerPermisosActualizados.php', function (res) {
        if (!res.success) {
            console.warn('No se pudieron actualizar los permisos en sesión:', res.error);
        } else {
            console.log('Permisos actualizados:', res.permisos);
        }
    }, 'json').fail(function () {
        console.warn('Error al conectar con el servidor para actualizar permisos.');
    });
}
function guardarRolYActualizarPermisos(datosRol) {
    $.ajax({
        url: 'usuariosAjax.php',
        method: 'POST',
        data: {
            accion: 'guardarRol',
            ...datosRol
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Rol guardado',
                    text: 'Los permisos han sido actualizados correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });

                actualizarPermisosSesion().done(function (resPermisos) {
                    if (resPermisos.success) {
                        console.log('Permisos en sesión actualizados');

                        if (window.tablaRoles) tablaRoles.ajax.reload(null, false);
                        if (window.tablaUsuarios) tablaUsuarios.ajax.reload(null, false);

                        // ✅ Preguntar si desea recargar para aplicar cambios visuales
                        setTimeout(() => {
                            Swal.fire({
                                icon: 'info',
                                title: 'Aplicar cambios',
                                text: 'Para ver reflejados los cambios de permisos es necesario recargar la página. ¿Deseas hacerlo ahora?',
                                showCancelButton: true,
                                confirmButtonText: 'Sí, recargar',
                                cancelButtonText: 'No, después',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Guardar indicador en sessionStorage
                                    sessionStorage.setItem('recargaExitosa', '1');
                                    location.reload();
                                }
                            });
                        }, 2100); // esperar a que se oculte el primer SweetAlert
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.error || 'No se pudo guardar el rol.'
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo conectar con el servidor.'
            });
        }
    });
}
function guardarUsuarioYActualizar(datosUsuario) {
    $.ajax({
        url: 'usuariosAjax.php',
        method: 'POST',
        data: {
            accion: 'guardarUsuario',
            ...datosUsuario
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Usuario guardado',
                    text: 'Los cambios se aplicaron correctamente',
                    timer: 2000,
                    showConfirmButton: false
                });

                actualizarPermisosSesion().done(function (resPermisos) {
                    if (resPermisos.success) {
                        if (window.tablaUsuarios) tablaUsuarios.ajax.reload(null, false);

                        setTimeout(() => {
                            Swal.fire({
                                icon: 'info',
                                title: 'Aplicar cambios',
                                text: 'Para ver reflejados los cambios de permisos es necesario recargar la página. ¿Deseas hacerlo ahora?',
                                showCancelButton: true,
                                confirmButtonText: 'Sí, recargar',
                                cancelButtonText: 'No, después',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    sessionStorage.setItem('recargaExitosa', '1');
                                    location.reload(); // ✅ solo recarga la misma URL actual
                                }
                            });
                        }, 2100);
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.error || 'No se pudo guardar el usuario.'
                });
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo conectar con el servidor.'
            });
        }
    });
}
