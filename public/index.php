<!-- Creative - Start Bootstrap Theme
    https://startbootstrap.com/template/creative
    Licencia MIT -->
<!-- Vista pública del landing -->
<?php
require_once __DIR__ . '/../includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>VFS</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>assets/favicon.ico" />
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="<?= BASE_URL ?>assets/css/styles.css" rel="stylesheet" />
</head>

<!-- INCLUDE HEADER  -->
<?php include_once __DIR__ . '/../includes/header.php'; ?>

<!-- INICIO MASTHEAD-->
<header class="masthead">
    <div class="container px-4 px-lg-5 h-100">
        <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end">
                <h1 class="text-white font-weight-bold">Profesionalismo en cada tasación</h1>
                <hr class="divider" />
            </div>
            <div class="col-lg-8 align-self-baseline">
                <p class="text-white-75 mb-5">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolorem vel quam, natus iure laborum quod consequatur voluptatibus ex facere necessitatibus fugit praesentium, rem accusamus exercitationem, consequuntur deleniti. Tempore, suscipit vel?</p>
                <a class="btn btn-primary btn-xl" href="#nosotros">¡Conocer más!</a>
            </div>
        </div>
    </div>
</header>
<!-- FIN MASTHEAD -->


<!-- INICIO SECCIÓN QUIÉNES SOMOS-->
<section class="page-section bg-primary" id="nosotros">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="text-white mt-0">Comprometidos con tu éxito</h2>
                <hr class="divider divider-light" />
                <p class="text-white-75 mb-4">Somos una empresa especializada en tasación y consultoría inmobiliaria, comprometida con la entrega de soluciones integrales que respalden decisiones estratégicas en el ámbito inmobiliario. Nuestra misión es proporcionar valor agregado a nuestros clientes mediante información precisa y asesoramiento especializado, facilitando procesos de inversión, financiamiento y gestión de activos inmobiliarios.</p>
                <a class="btn btn-light btn-xl" href="#servicios">¡Comencemos!</a>
            </div>
        </div>
    </div>
</section>
<!-- FIN SECCIÓN QUIÉNES SOMOS-->

<!-- SERVICIOS-->
<div class="page-section3" id="servicios">
    <div class="container-fluid p-0">
        <h2 class="text-center mt-0 page-section2">Tu socio en tasaciones</h2>
        <hr class="divider" />
        <div class="row g-0">
            <div class="col-lg-6 col-sm-6">
                <a class="servicios-box"
                    href="<?= BASE_URL ?>assets/images/servicios/fullsize/1.jpg"
                    title="Tasación de bienes raíces asasasasasasasasasasaasaosasUasasasasasa">
                    <img class="img-fluid" src="<?= BASE_URL ?>assets/images/servicios/thumbnails/1.jpg" alt="Tasación de bienes raíces" />
                    <div class="servicios-box-caption">
                        <div class="project-category text-white-50">Tasación</div>
                        <div class="project-name">Tasación de bienes raíces</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-6 col-sm-6 pe-4">
                <a class="servicios-box" href="<?= BASE_URL ?>assets/images/servicios/fullsize/2.jpg" title="Consultoría inmobiliaria">
                    <img class="img-fluid" src="<?= BASE_URL ?>assets/images/servicios/thumbnails/2.jpg" alt="Consultoría inmobiliaria" />
                    <div class="servicios-box-caption">
                        <div class="project-category text-white-50">Consultoría</div>
                        <div class="project-name">Consultoría inmobiliaria</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<!-- FIN SECCIÓN SERVICIOS-->


<!-- LLAMADA A LA ACCIÓN-->
<section class="page-section bg-dark text-white">
    <div class="container px-4 px-lg-5 text-center">
        <h2 class="mb-4">¡Solicita tu tasación!</h2>
        <a class="btn btn-light btn-xl" href="#contacto">Contáctanos</a>
    </div>
</section>
<!-- FIN LLAMADA A LA ACCIÓN -->

<!-- INICIO SECCIÓN FAQ -->
<section class="page-section" id="faq">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 col-xl-6 text-center">
                <h2 class="mt-0">Preguntas Frecuentes</h2>
                <hr class="divider" />
                <p class="text-muted mb-5">Resuelve tus dudas antes de contactarnos.</p>
            </div>
        </div>
        <div class="accordion" id="faqAccordion">
            <!-- PREGUNTA 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        ¿Qué tipos de propiedades pueden ser tasadas?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Ofrecemos servicios de tasación para una amplia gama de activos inmobiliarios, incluyendo:</p>
                        <ul>
                            <li>Casas y departamentos</li>
                            <li>Terrenos urbanos y rurales</li>
                            <li>Oficinas y locales comerciales</li>
                            <li>Bodegas e industrias</li>
                            <li>Parcelas agroresidenciales</li>
                            <li>Activos de uso específico (hoteles, clínicas, escuelas, etc.)</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- PREGUNTA 2  -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        ¿Qué información se requiere para solicitar una tasación? </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Para iniciar el proceso de tasación, solicitamos los siguientes datos:</p>
                        <ul>
                            <li>Nombre y apellido del propietario</li>
                            <li>Contacto (email y teléfono)</li>
                            <li>Tipo de propiedad</li>
                            <li>Rol del inmueble</li>
                            <li>Dirección exacta y comuna</li>
                            <li>Antecedentes de la propiedad (escritura, planos, informes previos, etc.)</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- PREGUNTA 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        ¿Cuánto tiempo toma la entrega del informe de tasación?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>El plazo de entrega del informe varía según el tipo de propiedad:</p>
                        <ul>
                            <li>Casa, departamento, terreno urbano: 5 días hábiles</li>
                            <li>Propiedades comerciales o industriales: 7 a 10 días hábiles</li>
                            <li>Activos de uso específico: 10 a 15 días hábiles</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- PREGUNTA 4 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseThree">
                        ¿Qué métodos de valoración utilizan?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Utilizamos metodologías reconocidas internacionalmente, tales como:</p>
                        <ul>
                            <li>Enfoque de mercado (comparación de ventas)</li>
                            <li>Método de costos (valor de reposición)</li>
                            <li>Enfoque de ingresos (capitalización de rentas)</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- PREGUNTA 5 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseThree">
                        ¿Qué sucede si no estoy conforme con el informe de tasación?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>En caso de disconformidad, el cliente puede solicitar una reconsideración. Nuestro equipo revisará el informe y, si corresponde, se realizarán los ajustes pertinentes.</p>
                    </div>
                </div>
            </div>
            <!-- PREGUNTA 6 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSix">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseThree">
                        ¿Qué servicios incluye la consultoría inmobiliaria?
                    </button>
                </h2>
                <div id="collapseSix" class="accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Nuestros servicios de consultoría abordan:</p>
                        <ul>
                            <li>Estudios de mercado y viabilidad de proyectos</li>
                            <li>Asesoramiento en inversiones y desarrollo inmobiliario</li>
                            <li>Optimización de carteras de activos</li>
                            <li>Evaluación del rendimiento de propiedades arrendadas</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- PREGUNTA 7 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSeven">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseThree">
                        ¿Cómo se realiza el pago de los servicios?
                    </button>
                </h2>
                <div id="collapseSeven" class="accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>El pago puede realizarse mediante transferencia bancaria o mercado pago. Los detalles del proceso de pago se incluirán en la cotización enviada al cliente.</p>
                    </div>
                </div>
            </div>
            <!-- PREGUNTA 8 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingEight">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseThree">
                        ¿Qué garantías ofrecen sobre la calidad del informe?
                    </button>
                </h2>
                <div id="collapseEight" class="accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        <p>Nuestros informes cumplen con las normativas IFRS y estándares nacionales, garantizando precisión, confiabilidad y validez ante instituciones financieras y reguladoras.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- FIN SECCIÓN FAQ -->

<!-- INICIO SECCIÓN FORMULARIO DE CONTACTO-->
<section class="page-section bg-tertiary" id="contacto">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 col-xl-6 text-center">
                <h2 class="mt-0">Atención personalizada</h2>
                <hr class="divider" />
                <p class="text-muted mb-5">Para solicitudes, cotizaciones o información adicional, por favor complete el siguiente formulario.</p>
            </div>
        </div>
        <div class="row gx-4 gx-lg-5 justify-content-center mb-5">
            <div class="col-lg-6">
                <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'enviado'): ?>
                <div class="alert alert-success text-center">¡Tu mensaje ha sido enviado con éxito!</div>
            <?php elseif (isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center">Por favor completa todos los campos.</div>
            <?php endif; ?>
                <!-- INICIO FORMULARIO DE CONTACTO CON VALIDACIONES POR CAMPO -->
                <form id="contactoForm" class="novalidate" action="../includes/Controller/procesar_mensaje.php" method="post">
                    <!-- Campo 1: Nombre OK-->
                    <div class="form-floating mb-3">
                        <input
                            class="form-control"
                            id="nombre"
                            name="nombre"
                            type="text"
                            placeholder="Ingrese su nombre"
                            required
                            minlength="2"
                            maxlength="50"
                            pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" />
                        <label for="nombre">Nombre</label>
                        <div class="invalid-feedback" id="nombre-error">
                            El nombre debe tener solo letras, entre 2 y 50 caracteres.
                        </div>
                    </div>
                    <!-- Opción 2 apellido OK-->
                    <div class="form-floating mb-3">
                        <input
                            class="form-control"
                            id="apellido"
                            name="apellido"
                            type="text"
                            placeholder="Ingrese su apellido"
                            required
                            minlength="2"
                            maxlength="50"
                            pattern="^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$" />
                        <label for="apellido">Apellido</label>
                        <div class="invalid-feedback" id="apellido-error">
                            El apellido debe tener solo letras, entre 2 y 50 caracteres.
                        </div>
                    </div>
                    <!-- Campo 3: Mail OK-->
                    <div class="form-floating mb-3">
                        <input name="email" class="form-control" id="email" type="email" placeholder="name@example.com" required />
                        <label for="email">Correo</label>
                        <div class="invalid-feedback">Ingrese un correo válido.</div>
                    </div>
                    <!-- Campo 4: Teléfono OK-->
                    <div class="form-floating mb-3">
                        <input 
                            class="form-control" 
                            id="telefono" 
                            name="telefono" 
                            type="tel" 
                            placeholder="56912345678" 
                            required 
                            pattern="^\d{8,15}$" />
                        <label for="telefono">Teléfono de contacto</label>
                        <div class="invalid-feedback">El número es obligatorio y solo debe contener dígitos (sin símbolos ni espacios).</div>
                    </div>
                    <!-- Elección de servicio OK-->
                    <div class="form-group mb-3">
                        <label for="servicio">¿Qué servicio necesita?</label>
                        <select id="servicio" name="servicio" class="form-control" required>
                            <option value="" disabled selected>Seleccione una opción</option>
                            <option value="tasacion">Tasación de bienes raíces</option>
                            <option value="consultoria">Consultoría inmobiliaria</option>
                            <option value="otros">Otro</option>
                        </select>
                        <div class="invalid-feedback">El servicio es obligatorio.</div>
                    </div>
                    <!-- Mensaje OK-->
                    <div class="form-floating mb-3">
                        <textarea name="mensaje" class="form-control" id="mensaje" placeholder="Ingrese su mensaje" style="height: 10rem" required minlength="20" maxlength="1000"></textarea>
                        <label for="mensaje">Mensaje</label>
                        <div class="invalid-feedback" id="mensajeError">El mensaje es obligatorio y debe tener entre 20 y 1000 caracteres.</div>
                    </div>
                    <!-- Respuesta recepción exitosa -->
                    <div class="d-none" id="submitSuccessMessage">
                        <div class="text-center mb-3">
                            <div class="fw-bolder">Su mensaje ha sido recibido con éxito.</div>
                        </div>
                    </div>
                    <!-- Respuesta error envío -->
                    <div class="d-none" id="submitErrorMessage">
                        <div class="text-center text-danger mb-3">Error al enviar el mensaje.</div>
                    </div>
                    <!-- Botón enviar -->
                    <div class="d-grid">
                        <button class="btn btn-primary btn-xl" id="submitButton" type="submit">Enviar</button>
                    </div>

                    <!-- reCAPTCHA -->
                    <!-- <div class="g-recaptcha mb-3 mt-3" data-sitekey="6LdyYy0rAAAAAH9kSCDWmq8Rkp0vZRQX3oFSZcpr"></div> -->
                </form>
                <!-- FIN FORMULARIO DE CONTACTO -->
            </div>
        </div>
    </div>
</section>
<!-- FIN SECCIÓN FORMULARIO DE CONTACTO-->

<!-- INCLUDE FOOTER-->
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script>
    const form = document.getElementById('contactoForm');
    const inputs = form.querySelectorAll('input, textarea, select');

    // Validación al enviar
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }

        form.classList.add('was-validated');
    });

    // Validación campo por campo al salir (blur) o escribir (input)
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            validateField(input);
        });

        input.addEventListener('blur', () => {
            validateField(input);
        });
    });

    function validateField(input) {
        if (input.checkValidity()) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        }
    }
</script>

</body>

</html>