<?php
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/Controller/mensajesController.php';

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Mensajes de Contacto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f5f5;
      font-family: "Segoe UI", sans-serif;
    }

    .container {
      margin-top: 30px;
      max-width: 1100px;
    }

    .card2 {
      background: white;
      border-radius: 16px;
      padding: 16px;
      margin-bottom: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
      position: relative;
    }

    .header2 {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .header2-info {
      display: flex;
      align-items: start;
    }

    .header2 .name {
      font-weight: bold;
      margin: 0;
    }

    .header2 .email {
      font-size: 12px;
      color: #7c7878;
    }

    .project {
      background: #fcd663;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 500;
      display: inline-block;
      margin-left: 15px;
    }

    .tag {
      background: #cfc5fd;
      color: #4c3fb2;
      border-radius: 8px;
      font-size: 12px;
      padding: 2px 6px;
      margin-left: 8px;
    }

    .reply {
      background: #f4f7fb;
      border-radius: 12px;
      padding: 10px;
      display: flex;
      align-items: flex-start;
      gap: 10px;
      margin-top: 12px;
    }

    .reply-header {
      display: flex;
      align-items: center;
    }

    .reply-name {
      font-weight: 500;
    }

    .rol {
      font-size: 12px;
      color: #888;
      margin-left: 8px;
    }

    .reply-text {
      font-size: 14px;
    }

    .resumen-box {
      text-align: right;
    }
  </style>
</head>

<body>
  <div class="container mt-4">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-end flex-wrap mb-3">
      <h3 class="fw-bold text-dark mb-0">Mensajes de Contacto</h3>
      <div class="card shadow-sm border-0 rounded-4 p-2 text-center" style="width: 120px;">
        <button class="btn btn-outline-danger btn-sm w-100">
          <h4 class="mb-1 fw-semibold"><span id="mensajesPorResponder">15/50</span></h4>
          <p class="text-muted mb-0 small">Por responder</p>
        </button>
      </div>
    </div>
    <!-- Card Total 
          <div class="card shadow-sm border-0 rounded-4 p-2 text-center" style="width: 120px;">
            <button class="btn btn-outline-secondary btn-sm w-100">
              <h4 class="mb-1 fw-semibold"><span id="mensajesTotales">50</span></h4>
              <p class="text-muted mb-0 small">Total</p>
            </button>
          </div>
          -->
    <!-- Filtros -->
    <div class="card p-3 mb-4 shadow-sm border-0 rounded-4">
      <form class="row gy-2 gx-3 align-items-center">
        <div class="col-auto">
          <select class="form-select form-select-sm" id="filtro_estado" name="filtro_estado" aria-label="Estado">
            <option value="Todos" selected disabled>Estado</option>
            <option value="pendiente">Pendiente</option>
            <option value="leido">Leído</option>
            <option value="respondido">Respondido</option>
            <option value="eliminado">Eliminado</option>
          </select>
        </div>
        <div class="col-auto">
          <select class="form-select form-select-sm" aria-label="floating select example" id="filtro_servicio" name="filtro_servicio">
            <option value="Todos" selected disabled>Servicio</option>
            <option value="Tasacion de bienes raices">Tasacion de bienes raices</option>
            <option value="Ambos">Ambos servicios</option>
            <option value="Otros">Otros servicios</option>
          </select>
        </div>
        <div class="col-auto">
          <select class="form-select form-select-sm" aria-label="Default select example" id="filtro_orden" name="filtro_orden">
            <option value="Todos" selected disabled>Ordenar</option>
            <option value="DESC">Más Reciente</option>
            <option value="ASC">Más Antiguo</option>
          </select>
        </div>
        <div class="col-auto">
          <button class="btn btn-primary w-100" type="button" onclick="filtrar_mensajes($('#filtro_estado').val(),$('#filtro_servicio').val(),$('#filtro_orden').val());">Filtrar</button>
        </div>
        <div class="col-auto text-align-center">
          <a href="#" class="text-danger small" onclick="resetearFiltros()">Borrar filtros</a>
        </div>
      </form>
    </div>
    <!-- Mensajes -->
    <div class="card shadow-sm border-0 rounded-4 mb-3">
      <div class="card-body" id="resultado_filtro_mensaje">
        <?php if (empty($mensajes)): ?>
          <div class="text-muted">No hay mensajes.</div>
        <?php else: ?>
          <?php include 'mensajesLista.php'; ?>
        <?php endif; ?>
      </div>
    </div>
            <script>
              function filtrar_mensajes(estado, servicio, orden) {
                var parametros = {
                  "buscar": 1,
                  "estado": estado,
                  "servicio": servicio,
                  "orden": orden
                };
                $.ajax({
                  data: parametros,
                  url: 'mensajesFiltro.php',
                  type: 'POST',
                  timeout: 10000,
                  beforeSend: function() {
                    $("#resultado_filtro_mensaje").html("Procesando, espere por favor...");
                  },
                  success: function(data) {
                    $("#resultado_filtro_mensaje").html(data);
                  },
                  error: function(data, error) {
                    console.log('ERROR');
                    document.getElementById("resultado_filtro_mensaje").innerHTML = "Error";
                  }
                });
              }
            </script>
            <script>
              function resetearFiltros() {
                $('#filtro_estado').val('Todos');
                $('#filtro_servicio').val('Todos');
                $('#filtro_orden').val('Todos');
                filtrar_mensajes('Todos', 'Todos', 'Todos');
              }
            </script>
            <script>
              document.addEventListener('DOMContentLoaded', function () {
                window.toggleReplyForm = function (id) {
                  const form = document.getElementById('reply-form-' + id);
                  if (form) {
                    form.classList.toggle('d-none');
                  }
                };
              });
            </script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>