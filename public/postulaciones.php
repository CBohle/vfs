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
                <p class="text-muted mb-5">Completa el siguiente formulario</p>
            </div>
        </div>
        <div class="row gx-4 gx-lg-5 justify-content-center mb-5">
            <div class="col-lg-6">
                <!-- INICIO FORMULARIO DE POSTULACIÓN-->
                <form id="postulacionForm" action="/ruta/donde/se/enviará/el/archivo" method="post" enctype="multipart/form-data">
                    <!-- Campo 1: Nombre-->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="name" type="text" placeholder="Ingrese su nombre..." data-sb-validations="required" />
                        <label for="name">Nombre</label>
                        <div class="invalid-feedback" data-sb-feedback="name:required">El nombre es obligatorio.</div>
                    </div>
                    <!-- Campo 2: Apellido-->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="apellido" type="text" placeholder="Ingrese su apellido..." data-sb-validations="required" />
                        <label for="apellido">Apellido</label>
                        <div class="invalid-feedback" data-sb-feedback="apellido:required">El apellido es obligatorio.</div>
                    </div>
                    <!-- Campo 3: Rut -->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="rut" type="text" placeholder="Ingrese su RUT" data-sb-validations="required,rut" />
                        <label for="rut">RUT</label>
                        <div class="invalid-feedback" data-sb-feedback="rut:required">El RUT es obligatorio.</div>
                        <div class="invalid-feedback" data-sb-feedback="rut:rut">El RUT ingresado no es válido.</div>
                    </div>
                    <!-- Campo 4: Mail-->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="email" type="email" placeholder="name@example.com" data-sb-validations="required,email" />
                        <label for="email">Mail</label>
                        <div class="invalid-feedback" data-sb-feedback="email:required">El mail es obligatorio.</div>
                        <div class="invalid-feedback" data-sb-feedback="email:email">El mail ingresado no es válido.</div>
                    </div>
                    <!-- Campo 5: Teléfono-->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="phone" type="tel" placeholder="(123) 456-7890" data-sb-validations="required" />
                        <label for="phone">Número de contacto</label>
                        <div class="invalid-feedback" data-sb-feedback="phone:required">El número de teléfono es obligatorio.</div>
                    </div>
                    <!-- Campo 6: Comuna -->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="comuna" type="text" placeholder="Ingrese la comuna..." data-sb-validations="required" />
                        <label for="comuna">Comuna</label>
                        <div class="invalid-feedback" data-sb-feedback="comuna:required">La comuna es obligatoria.</div>
                    </div>
                    <!-- Campo 7: CV -->
                    <div class="form-floating mb-3">
                        <input class="form-control" id="cv" type="file" name="cv" accept=".doc,.docx,.pdf" data-sb-validations="required" />
                        <label for="cv">Cargar CV (PDF, Word)</label>
                        <div class="invalid-feedback" data-sb-feedback="cv:required">El currículum es obligatorio.</div>
                        <small class="form-text text-muted">Máximo 2 MB.</small>
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
                        <button class="btn btn-primary btn-xl disabled" id="submitButton" type="submit">Enviar</button>
                    </div>
                </form>
                <!-- FIN FORMULARIO DE POSTULACIÓN-->
            </div>
        </div>
    </div>
</section>
<!-- FIN SECCIÓN FORMULARIO DE POSTULACIÓN-->

<!-- INCLUDE FOOTER-->
<?php include_once __DIR__ . '/../includes/footer.php'; ?>

</body>

</html>
