<?php
// Detectar si estamos en la landing o no
$is_landing = basename($_SERVER['PHP_SELF']) === 'index.php';
$base_url = $is_landing ? '' : '/public/index.php'; // Ajusta esta ruta si cambia
?>

<footer class="bg-light py-5">
    <div class="container px-4 px-lg-5">
        <div class="row text-center text-md-start">
            <!-- COLUMNA IZQUIERDA: IDENTIFICACIÓN -->
            <div class="col-12 col-md-4 mb-4">
                <a href="#top">
                    <img src="../assets/images/logo/LogoVFS2.png" alt="Logo de la empresa" style="max-width: 150px;">
                </a>
                <br>
                <p class="mb-0">Calle Ejemplo 123</p>
                <p class="mb-0">Ciudad, País</p>
            </div>
            <!-- COLUMNA CENTRO: NAVEGAR POR LA PÁGINA -->
            <div class="col-12 col-md-4 mb-4">
                <h5>Enlaces rápidos</h5>
                <ul class="list-unstyled">
                    <li><a href="<?= $base_url ?>#faq">Preguntas frecuentes</a></li>
                    <li><a href="<?= $base_url ?>#contacto">Formulario de contacto</a></li>
                    <li><a href="<?= $base_url ?>#servicios">Nuestros servicios</a></li>
                </ul>
            </div>
            <!-- COLUMNA DERECHA: CONTACTO-->
            <div class="col-12 col-md-4 mb-4">
                <h5>Contacto</h5>
                <ul class="list-unstyled">
                    <li class="bi-phone"><a href="tel:+1234567890"> +1 234 567 890</a></li>
                    <li class="bi-envelope"><a href="mailto:contacto@empresa.com"> contacto@vfs.cl</a></li>
                </ul>
                <a href="<?= $base_url ?>#contacto" class="btn btn-primary mt-2">Contáctanos</a>
            </div>
        </div>
        <!-- Línea de copyright -->
        <div class="text-center small text-muted mt-4">
            © 2025 VFS. Todos los derechos reservados.
        </div>
    </div>
</footer>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SimpleLightbox plugin JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
<!-- Core theme JS-->
<script src="/assets/js/scripts.js"></script>
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<!-- * *                               SB Forms JS                               * *-->
<!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
<!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
<script>
    document.getElementById('contactoForm').addEventListener('submit', function(event) {
        const form = event.target;
        const mensaje = form.mensaje;
        const servicio = form.servicio;
        let valid = true;

        // Validación NOMBRE
        const nombre = form.nombre.value.trim();
        const nombreInput = form.nombre;
        const nombreError = document.getElementById('nombre-error');

        if (nombre === "") {
            nombreError.textContent = "El nombre es obligatorio.";
            nombreInput.classList.add("is-invalid");
            valid = false;
        } else if (nombre.length < 2) {
            nombreError.textContent = "El nombre debe tener al menos 2 carácteres.";
            nombreInput.classList.add("is-invalid");
            valid = false;
        } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre)) {
            nombreError.textContent = "El nombre no debe contener números ni símbolos.";
            nombreInput.classList.add("is-invalid");
            valid = false;
        } else {
            nombreInput.classList.remove("is-invalid");
        }

        // Puedes replicar el mismo patrón para:
        // - apellido → form.apellido.value, id="apellido-error"
        // - mensaje → validar longitud mínima y máxima con mensajes distintos
        // - teléfono → patrón y contenido válido
        // - servicio → si está vacío

        if (!valid) {
            event.preventDefault();
            event.stopPropagation();
        }
    });
</script>
<!-- Script para el campo adicional, del campo 13, de Formulario de postulación -->
<script>
    function mostrarCampoEspecificar() {
        const seleccion = document.getElementById("formacion_tasacion").value;
        const campoEspecificar = document.getElementById("campo_especificar");
        const inputEspecificar = document.getElementById("detalle_formacion");

        if (seleccion === "Sí") {
            campoEspecificar.style.display = "block";
            inputEspecificar.setAttribute("data-sb-validations", "required");
        } else {
            campoEspecificar.style.display = "none";
            inputEspecificar.removeAttribute("data-sb-validations");
            inputEspecificar.value = "";
        }
    }
</script>
<!-- // Validación personalizada para mensaje
if (mensaje.value.length < 20) {
    mensaje.classList.add('is-invalid');
    document.getElementById('mensajeError').textContent="El mensaje debe tener al menos 20 caracteres." ;
    valid=false;
    } else if (mensaje.value.length> 1000) {
    mensaje.classList.add('is-invalid');
    document.getElementById('mensajeError').textContent = "El mensaje no puede superar los 1000 caracteres.";
    valid = false;
    } else {
    mensaje.classList.remove('is-invalid');
    }

    // Validación del campo servicio (select)
    if (!servicio.value) {
    servicio.classList.add('is-invalid');
    valid = false;
    } else {
    servicio.classList.remove('is-invalid');
    }

    if (!form.checkValidity() || !valid) {
    event.preventDefault();
    event.stopPropagation();
    form.classList.add('was-validated');
    }
    <!-- });
    </script> --> -->