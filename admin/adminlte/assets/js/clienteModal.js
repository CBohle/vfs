function inicializarFormularioCliente() {
    const opcionesDetallePorTipo = {
        "Propiedad Residencial": ["Casa", "Departamento", "Parcela"],
        "Inmueble Comercial": ["Local", "Oficina", "Centro Comercial"],
        "Activo Industrial": ["Fábrica", "Planta", "Galpón"],
        "Bien Especial": ["Terreno", "Estacionamiento", "Otro"]
    };

    const tipoSelect = $('#tipo_activo');
    const detalleSelect = $('#detalle_activos');
    const detalleActual = detalleSelect.data('valor') || '';

    function llenarDetalles(tipo) {
        detalleSelect.empty().append('<option hidden selected>Seleccionar</option>');
        if (opcionesDetallePorTipo[tipo]) {
            opcionesDetallePorTipo[tipo].forEach(detalle => {
                const selected = (detalle === detalleActual) ? 'selected' : '';
                detalleSelect.append(`<option value="${detalle}" ${selected}>${detalle}</option>`);
            });
        } else {
            detalleSelect.append('<option value="">Selecciona un tipo válido</option>');
        }
    }

    // Llenar al iniciar si ya hay un tipo seleccionado (modo editar o ver)
    if (tipoSelect.val()) {
        llenarDetalles(tipoSelect.val());
    }

    tipoSelect.off('change').on('change', function () {
        llenarDetalles(this.value);
    });

    // Envío del formulario
    $(document).off('submit', '#formCliente').on('submit', '#formCliente', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();

        $.post('clientesAjax.php', {
            accion: 'crear',
            ...Object.fromEntries(new URLSearchParams(formData))
        }, function (response) {
            if (response.success) {
                alert('Cliente creado correctamente.');
                $('#modalVerCliente').modal('hide');
                if (typeof tabla !== 'undefined') {
                    tabla.ajax.reload(null, false);
                }
            } else {
                alert('Error al crear el cliente: ' + (response.error || ''));
                console.error(response);
            }
        }, 'json').fail(function (xhr) {
            console.error('Fallo AJAX:', xhr.responseText);
        });
    });
}

// Opcional: si también usas el botón "Importante" desde clienteModal.php
function toggleImportante(id, estadoActual) {
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

            if (typeof tabla !== 'undefined') {
                tabla.ajax.reload(null, false);
            }
        } else {
            alert('No se pudo actualizar el estado de importancia.');
        }
    }, 'json');
}