<!-- Creative - Start Bootstrap Theme
    https://startbootstrap.com/template/creative
    Licencia MIT -->
<!-- Vista pública del landing -->
<?php
require_once __DIR__ . '/../includes/config.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Postular</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>assets/favicon.ico" />
    <!-- Iconos Bootstrap-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Fuentes de Google-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Archivo CSS de estilos-->
    <link href="<?= BASE_URL ?>assets/css/styles.css" rel="stylesheet" />
    <!-- SweetAlert-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<!-- INCLUDE HEADER -->
<?php include_once __DIR__ . '/../includes/header.php'; ?>

<body>
    <section class="page-section bg-tertiary" id="postulacion">
        <!-- Encabezado de sección  -->
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <h2 class="display-4 text-center">Sé parte del equipo VFS</h2>
                <p class="text-muted text-center mb-5 p-postular">Completa el siguiente formulario para postular a ser parte del equipo VFS.</p>
            </div>
            <div class="accordion" id="postulacionAccordion">
                <!-- INICIO FORMULARIO DE POSTULACIÓN -->
                <form id="postulacionForm" action="<?= BASE_URL ?>../includes/Controller/procesar_postulacion.php" method="post" enctype="multipart/form-data">
                    <!-- DATOS PERSONALES (Campo 1-4)(Nombre/Apellido/Nacimiento/Rut)-->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Datos personales
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#postulacionAccordion">
                            <div class="accordion-body">
                                <!-- Campo 1: Nombre-->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="nombre" name="nombre" type="text" placeholder="Ingrese su nombre..." />
                                    <label for="nombre">Nombre</label>
                                </div>
                                <!-- Campo 2: Apellido-->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="apellido" name="apellido" type="text" placeholder="Ingrese su apellido..." />
                                    <label for="apellido">Apellido</label>
                                </div>
                                <!-- Campo 3: Fecha de nacimiento -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" type="date" placeholder="Ingrese su fecha de nacimiento..." />
                                    <label for="fecha_nacimiento">Fecha de nacimiento</label>
                                </div>
                                <!-- Campo 4: Rut -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="rut" name="rut" type="text" placeholder="Ingrese su RUT" />
                                    <label for="rut">RUT</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- DATOS DE CONTACTO (Campo 5-9)(Correo/Teléfono/Dirección/Comuna/Región-->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseOne">
                                Datos de contacto
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#postulacionAccordion">
                            <div class="accordion-body">
                                <!-- Campo 5: Correo-->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" />
                                    <label for="email">Correo</label>
                                </div>
                                <!-- Campo 6: Teléfono-->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="telefono" name="telefono" type="tel" placeholder="(123) 456-7890" />
                                    <label for="telefono">Teléfono de contacto</label>
                                </div>
                                <!-- Campo 7: Dirección -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="direccion" name="direccion" type="text" placeholder="Ingrese su dirección..." />
                                    <label for="direccion">Dirección</label>
                                </div>
                                <!-- Campo 8: Región -->
                                <div class="form-group mb-3">
                                    <label for="region" style="margin-bottom:6px">Región</label>
                                    <select id="region" name="region" class="form-control" required>
                                        <option value="" selected disabled>Seleccione una región</option>
                                        <option value="xv">XV Arica y Parinacota</option>
                                        <option value="i">I Tarapacá</option>
                                        <option value="ii">II Antofagasta</option>
                                        <option value="iii">III Atacama</option>
                                        <option value="iv">IV Coquimbo</option>
                                        <option value="v">V Valparaíso</option>
                                        <option value="rm">RM Metropolitana de Santiago</option>
                                        <option value="vi">VI Libertador General Bernardo O’Higgins</option>
                                        <option value="vii">VII Maule</option>
                                        <option value="xvi">XVI Ñuble</option>
                                        <option value="viii">VIII Biobío</option>
                                        <option value="ix">IX La Araucanía</option>
                                        <option value="xiv">XIV Los Ríos</option>
                                        <option value="x">X Los Lagos</option>
                                        <option value="xi">XI Aysén del General Carlos Ibáñez del Campo</option>
                                        <option value="xii">XII Magallanes y de la Antártica Chilena</option>
                                    </select>
                                    <div class="invalid-feedback">La región es obligatoria.</div>
                                </div>
                                <!-- Campo 9: Comuna -->
                                <div class="form-group mb-3">
                                    <label for="comuna" style="margin-bottom:6px">Comuna</label>
                                    <select id="comuna" name="comuna" class="form-control" required>
                                        <option value="" disabled selected>Seleccione una comuna</option>
                                    </select>
                                    <div class="invalid-feedback">La comuna es obligatoria.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ESTUDIOS Y EXPERIENCIA (Campo 10-15)(Estudios/Institución/AñoTitulación/4 preguntas) -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseOne">
                                Estudios y experiencia
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#postulacionAccordion">
                            <div class="accordion-body">
                                <!-- Campo 10: Estudios -->
                                <div class="form-floating mb-3">
                                    <select class="form-select custom-floating-select" id="estudios" name="estudios" aria-label="Seleccione su carrera">
                                        <option value="" selected disabled>Seleccione su carrera</option>
                                        <option value="Arquitecto">Arquitecto</option>
                                        <option value="CCivil">Constructor Civil</option>
                                        <option value="IngConstruccion">Ingeniero en Construcción</option>
                                        <option value="IngAgronomo">Ingeniero Agrónomo </option>
                                        <option value="IngForestal">Ingeniero Forestal </option>
                                        <option value="IngIndustrial">Ingeniero Industrial</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                    <label for="estudios">Estudios</label>
                                </div>
                                <!-- Campo 11: Institución -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="institucion" name="institucion" type="text" placeholder="Ingrese la institución donde estudió..." />
                                    <label for="institucion">Institución donde estudió</label>
                                </div>
                                <!-- Campo 12: Año de Titulación -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="ano_titulacion" name="ano_titulacion" type="number" placeholder="Ingrese el año de titulación" />
                                    <label for="ano_titulacion">Año de titulación</label>
                                </div>
                                <!-- Campo 13: Formación específica en tasación -->
                                <div class="form-floating mb-3">
                                    <select class="form-select custom-floating-select" id="formacion_tasacion" name="formacion_tasacion" aria-label="¿Tiene formación en tasación?" onchange="mostrarCampoEspecificar()">
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        <option value="Sí">Sí</option>
                                        <option value="No">No</option>
                                    </select>
                                    <label for="formacion_tasacion">¿Cuenta con formación específica en tasación?</label>
                                </div>
                                <!-- Campo adicional: En caso de respode "Sí" al campo 13, se activa este campo -->
                                <div class="form-floating mb-3" id="campo_especificar" name="campo_especificar" style="display: none;">
                                    <input class="form-control" id="detalle_formacion" name="campo_especificar" type="text" placeholder="Especifique su formación en tasación" />
                                    <label for="detalle_formacion">En caso de haber respondido afirmativamente, por favor especifique</label>
                                </div>
                                <!-- Campo 14: Años de experiencia -->
                                <div class="form-floating mb-3">
                                    <select class="form-select custom-floating-select" id="ano_experiencia" name="ano_experiencia" aria-label="Años de experiencia">
                                        <option value="" selected disabled>Indique sus años de experiencia como tasador</option>
                                        <option value="Sin experiencia">Sin experiencia</option>
                                        <option value="Menos de 1 año">Menos de 1 año</option>
                                        <option value="1 a 3 años">1 a 3 años</option>
                                        <option value="3 a 5 años">3 a 5 años</option>
                                        <option value="Más de 5 años">Más de 5 años</option>
                                    </select>
                                    <label for="ano_experiencia">Experiencia</label>
                                </div>
                                <!-- Campo 15: Trabaja con otra empresa -->
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="otra_empresa" name="otra_empresa" type="text" placeholder="Ingrese el nombre de las empresas en que trabaja tasando..." />
                                    <label for="otra_empresa">Empresas para las que tasa actualmente</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- DISPONIBILIDAD Y COBERTURA (Campo 16-18)(3 preguntas)-->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseOne">
                                Disponibilidad y cobertura
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#postulacionAccordion">
                            <div class="accordion-body">
                                <!-- Pregunta 16: Disponibilidad en otras comunas dentro de su región -->
                                <div class="form-floating mb-3">
                                    <select class="form-select custom-floating-select" id="disponibilidad_comunal" name="disponibilidad_comunal" aria-label="Disponibilidad en otras comunas dentro de su región">
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        <option value="Sí">Sí</option>
                                        <option value="No">No</option>
                                    </select>
                                    <label for="disponibilidad_comunal">¿Está disponible para realizar tasaciones en otras comunas dentro de su región?</label>
                                </div>
                                <!-- Pregunta 17: Disponibilidad para trasladarse a otras regiones -->
                                <div class="form-floating mb-3">
                                    <select class="form-select custom-floating-select" id="disponibilidad_regional" name="disponibilidad_regional" aria-label="Disponibilidad para trasladarse a otras regiones">
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        <option value="Sí">Sí</option>
                                        <option value="No">No</option>
                                    </select>
                                    <label for="disponibilidad_regional">¿Está disponible para trasladarse a otras regiones del país para realizar tasaciones?</label>
                                </div>
                                <!-- Pregunta 18: Movilización propia -->
                                <div class="form-floating mb-3">
                                    <select class="form-select custom-floating-select" id="movilizacion" name="movilizacion" aria-label="Movilización propia">
                                        <option value="" selected disabled>Seleccione una opción</option>
                                        <option value="Sí">Sí</option>
                                        <option value="No">No</option>
                                    </select>
                                    <label for="movilizacion">¿Cuenta con movilización propia para desplazarse a realizar tasaciones?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- CURRICULUM VITAE (Campo 19)(CV)-->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFive">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseOne">
                                Currículum Vitae
                            </button>
                        </h2>
                        <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#postulacionAccordion">
                            <div class="accordion-body">
                                <!-- Campo 19: CV -->
                                <div class="mb-3">
                                    <label for="cv" class="form-label">Cargar CV (PDF)</label>
                                    <input class="form-control" id="cv" type="file" name="cv" accept=".pdf" />
                                    <small class="form-text text-muted">Máximo 2 MB.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Botón enviar -->
                    <div class="d-grid">
                        <button class="btn btn-primary btn-xl" id="submitButton" type="submit" disabled>Enviar</button>
                    </div>
                </form>
                <!-- FIN FORMULARIO DE POSTULACIÓN-->
            </div>
        </div>
    </section>
    <!-- FIN SECCIÓN FORMULARIO DE POSTULACIÓN-->
    <?php
    if (isset($_GET['mensaje']) && $_GET['mensaje'] === 'postulado') {
        echo '<div class="alert alert-success text-center" role="alert">
                ¡Su postulación ha sido enviada correctamente!
              </div>';
    }
    ?>

    <!-- INCLUDE FOOTER-->
    <?php include_once __DIR__ . '/../includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
$(document).ready(function () {
    $('#postulacionForm').on('submit', function (e) {
        e.preventDefault();

        const form = this;

        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        const formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: '<?= BASE_URL ?>../includes/Controller/procesar_postulacion.php',
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                try {
                    const jsonResponse = typeof response === 'string' ? JSON.parse(response) : response;

                    if (jsonResponse.success) {
                        Swal.fire({
                            title: '¡Postulación enviada!',
                            text: 'Gracias por postular, revisaremos tu información.',
                            icon: 'success',
                            confirmButtonText: 'Cerrar',
                            customClass: {
                                popup: 'rounded-4 shadow-lg'
                            }
                        });

                        form.reset();
                        form.classList.remove('was-validated');
                        $(form).find('input, textarea, select').removeClass('is-valid is-invalid');
                    } else {
                        Swal.fire({
                            title: 'Error',
                            text: jsonResponse.error || 'Hubo un problema al enviar la postulación.',
                            icon: 'error',
                            confirmButtonText: 'Cerrar'
                        });
                    }
                } catch (err) {
                    Swal.fire({
                        title: 'Error inesperado',
                        text: 'La respuesta del servidor no fue válida.',
                        icon: 'warning',
                        confirmButtonText: 'Cerrar'
                    });
                }
            },
            error: function () {
                Swal.fire({
                    title: 'Error de conexión',
                    text: 'No se pudo conectar con el servidor.',
                    icon: 'error',
                    confirmButtonText: 'Cerrar'
                });
            }
        });
    });
});
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>