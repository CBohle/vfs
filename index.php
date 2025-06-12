<!-- Creative - Start Bootstrap Theme
    https://startbootstrap.com/template/creative
    Licencia MIT -->

<!-- VISTA DE LA LANDING PAGE -->
<!-- Incluye el formulario de contacto y sus validaciones nativas -->

<?php
require_once __DIR__ . '/includes/config.php';
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
    <!-- Iconos de Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Fuentes de Google-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Hoja de estilos CSS-->
    <link href="<?= BASE_URL ?>assets/css/styles.css" rel="stylesheet" />
     <!--SweetAlert2 CSS-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<!-- INCLUDE HEADER  -->
<?php include_once __DIR__ . '/includes/header.php'; ?>

<!-- INICIO MASTHEAD-->
<header class="masthead">
    <div class="container px-4 px-lg-5 h-100">
        <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-8 align-self-end">
                <h1 class="text-white">Profesionalismo en cada tasación</h1>
                <hr class="divider" />
            </div>
            <div class="col-lg-8 align-self-baseline">
                <p class="text-white-75 mb-5 p-masthead">Somos una empresa de tasación y consultoría inmobiliaria que entrega soluciones integrales y asesoría precisa para apoyar decisiones estratégicas en inversión, financiamiento y gestión de activos.</p>
                <a class="btn btn-primary btn-xl" href="#nosotros">¡Conocer más!</a>
            </div>
        </div>
    </div>
</header>
<!-- FIN MASTHEAD -->

<!-- INICIO SECCIÓN QUIÉNES SOMOS-->
<section class="page-section" id="nosotros">
    <div class="container px-5">
        <div class="row gx-5 align-items-center">
            <div class="col-lg-6 order-lg-2">
                <div class="p-5">
                    <img class="img-fluid rounded-circle" src="<?= BASE_URL ?>assets/images/nosotros/01.webp" alt="Nosotros" />
                </div>
            </div>
            <div class="col-lg-6 order-lg-1">
                <div class="pb-100">
                    <h2 class="display-4">Comprometidos con tu éxito</h2>
                    <br>
                    <p class="p-nosotros">Somos una empresa especializada en tasación y consultoría inmobiliaria, comprometida con la entrega de soluciones integrales que respalden decisiones estratégicas en el ámbito inmobiliario.</p>
                    <p class="p-nosotros">Nuestra misión es proporcionar valor agregado a nuestros clientes mediante información precisa y asesoramiento especializado, facilitando procesos de inversión, financiamiento y gestión de activos inmobiliarios.</p>
                    <a class="btn btn-primary btn-xl" style="margin-top: 8px;" href="#servicios">¡Saber más!</a>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- FIN SECCIÓN QUIÉNES SOMOS-->

<!-- SERVICIOS-->
<div class="page-section bg-dark text-white" id="servicios">
    <div class="container-fluid p-0">
        <!-- Encabezado de sección -->
        <h2 class="text-center mt-0">Tu socio en tasaciones</h2>
        <hr class="divider" />
        <p class="text-muted mb-5 text-center text-white-75">Servicios</p>
        <div class="row g-0">
            <!-- Servicio 1 -->
            <div class="col-lg-6 col-sm-12 pe-4">
                <div class="servicios-box al-servicios" data-bs-toggle="modal" data-bs-target="#modalServicio1">
                    <img class="img-fluid" src="<?= BASE_URL ?>assets/images/servicios/05.webp" alt="Consultoría inmobiliaria" />
                    <div class="servicios-box-caption">
                        <div class="project-category text-white-50">TASACIÓN DE BIENES RAÍCES</div>
                        <br>
                        <div class="project-name">Nuestros servicios de tasación inmobiliaria están diseñados para determinar con precisión el valor de mercado de diversos tipos de propiedades.</div>
                        <br>
                        <p class="text-white-50 small m-0">Ver detalle</p>
                    </div>
                </div>
            </div>
            <!-- Modal con Carrusel para mostrar el detalle del servicio 1 -->
            <div class="modal fade" id="modalServicio1" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content p-0 border-0">
                        <div class="modal-body p-0 position-relative">

                            <!-- Botón de cierre -->
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3 btn-close-white" data-bs-dismiss="modal"></button>

                            <!-- Carrusel -->
                            <div id="carouselServicio1" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <!-- Slide 1 -->
                                    <div class="carousel-item active">
                                        <div class="carousel-slide-bg carrousel-servicio1-1">
                                            <div class="bg-dark bg-opacity-90 text-white p-4 px-5 mx-auto" style="max-width: 700px;">
                                                <h3 class="mb-3">Propiedades</h3>
                                                <p class="lead mb-0">
                                                <ul class="text-start mx-auto ul-servicios">
                                                    <li>Propiedades residenciales: casas, departamentos, parcelas agroresidenciales, sitios urbanos y rurales.</li>
                                                    <li>Inmuebles comerciales: oficinas y locales comerciales.</li>
                                                    <li>Activos industriales: bodegas, industrias y terrenos industriales.</li>
                                                    <li>Bienes especiales: hoteles, clínicas, colegios y otros inmuebles de uso específico.</li>
                                                </ul>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Slide 2 -->
                                    <div class="carousel-item">
                                        <div class="carousel-slide-bg carrousel-servicio1-2">
                                            <div class="bg-dark bg-opacity-90 text-white p-4 px-5 mx-auto" style="max-width: 700px;">
                                                <h3 class="mb-3">Metodologías</h3>

                                                <p class="lead mb-0">
                                                    Aplicamos metodologías reconocidas internacionalmente, como el enfoque de mercado, el método de costos y el enfoque de ingresos, adaptándolas a las particularidades de cada activo y a las necesidades específicas de nuestros clientes.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Slide 3 -->
                                    <div class="carousel-item">
                                        <div class="carousel-slide-bg carrousel-servicio1-3">
                                            <div class="bg-dark bg-opacity-90 text-white p-4 px-5 mx-auto" style="max-width: 700px;">
                                                <h3 class="mb-3">Informes</h3>
                                                <p class="lead mb-0">
                                                    Nuestros informes cumplen con estándares de entidades financieras, organismos reguladores y normativas contables internacionales (IFRS).
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Controles Carrousel Servicio 1 -->
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselServicio1" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselServicio1" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Servicio 2 -->
            <div class="col-lg-6 col-sm-12 pe-4">
                <div class="servicios-box al-servicios" data-bs-toggle="modal" data-bs-target="#modalServicio2">
                    <img class="img-fluid" src="<?= BASE_URL ?>assets/images/servicios/16.webp" alt="Consultoría inmobiliaria" />
                    <div class="servicios-box-caption">
                        <div class="project-category text-white-50">CONSULTORÍA INMOBILIARIA</div>
                        <br>
                        <div class="project-name">Ofrecemos servicios de consultoría inmobiliaria orientados a maximizar el valor y la eficiencia de las inversiones y desarrollos inmobiliarios.</div>
                        <br>
                        <p class="text-white-50 small m-0">Ver detalle</p>
                    </div>
                </div>
            </div>
            <!-- Modal con Carrusel para mostrar el detalle del servicio 2 -->
            <div class="modal fade" id="modalServicio2" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content p-0 border-0">
                        <div class="modal-body p-0 position-relative">

                            <!-- Botón de cierre -->
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3 btn-close-white" data-bs-dismiss="modal"></button>
                            <!-- Carrusel -->
                            <div id="carouselServicio2" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <!-- Slide 1 -->
                                    <div class="carousel-item active">
                                        <div class="carousel-slide-bg carrousel-servicio2-1">
                                            <div class="bg-dark bg-opacity-90 text-white p-4 px-5 mx-auto" style="max-width: 700px;">
                                                <h3 class="mb-3">Servicios</h3>
                                                <p class="lead mb-0">
                                                <ul class="text-start mx-auto ul-servicios">
                                                    <li>Estudios de mercado</li>
                                                    <li>Análisis de viabilidad</li>
                                                    <li>Asesoramiento estratégico</li>
                                                    <li>Gestión de activos</li>
                                                </ul>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Slide 2 -->
                                    <div class="carousel-item">
                                        <div class="carousel-slide-bg carrousel-servicio2-2">
                                            <div class="bg-dark bg-opacity-90 text-white p-4 px-5 mx-auto" style="max-width: 700px;">
                                                <h3 class="mb-3">Estudios de mercado</h3>
                                                <p class="lead mb-0">
                                                    Análisis de oferta y demanda, identificación de tendencias y evaluación de oportunidades de inversión.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Slide 3 -->
                                    <div class="carousel-item">
                                        <div class="carousel-slide-bg carrousel-servicio2-3">
                                            <div class="bg-dark bg-opacity-90 text-white p-4 px-5 mx-auto" style="max-width: 700px;">
                                                <h3 class="mb-3">Análisis de viabilidad</h3>
                                                <p class="lead mb-0">
                                                    Evaluación técnica, legal y financiera de proyectos inmobiliarios para determinar su rentabilidad y sostenibilidad.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Slide 4 -->
                                    <div class="carousel-item">
                                        <div class="carousel-slide-bg carrousel-servicio2-4">
                                            <div class="bg-dark bg-opacity-90 text-white p-4 px-5 mx-auto" style="max-width: 700px;">
                                                <h3 class="mb-3">Asesoramiento estratégico</h3>
                                                <p class="lead mb-0">
                                                    Diseño de estrategias de desarrollo, comercialización y posicionamiento de activos inmobiliarios.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Slide 5 -->
                                    <div class="carousel-item">
                                        <div class="carousel-slide-bg carrousel-servicio2-5">
                                            <div class="bg-dark bg-opacity-90 text-white p-4 px-5 mx-auto" style="max-width: 700px;">
                                                <h3 class="mb-3">Gestión de activos</h3>
                                                <p class="lead mb-0">
                                                    Optimización del rendimiento de carteras inmobiliarias mediante la implementación de prácticas de gestión eficientes y sostenibles.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Slide 6 -->
                                    <div class="carousel-item">
                                        <div class="carousel-slide-bg carrousel-servicio2-6">
                                            <div class="bg-dark bg-opacity-90 text-white p-4 px-5 mx-auto" style="max-width: 700px;">
                                                <h3 class="mb-3">Enfoque</h3>
                                                <p class="lead mb-0">
                                                    Nuestro enfoque se basa en la integración de datos cuantitativos y cualitativos, el uso de herramientas tecnológicas avanzadas y una comprensión profunda del entorno regulatorio y económico, lo que nos permite ofrecer soluciones personalizadas y efectivas a nuestros clientes. </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Controles Carrousel Servicio 2 -->
                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselServicio2" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carouselServicio2" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- FIN SECCIÓN SERVICIOS-->

<!-- LLAMADA A LA ACCIÓN-->
<section class="page-section accion" id="llamadaAccion">
    <div class="container px-4 px-lg-5 text-center text-white">
        <h2 class="mb-4">¡Solicita tu tasación!</h2>
        <a class="btn btn-primary btn-xl" href="#contacto">Contáctanos</a>
    </div>
</section>
<!-- FIN LLAMADA A LA ACCIÓN -->

<!-- INICIO SECCIÓN FAQ -->
<section class="page-section" id="faq">
    <div class="container-fluid px-4 px-lg-5">
        <!-- Encabezado de sección -->
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 col-xl-6 text-center">
                <h2 class="text-center mt-0">Preguntas Frecuentes</h2>
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
                        <p class="p-faq-accordion">Ofrecemos servicios de tasación para una amplia gama de activos inmobiliarios, incluyendo:</p>
                        <ul class="ul-faq">
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
                        <p class="p-faq-accordion">Para iniciar el proceso de tasación, solicitamos los siguientes datos:</p>
                        <ul class="ul-faq">
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
                        <p class="p-faq-accordion">El plazo de entrega del informe varía según el tipo de propiedad:</p>
                        <ul class="ul-faq">
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
                        <p class="p-faq-accordion">Utilizamos metodologías reconocidas internacionalmente, tales como:</p>
                        <ul class="ul-faq">
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
                        <p class="p-faq-accordion">En caso de disconformidad, el cliente puede solicitar una reconsideración. Nuestro equipo revisará el informe y, si corresponde, se realizarán los ajustes pertinentes.</p>
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
                        <p class="p-faq-accordion">Nuestros servicios de consultoría abordan:</p>
                        <ul class="ul-faq">
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
                        <p class="p-faq-accordion">El pago puede realizarse mediante transferencia bancaria o mercado pago. Los detalles del proceso de pago se incluirán en la cotización enviada al cliente.</p>
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
                        <p class="p-faq-accordion">Nuestros informes cumplen con las normativas IFRS y estándares nacionales, garantizando precisión, confiabilidad y validez ante instituciones financieras y reguladoras.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- FIN SECCIÓN FAQ -->

<!-- INICIO SECCIÓN FORMULARIO DE CONTACTO -->
<section class="page-section bg-tertiary" id="contacto">
    <div class="container px-5 ">
        <!-- Encabezado de sección -->
        <div class="text-center">
            <h2 class="display-4 text-center">Atención personalizada</h2>
            <hr class="divider" />
            <p class="text-center">Para solicitudes, cotizaciones o información adicional, por favor complete el siguiente formulario.</p>
        </div>
        <div class="row gx-5 mt-5">
            <!-- Imagen -->
            <div class="col-lg-6 order-lg-1 mb-5">
                <div class="">
                    <div class="gx-4 gx-lg-5 justify-content-center">
                        <img class="img-fluid rounded" src="<?= BASE_URL ?>assets/images/contacto/01.webp" alt="Nosotros" />
                    </div>
                </div>
            </div>
            <!-- Fin Imagen -->
            <!-- Formulario -->
            <div class="col-lg-6 order-lg-2 mb-5">
                <div class="">
                    <div class="row gx-4 gx-lg-5 justify-content-center ">
                        <div class="">
                            <!-- Mensaje de recepción exitosa o error -->
                           <div id="mensajeAlerta" class="alert d-none alert-dismissible fade show" role="alert">
                                <span id="mensajeTexto"></span>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                            </div>
                            <!-- INICIO FORMULARIO DE CONTACTO CON VALIDACIONES POR CAMPO -->
                            <form id="contactoForm" class="novalidate" method="post">
                                <!-- Campo 1: Nombre-->
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
                                <!-- Opción 2 apellido-->
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
                                <!-- Campo 3: Mail-->
                                <div class="form-floating mb-3">
                                    <input name="email" class="form-control" id="email" type="email" placeholder="name@example.com" required />
                                    <label for="email">Correo</label>
                                    <div class="invalid-feedback">Ingrese un correo válido.</div>
                                </div>
                                <!-- Campo 4: Teléfono-->
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
                                <!-- Elección de servicio-->
                                <div class="form-group mb-3">
                                    <label for="servicio" style="margin-bottom:6px">¿Qué servicio necesita?</label>
                                    <select id="servicio" name="servicio" class="form-control" required>
                                        <option value="" disabled selected>Seleccione una opción</option>
                                        <option value="tasacion">Tasación de bienes raíces</option>
                                        <option value="consultoria">Consultoría inmobiliaria</option>
                                        <option value="otros">Otro</option>
                                    </select>
                                    <div class="invalid-feedback">El servicio es obligatorio.</div>
                                </div>
                                <!-- Mensaje-->
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
                                    <button class="btn btn-primary btn-xl" id="submitButtonContacto" type="submit" disabled>Enviar</button>
                                </div>
                                <!-- reCAPTCHA -->
                                <div class="g-recaptcha mb-3 mt-3" data-sitekey="6LdyYy0rAAAAAH9kSCDWmq8Rkp0vZRQX3oFSZcpr"></div>
                            </form>
                            <!-- FIN FORMULARIO DE CONTACTO -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- FIN SECCIÓN FORMULARIO DE CONTACTO -->

<!-- INCLUDE FOOTER-->
<?php include_once __DIR__ . '/includes/footer.php'; ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const form = $('#contactoForm');

    // Validación por campo
    form.find('input, textarea, select').on('input blur', function() {
        if (this.checkValidity()) {
            $(this).removeClass('is-invalid').addClass('is-valid');
        } else {
            $(this).removeClass('is-valid').addClass('is-invalid');
        }
    });

    // Envío por AJAX
    form.on('submit', function(e) {
        e.preventDefault(); // Evita el comportamiento normal

        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            return;
        }

        const formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: '../includes/Controller/procesar_mensaje.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                try {
                    const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;
                    if (jsonResponse.success) {
                        mostrarAlerta("¡Tu mensaje ha sido enviado con éxito!", "success");
                        form[0].reset();
                        form.removeClass('was-validated');
                        form.find('input, textarea, select').removeClass('is-valid is-invalid');
                    } else {
                        mostrarAlerta("Error: " + (jsonResponse.error || "Hubo un problema al enviar el mensaje."), "danger");
                    }
                } catch (err) {
                    mostrarAlerta("Hubo un error inesperado en la respuesta del servidor.", "warning");
                }
            },
            error: function() {
                mostrarAlerta("Error al conectar con el servidor.", "danger");
            }
        });
    });

    // Mostrar alertas bonitas de Bootstrap
   function mostrarAlerta(mensaje, tipo) {
    Swal.fire({
        title: tipo === 'success' ? '¡Éxito!' : 'Atención',
        text: mensaje,
        icon: tipo, // 'success', 'error', 'warning', etc.
        confirmButtonText: 'Cerrar',
        customClass: {
            popup: 'rounded-4 shadow-lg'
        }
    });
    }

});
</script>



</body>

</html>