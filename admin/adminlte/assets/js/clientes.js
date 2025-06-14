function crearCliente() {
    $('#contenidoModalCliente').html('<p class="text-center text-muted">Cargando formulario...</p>');
    $('#modalVerCliente').modal('show');

    $.get('clienteModal.php', function (respuesta) {
        $('#contenidoModalCliente').html(respuesta);

        $('#botonImportanteWrapper').html(`
            <button type="button" class="btn btn-primary" id="btnCrearCliente">Crear Cliente</button>
        `);

        $(document).off('click', '#btnCrearCliente').on('click', '#btnCrearCliente', function () {
            $('#formCliente').submit();
        });

        // Verifica si ya está cargado el script externo
        const yaCargado = document.querySelector('script[src*="clienteModal.js"]');
        if (!yaCargado) {
            const script = document.createElement('script');
            script.src = BASE_ADMIN_URL + 'adminlte/assets/js/clienteModal.js?v=' + new Date().getTime();
            script.onload = function () {
                if (typeof inicializarFormularioCliente === 'function') {
                    inicializarFormularioCliente();
                }
            };
            document.body.appendChild(script);
        } else {
            if (typeof inicializarFormularioCliente === 'function') {
                inicializarFormularioCliente();
            }
        }
    }).fail(function () {
        $('#contenidoModalCliente').html('<p class="text-danger">Error al cargar el formulario.</p>');
    });
}
function destruirYRestaurarEncabezado(idTabla) {
    const $tabla = $(idTabla);
    const theadHtml = $tabla.find('thead').prop('outerHTML'); // ⚠️ más robusto
    const tbodyHtml = $tabla.find('tbody').length ? $tabla.find('tbody').prop('outerHTML') : '<tbody></tbody>';

    // Eliminar completamente y reconstruir tabla
    $tabla.DataTable().clear().destroy();
    $tabla.empty(); // ⚠️ importante: borrar todo el contenido de la tabla

    // Reconstruir tabla con thead y tbody válidos
    $tabla.append(theadHtml).append(tbodyHtml);
}
window.toggleImportante = function (id, estadoActual) {
    const nuevoValor = estadoActual === 1 ? 0 : 1;
            $.post('clientesAjax.php', {
                accion: 'importante',
                cliente_id: id,
                importante: nuevoValor
            }, function (response) {
                if (response.success) {
                    const boton = $('#btnImportante');
                    const icono = $('#iconoImportante');
                    const texto = $('#textoImportante');

                    setTimeout(() => {
                        icono.removeClass('balanceando');
                    }, 500);   
                    if (nuevoValor === 1) {
                        boton.removeClass('btn-warning').addClass('btn-outline-warning');
                        icono.removeClass('bi-star').addClass('bi-star-fill');
                        texto.text('Marcar como no importante');
                    } else {
                        boton.removeClass('btn-outline-warning').addClass('btn-warning');
                        icono.removeClass('bi-star-fill').addClass('bi-star');
                        texto.text('Marcar como importante');
                    }

                    boton.attr('onclick', `toggleImportante(${id}, ${nuevoValor})`);

                    const iconoTabla = $(`#row_${id} .marcarImportante`);
                    if (iconoTabla.length > 0) {
                        iconoTabla
                            .removeClass('bi-star bi-star-fill text-warning text-muted')
                            .addClass(nuevoValor === 1 ? 'bi-star-fill text-warning' : 'bi-star text-muted')
                            .attr('data-valor', nuevoValor)
                            .addClass('balanceando');

                        setTimeout(() => {
                            iconoTabla.removeClass('balanceando');
                        }, 500);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al actualizar',
                        text: 'No se pudo actualizar el estado de importancia.',
                        confirmButtonText: 'Cerrar'
                    });
                }
            }, 'json');
        }            