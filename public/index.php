//Vista pública del landing

<?php include '../includes/header.php'; ?>

<!-- Hero -->
<header class="masthead">
  <div class="container px-4 px-lg-5 h-100">
    <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
      <div class="col-lg-8 align-self-end">
        <h1 class="text-white font-weight-bold">Tu Empresa, Profesional y Sofisticada</h1>
        <hr class="divider" />
      </div>
      <div class="col-lg-8 align-self-baseline">
        <p class="text-white-75 mb-5">Ofrecemos soluciones eficientes con una estética elegante y una experiencia limpia.</p>
        <a class="btn btn-primary btn-xl" href="#services">Ver Servicios</a>
      </div>
    </div>
  </div>
</header>

<!-- Servicios -->
<section class="page-section" id="services">
  <div class="container px-4 px-lg-5">
    <h2 class="text-center mt-0">Nuestros Servicios</h2>
    <hr class="divider" />
    <div class="row gx-4 gx-lg-5">
      <div class="col-lg-4 col-md-6 text-center">
        <div class="mt-5">
          <div class="mb-2"><i class="bi-gem fs-1 text-primary"></i></div>
          <h3 class="h4 mb-2">Profesionalismo</h3>
          <p class="text-muted mb-0">Atención al detalle y compromiso con la calidad.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 text-center">
        <div class="mt-5">
          <div class="mb-2"><i class="bi-laptop fs-1 text-primary"></i></div>
          <h3 class="h4 mb-2">Eficiencia</h3>
          <p class="text-muted mb-0">Procesos optimizados que ahorran tiempo y recursos.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 text-center">
        <div class="mt-5">
          <div class="mb-2"><i class="bi-globe fs-1 text-primary"></i></div>
          <h3 class="h4 mb-2">Conectividad</h3>
          <p class="text-muted mb-0">Comunicación efectiva con clientes y usuarios.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Contacto -->
<section class="page-section bg-light" id="contact">
  <div class="container px-4 px-lg-5">
    <h2 class="text-center mt-0">Contáctanos</h2>
    <hr class="divider" />
    <div class="row gx-4 gx-lg-5 justify-content-center mb-5">
      <div class="col-lg-6">
        <form id="contactForm" action="contact.php" method="POST">
          <div class="form-floating mb-3">
            <input class="form-control" name="name" type="text" placeholder="Tu nombre" required />
            <label for="name">Nombre completo</label>
          </div>
          <div class="form-floating mb-3">
            <input class="form-control" name="email" type="email" placeholder="nombre@ejemplo.com" required />
            <label for="email">Correo electrónico</label>
          </div>
          <div class="form-floating mb-3">
            <textarea class="form-control" name="message" placeholder="Escribe tu mensaje aquí..." style="height: 10rem" required></textarea>
            <label for="message">Mensaje</label>
          </div>
          <div class="d-grid">
            <button class="btn btn-primary btn-xl" type="submit">Enviar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include '../includes/footer.php'; ?>
