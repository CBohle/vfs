<!-- Creative - Start Bootstrap Theme
    https://startbootstrap.com/template/creative
    Licencia MIT -->
<!-- Vista pública del landing -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Postula</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="/assets/favicon.ico" />
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic" rel="stylesheet" type="text/css" />
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="/assets/css/styles.css" rel="stylesheet" />
</head>

<!-- INCLUDE HEADER -->
<?php include_once __DIR__ . '/../includes/header.php'; ?>

<!-- INICIO SECCIÓN FORMULARIO DE POSTULACIÓN-->
<section class="page-section bg-tertiary" id="postulacion">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-lg-8 col-xl-6 text-center">
                <h2 class="mt-0">Sé parte del equipo VFS</h2>
                <hr class="divider" />
                <p class="text-muted mb-5">Completa el siguiente formulario para postular a ser parte del equipo VFS.</p>
            </div>
        </div>
        <div class="accordion" id="postulacionAccordion">

            <!-- INICIO FORMULARIO DE POSTULACIÓN-->
            <form id="postulacionForm" action="/ruta/donde/se/enviará/el/archivo" method="post" enctype="multipart/form-data">

                <!-- DATOS PERSONALES (Nombre/Apellido/Nacimiento/Rut)-->
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
                                <input class="form-control" id="nombre" name="nombre" type="text" placeholder="Ingrese su nombre..." data-sb-validations="required" />
                                <label for="nombre">Nombre</label>
                                <div class="invalid-feedback" data-sb-feedback="nombre:required">El nombre es obligatorio.</div>
                            </div>
                            <!-- Campo 2: Apellido-->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="apellido" name="apellido" type="text" placeholder="Ingrese su apellido..." data-sb-validations="required" />
                                <label for="apellido">Apellido</label>
                                <div class="invalid-feedback" data-sb-feedback="apellido:required">El apellido es obligatorio.</div>
                            </div>
                            <!-- Campo 3: Fecha de nacimiento -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" type="date" placeholder="Ingrese su fecha de nacimiento..." data-sb-validations="required" />
                                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                                <div class="invalid-feedback" data-sb-feedback="fecha_nacimiento:required">La fecha de nacimiento es obligatoria.</div>
                            </div>
                            <!-- Campo 4: Rut -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="rut" name="rut" type="text" placeholder="Ingrese su RUT" data-sb-validations="required,rut" />
                                <label for="rut">RUT</label>
                                <div class="invalid-feedback" data-sb-feedback="rut:required">El RUT es obligatorio.</div>
                                <div class="invalid-feedback" data-sb-feedback="rut:rut">El RUT ingresado no es válido.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DATOS DE CONTACTO (Correo/Teléfono/Dirección/Comuna/Región-->
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
                                <input class="form-control" id="email" name="email" type="email" placeholder="name@example.com" data-sb-validations="required,email" />
                                <label for="email">Correo</label>
                                <div class="invalid-feedback" data-sb-feedback="email:required">El mail es obligatorio.</div>
                                <div class="invalid-feedback" data-sb-feedback="email:email">El mail ingresado no es válido.</div>
                            </div>
                            <!-- Campo 6: Teléfono-->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="telefono" name="telefono" type="tel" placeholder="(123) 456-7890" data-sb-validations="required" />
                                <label for="telefono">Teléfono de contacto</label>
                                <div class="invalid-feedback" data-sb-feedback="telefono:required">El número de teléfono es obligatorio.</div>
                            </div>
                            <!-- Campo 7: Dirección -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="direccion" name="direccion" type="text" placeholder="Ingrese su dirección..." data-sb-validations="required" />
                                <label for="direccion">Dirección</label>
                                <div class="invalid-feedback" data-sb-feedback="direccion:required">La dirección es obligatoria.</div>
                            </div>
                            <!-- Campo 8: Comuna -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="comuna" name="comuna" type="text" placeholder="Ingrese la comuna..." data-sb-validations="required" />
                                <label for="comuna">Comuna</label>
                                <div class="invalid-feedback" data-sb-feedback="comuna:required">La comuna es obligatoria.</div>
                            </div>
                            <!-- Campo 9: Región -->
                            <div class="form-floating mb-3">
                                <select class="form-select custom-floating-select" id="region" name="region" aria-label="Seleccione una región" data-sb-validations="required">
                                    <option value="" selected disabled>Seleccione una región</option>
                                    <option value="Arica y Parinacota">Arica y Parinacota</option>
                                    <option value="Tarapacá">Tarapacá</option>
                                    <option value="Antofagasta">Antofagasta</option>
                                    <option value="Atacama">Atacama</option>
                                    <option value="Coquimbo">Coquimbo</option>
                                    <option value="Valparaíso">Valparaíso</option>
                                    <option value="Metropolitana de Santiago">Metropolitana de Santiago</option>
                                    <option value="Libertador General Bernardo O’Higgins">Libertador General Bernardo O’Higgins</option>
                                    <option value="Maule">Maule</option>
                                    <option value="Ñuble">Ñuble</option>
                                    <option value="Biobío">Biobío</option>
                                    <option value="La Araucanía">La Araucanía</option>
                                    <option value="Los Ríos">Los Ríos</option>
                                    <option value="Los Lagos">Los Lagos</option>
                                    <option value="Aysén del General Carlos Ibáñez del Campo">Aysén del General Carlos Ibáñez del Campo</option>
                                    <option value="Magallanes y de la Antártica Chilena">Magallanes y de la Antártica Chilena</option>
                                </select>
                                <label for="region">Región</label>
                                <div class="invalid-feedback" data-sb-feedback="region:required">La región es obligatoria.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ESTUDIOS Y EXPERIENCIA (Estudios/Institución/AñoTitulación/4 preguntas) -->
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
                                <select class="form-select custom-floating-select" id="estudios" name="estudios" aria-label="Seleccione su carrera" data-sb-validations="required">
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
                                <div class="invalid-feedback" data-sb-feedback="estudios:required">Su carrera es obligatoria.</div>
                            </div>
                            <!-- Campo 11: Institución -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="institucion" name="institucion" type="text" placeholder="Ingrese la institución donde estudió..." data-sb-validations="required" />
                                <label for="institucion">Institución donde estudió</label>
                                <div class="invalid-feedback" data-sb-feedback="institucion:required">La institución es obligatoria.</div>
                            </div>
                            <!-- Campo 12: Año de Titulación -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="ano_titulacion" name="ano_titulacion" type="number" placeholder="Ingrese el año de titulación" data-sb-validations="required,min:1950,max:2025" min="1950" max="2025" />
                                <label for="ano_titulacion">Año de titulación</label>
                                <div class="invalid-feedback" data-sb-feedback="ano_titulacion:required">El año de titulación es obligatorio.</div>
                                <div class="invalid-feedback" data-sb-feedback="ano_titulacion:min">El año ingresado es muy antiguo.</div>
                                <div class="invalid-feedback" data-sb-feedback="ano_titulacion:max">El año ingresado no puede ser mayor a 2025.</div>
                            </div>
                            <!-- Campo 13: Formación específica en tasación -->
                            <div class="form-floating mb-3">
                                <select class="form-select custom-floating-select" id="formacion_tasacion" name="formacion_tasacion" aria-label="¿Tiene formación en tasación?" data-sb-validations="required" onchange="mostrarCampoEspecificar()">
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="formacion_tasacion">¿Cuenta con formación específica en tasación?</label>
                                <div class="invalid-feedback" data-sb-feedback="formacion_tasacion:required">Este campo es obligatorio.</div>
                            </div>
                            <!-- Campo adicional: En caso de respode "Sí" al campo 13, se activa este campo -->
                            <div class="form-floating mb-3" id="campo_especificar" name="campo_especificar" style="display: none;">
                                <input class="form-control" id="detalle_formacion" name="campo_especificar" type="text" placeholder="Especifique su formación en tasación" data-sb-validations="required" />
                                <label for="detalle_formacion">En caso de haber respondido afirmativamente, por favor especifique</label>
                                <div class="invalid-feedback" data-sb-feedback="detalle_formacion:required">Este campo es obligatorio si respondió que sí.</div>
                            </div>
                            <!-- Campo 14: Años de experiencia -->
                            <div class="form-floating mb-3">
                                <select class="form-select custom-floating-select" id="ano_experiencia" name="ano_experiencia" aria-label="Años de experiencia" data-sb-validations="required">
                                    <option value="" selected disabled>Indique sus años de experiencia como tasador</option>
                                    <option value="Sin experiencia">Sin experiencia</option>
                                    <option value="Menos de 1 año">Menos de 1 año</option>
                                    <option value="1 a 3 años">1 a 3 años</option>
                                    <option value="3 a 5 años">3 a 5 años</option>
                                    <option value="Más de 5 años">Más de 5 años</option>
                                </select>
                                <label for="ano_experiencia">Experiencia</label>
                                <div class="invalid-feedback" data-sb-feedback="ano_experiencia:required">Este campo es obligatorio.</div>
                            </div>
                            <!-- Campo 15: Trabaja con otra empresa -->
                            <div class="form-floating mb-3">
                                <input class="form-control" id="otra_empresa" name="otra_empresa" type="text" placeholder="Ingrese el nombre de las empresas en que trabaja tasando..." data-sb-validations="required" />
                                <label for="otra_empresa">Empresas para las que tasa actualmente</label>
                                <div class="invalid-feedback" data-sb-feedback="otra_empresa:required">Este campo es obligatorio.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DISPONIBILIDAD Y COBERTURA (3 preguntas)-->
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
                                <select class="form-select custom-floating-select" id="disponibilidad_comunal" name="disponibilidad_comunal" aria-label="Disponibilidad en otras comunas dentro de su región" data-sb-validations="required">
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="disponibilidad_comunal">¿Está disponible para realizar tasaciones en otras comunas dentro de su región?</label>
                                <div class="invalid-feedback" data-sb-feedback="disponibilidad_comunal:required">Este campo es obligatorio.</div>
                            </div>
                            <!-- Pregunta 17: Disponibilidad para trasladarse a otras regiones -->
                            <div class="form-floating mb-3">
                                <select class="form-select custom-floating-select" id="disponibilidad_regional" name="disponibilidad_regional" aria-label="Disponibilidad para trasladarse a otras regiones" data-sb-validations="required">
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="disponibilidad_regional">¿Está disponible para trasladarse a otras regiones del país para realizar tasaciones?</label>
                                <div class="invalid-feedback" data-sb-feedback="disponibilidad_regional:required">Este campo es obligatorio.</div>
                            </div>
                            <!-- Pregunta 18: Movilización propia -->
                            <div class="form-floating mb-3">
                                <select class="form-select custom-floating-select" id="movilizacion" name="movilizacion" aria-label="Movilización propia" data-sb-validations="required">
                                    <option value="" selected disabled>Seleccione una opción</option>
                                    <option value="Sí">Sí</option>
                                    <option value="No">No</option>
                                </select>
                                <label for="movilizacion">¿Cuenta con movilización propia para desplazarse a realizar tasaciones?</label>
                                <div class="invalid-feedback" data-sb-feedback="movilizacion:required">Este campo es obligatorio.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- CURRICULUM VITAE -->
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
                                <label for="cv" class="form-label">Cargar CV (PDF, Word)</label>
                                <input class="form-control" id="cv" type="file" name="cv" accept=".doc,.docx,.pdf" data-sb-validations="required" />
                                <div class="invalid-feedback" data-sb-feedback="cv:required">El currículum es obligatorio.</div>
                                <small class="form-text text-muted">Máximo 2 MB.</small>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Respuesta recepción exitosa del formulario-->
                <div class="d-none" id="submitSuccessMessage">
                    <div class="text-center mb-3">
                        <div class="fw-bolder">Su mensaje ha sido recibido con éxito.</div>
                    </div>
                </div>
                <!-- Respuesta error en el envío del formulario -->
                <div class="d-none" id="submitErrorMessage">
                    <div class="text-center text-danger mb-3">Error al enviar el mensaje.</div>
                </div>
                <!-- Botón enviar-->
                <div class="d-grid">
                    <br>
                    <button class="btn btn-primary btn-xl disabled" id="submitButton" type="submit">Enviar</button>
                </div>
            </form>
            <!-- FIN FORMULARIO DE POSTULACIÓN-->
        </div>
    </div>
    </div>
    </div>
</section>
<!-- FIN SECCIÓN FORMULARIO DE POSTULACIÓN-->

<!-- INCLUDE FOOTER-->
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
</body>

</html>