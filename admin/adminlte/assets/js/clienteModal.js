    function inicializarFormularioCliente() {
        const opcionesDetallePorTipo = {
            "Propiedad Residencial": ["Casa", "Departamento", "Parcela Agroresidencial", "Sitio Urbano", "Sitio Rural"],
            "Inmueble Comercial": ["Oficina", "Local Comercial"],
            "Activo Industrial": ["Bodega", "Industria", "Terreno Industrial"],
            "Bien Especial": ["Hotel", "Clínica", "Colegio", "Otros inmuebles de uso específico"]
        };
        const tipoSelect = $('#tipo_activo');

        function llenarDetalles(tipo) {
            const detalleSelect = $('#detalle_activos');
            const detalleActual = detalleSelect.attr('data-valor') || '';

            detalleSelect.empty();

            if (!tipo || !opcionesDetallePorTipo[tipo]) {
                detalleSelect.append('<option hidden selected>Selecciona un tipo válido</option>');
                return;
            }

            detalleSelect.append('<option hidden selected>Seleccionar</option>');
            opcionesDetallePorTipo[tipo].forEach(detalle => {
                const selected = (detalle === detalleActual) ? 'selected' : '';
                detalleSelect.append(`<option value="${detalle}" ${selected}>${detalle}</option>`);
            });
            $('#detalle_activos option').each(function () {
                //console.log(`- ${$(this).val()} ${$(this).is(':selected') ? '(seleccionado)' : ''}`);
            });
        }

        // Llenar al iniciar si ya hay un tipo seleccionado (modo editar o ver)
        const tipoInicial = $('#tipo_activo').val();
        const detalleSelect = $('#detalle_activos');
        const detalleActual = detalleSelect.attr('data-valor') || '';

        if (tipoInicial && tipoInicial !== '') {
            llenarDetalles(tipoInicial); // Solo si hay tipo seleccionado
        } else {
            detalleSelect.empty().append('<option hidden selected>Selecciona un tipo válido</option>');
        }

        tipoSelect.off('change').on('change', function () {
            llenarDetalles(this.value);
        });

        // Envío del formulario
        $(document).off('submit', '#formCliente').on('submit', '#formCliente', function (e) {
            e.preventDefault();

            const datos = $(this).serialize();

            $.post('clientesAjax.php', datos, function (response) {
                if (response.success) {
                    $('#modalVerCliente').modal('hide');
                    if (window.tablaClientes) {
                        tablaClientes.ajax.reload(null, false);
                    }
                    Swal.fire({
                        icon: 'success',
                        title: datos.includes('accion=actualizar') ? 'Cliente actualizado' : 'Cliente creado',
                        text: datos.includes('accion=actualizar')
                            ? 'El cliente fue modificado correctamente.'
                            : 'El cliente fue agregado correctamente.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    // Mostrar mensaje especial si viene como "warning"
                    const tipo = response.type || 'error'; // default a 'error' si no viene
                    const texto = response.message || (
                        datos.includes('accion=actualizar')
                            ? 'No se pudo actualizar el cliente.'
                            : 'No se pudo crear el cliente.'
                    );
                    Swal.fire({
                        icon: tipo,
                        title: tipo === 'warning' ? 'Sin cambios' : 'Error',
                        text: texto
                    });
                }
            }, 'json').fail(function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'No se pudo contactar con el servidor.'
                });
            });
        });
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

                icono.addClass("balanceando");
                setTimeout(() => {
                    icono.removeClass("balanceando");
                }, 500);

                // actualizar clases/icono
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
                    title: 'No se pudo actualizar',
                    text: 'Ocurrió un error al cambiar la importancia del cliente.',
                    confirmButtonText: 'Entendido'
                });
            }
        }, 'json');
    }