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