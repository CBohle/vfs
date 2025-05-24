<?php
// Incluye config.php que determina la BASE_URL
require_once __DIR__ . '/../includes/config.php';

// DETECTAR SI ESTÁ EN LA LANDING O NO, PARA REDIRECCIONAR
$is_landing = basename($_SERVER['PHP_SELF']) === 'index.php';
$base_url = $is_landing ? '' : BASE_URL . 'index.php';
?>

<!-- ESTRUCTURA DEL FOOTER -->
<footer class="bg-light py-5">
    <div class="container px-4 px-lg-5">
        <div class="row text-center text-md-start">
            <!-- COLUMNA IZQUIERDA: IDENTIFICACIÓN -->
            <div class="col-12 col-md-4 mb-4">
                <a href="#top">
                    <img src="<?= BASE_URL ?>assets/images/logo/LogoVFS2.png" alt="Logo de la empresa" style="max-width: 150px;">
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
<script src="<?= BASE_URL ?>assets/js/scripts.js"></script>
<script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>

<!-- VALIDACION FORMULARIO DE POSTULACION -->
<?php if (basename($_SERVER['PHP_SELF']) === 'postular.php') : ?>
    <script src="<?= BASE_URL ?>assets/js/validacionPostulacionForm.js"></script>
<?php endif; ?>

<script>
    // Despliegue del campo 13.1 en caso que sea "Si" la respuesta del capmo 13
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