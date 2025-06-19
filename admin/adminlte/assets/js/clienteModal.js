// Protege la ejecución para evitar múltiples registros
if (!window.clienteModalYaInicializado) {
    window.clienteModalYaInicializado = true;

    window.formClienteEventosRegistrados = false;

    // Función para inicializar lógica del formulario (una sola vez)
    window.inicializarFormularioCliente = function () {
        if (window.formClienteEventosRegistrados) return;
        window.formClienteEventosRegistrados = true;

        const opcionesDetallePorTipo = {
            "Propiedad Residencial": ["Casa", "Departamento", "Parcela Agroresidencial", "Sitio Urbano", "Sitio Rural"],
            "Inmueble Comercial": ["Oficina", "Local Comercial"],
            "Activo Industrial": ["Bodega", "Industria", "Terreno Industrial"],
            "Bien Especial": ["Hotel", "Clínica", "Colegio", "Otros inmuebles de uso específico"]
        };

        const tipoSelect = $('#tipo_activo');
        const detalleSelect = $('#detalle_activos');
        const detalleActual = detalleSelect.data('valor') || '';

        function llenarDetalles(tipo) {
            detalleSelect.empty().append('<option hidden selected>""</option>');
            if (opcionesDetallePorTipo[tipo]) {
                opcionesDetallePorTipo[tipo].forEach(detalle => {
                    const selected = (detalle === detalleActual) ? 'selected' : '';
                    detalleSelect.append(`<option value="${detalle}" ${selected}>${detalle}</option>`);
                });
            } else {
                detalleSelect.append('<option value="">Selecciona un tipo válido</option>');
            }
        }

        // Evento: al cambiar tipo_activo, se actualiza detalle_activos
        tipoSelect.on('change', function () {
            const tipoSeleccionado = $(this).val();
            llenarDetalles(tipoSeleccionado);
        });

        // Llenar al iniciar si ya hay uno seleccionado
        if (tipoSelect.val()) {
            llenarDetalles(tipoSelect.val());
        }

        // Bloqueo adicional del submit automático
        $('#formCliente').on('submit.preventDefault', function (e) {
            e.preventDefault();
            //console.warn('🚫 Submit bloqueado por prevención');
        });

        // Para depurar si se dispara otro submit (no debería)
        $(document).on('submit', '#formCliente', () => {
            //console.warn('⚠️ Evento submit detectado');
        });
    };

    // Función adicional opcional para cargar eventos extra (si aplica)
    window.cargarEventosSelectActivo = function () {
        //console.log('📌 cargarEventosSelectActivo ejecutado');
        // Aquí puedes registrar otros eventos si hace falta
    };
}
