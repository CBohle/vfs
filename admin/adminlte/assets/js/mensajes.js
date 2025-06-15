(function () {
  let tabla = null;
  window.ordenManualActivado = window.ordenManualActivado || false;
  function inicializarTablaMensajes() {
    if (tabla) return; // Ya inicializada

    tabla = $("#tablaMensajes").DataTable({
      serverSide: true,
      processing: true,
      rowId: 'DT_RowId',
      ajax: {
        url: "mensajesAjax.php",
        type: "POST",
        data: function (d) {
          if (typeof window.estadoPersonalizado !== "undefined") {
            d.estadoMultiple = window.estadoPersonalizado;
          } else {
            d.estado = $("#filtro_estado").val();
          }

          d.servicio = $("#filtro_servicio").val();
          // Aplica orden manual (por filtro) solo si NO hay orden del usuario
        if (window.ordenManualActivado && (!d.order || d.order.length === 0)) {
          const ordenSeleccionado = $("#filtro_orden").val();
          if (ordenSeleccionado === "ASC" || ordenSeleccionado === "DESC") {
            d.order = [{ column: 13, dir: ordenSeleccionado.toLowerCase() }];
          }
        }
          d.importante = $("#filtro_importante").val();
          d.search.value = $("#filtro_busqueda").val();
        },
      },
      columns: [
        {
          data: "importante",
          orderable: true,
          searchable: false,
          className: "text-center",
          render: function (data, type, row) {
            const icon =
              data == 1 ? "bi-star-fill text-warning" : "bi-star text-muted";
            return `<i class="bi ${icon} marcarImportante" data-id="${row.id}" data-valor="${data}" style="cursor:pointer;"></i>`;
          },
        },
        {
          data: "importante_texto",
          visible: false,
          render: function (data) {
            return data;
          }
        },
        { data: "id" },
        { data: "servicio" },
        { data: "nombre" },
        { data: "email" },
        {
          data: "telefono",
          visible: false,
          render: function (data) {
            return data;
          }
        },
        {
          data: "mensaje",
          className: "col-mensaje",
          render: function (data, type) {
            return type === "display"
              ? `<div class="truncado-3-lineas">${data}</div>`
              : data;
          },
        },
        {
          data: "respuesta",
          visible: false,
          render: function (data) {
            return data;
          }
        },
        {
          data: "fecha_respuesta",
          visible: false,
          render: function (data) {
            return data;
          }
        },
        {
          data: "admin",
          visible: false,
          render: function (data) {
            return data;
          }
        },
        {
          data: "rol",
          visible: false,
          render: function (data) {
            return data;
          }
        },
        {
          data: "estado",
          className: "col-estado",
          render: function (data) {
            let clase = "badge ";
            switch (data.toLowerCase()) {
              case "respondido":
                clase += "bg-success";
                break;
              case "leido":
                clase += "bg-primary";
                break;
              case "pendiente":
                clase += "bg-warning text-dark";
                break;
              case "eliminado":
                clase += "bg-secondary";
                break;
              default:
                clase += "bg-light text-dark";
            }
            return `<span class="${clase}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
          },
        },
        { data: "fecha" },
        {
          data: null,
          orderable: false,
          searchable: false,
          className: "text-center",
          render: function (data, type, row) {
            const verBtn = `
              <button class="btn btn-sm btn-primary me-1" title="Ver" onclick="verMensaje(${row.id})">
                  <i class="bi bi-eye"></i>
              </button>
            `;

            // Verificar si el permiso 'eliminar' está presente
            const puedeEliminar = PERMISOS['mensajes']?.includes('eliminar');

            if (row.estado.toLowerCase() === "eliminado") {
              if (puedeEliminar) {
                return verBtn + `
                  <button class="btn btn-sm btn-success" title="Recuperar" onclick="recuperarMensaje(${row.id})">
                      <i class="bi bi-arrow-counterclockwise"></i>
                  </button>`;
              }
              return verBtn;
            } else {
              if (puedeEliminar) {
                return verBtn + `
                  <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarMensaje(${row.id})">
                      <i class="bi bi-trash"></i>
                  </button>`;
              }
              return verBtn;
            }
          },
        }
      ],
      order: [],
      language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json",
      },
      dom: '<"d-flex justify-content-end mb-2"l>Bfrtip',
      lengthMenu: [10, 30, 50, 100],
      buttons: [
        {
          extend: "copy",
          text: '<i class="bi bi-clipboard me-1"></i> Copiar',
          className: "btn btn-primary btn-sm me-2",
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],  // Seleccion de columnas a copiar
            format: {
              body: function (data, row, column, node) {
                return typeof data === 'string' ? data.replace(/<.*?>/g, '') : data;
              }
            }
          }
        },
        {
          extend: "excel",
          text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
          className: "btn btn-success btn-sm",
          exportOptions: {
            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13], // Seleccion de columnas a copiar
            format: {
              body: function (data, row, column, node) {
                return typeof data === 'string' ? data.replace(/<.*?>/g, '') : data;
              }
            }
          }
        },
      ],
      initComplete: function () {
        $("#loaderTabla").hide();
        $("#tablaMensajes").css("visibility", "visible");
        $("#exportButtons .dt-buttons").remove();
        tabla.columns.adjust().draw();
        tabla.buttons().container().appendTo("#exportButtons");
        $('#tablaMensajes thead').on('click', 'th', function () {
          $("#filtro_orden").val(""); // limpia visual
          window.ordenManualActivado = false; // desactiva orden manual
        });
      },
    });
  }

  $(document).on("keyup", "#filtro_busqueda", function () {
    if (tabla) tabla.draw();
  });

  window.filtrar = function () {
    delete window.estadoPersonalizado;
    const ordenSeleccionado = $("#filtro_orden").val();
    window.ordenManualActivado = (ordenSeleccionado === "ASC" || ordenSeleccionado === "DESC");
    if (!tabla) inicializarTablaMensajes();
    tabla.ajax.reload();
  };

  window.filtrarPendienteYLeido = function () {
    window.estadoPersonalizado = ["pendiente", "leido"];
    $("#filtro_estado").val("");
    $("#filtro_servicio").val("");
    $("#filtro_orden").val("DESC");
    $("#filtro_importante").val("");
    if (!tabla) inicializarTablaMensajes();
    tabla.ajax.reload();
  };

  window.resetearFiltros = function () {
    window.ordenManualActivado = false;
    $("#filtro_estado").val("");
    $("#filtro_servicio").val("");
    $("#filtro_orden").val("DESC");
    $("#filtro_importante").val("");
    $("#filtro_busqueda").val("");
    delete window.estadoPersonalizado;
    if (!tabla) inicializarTablaMensajes();
    tabla.ajax.reload();
  };

  window.verMensaje = function (id) {
    $("#contenidoModalMensaje").empty().html(
        '<p class="text-center text-muted">Cargando...</p>'
    );
    $("#modalVerMensaje").modal("show");
    $("#botonImportanteWrapper").empty();

    console.log("ID del mensaje al abrir:", id);

    // Marcar como leído
    $.post("mensajesAjax.php", { accion: "marcarLeido", id: id }, function (response) {
        if (response.success) {
            const $badge = $(`#row_${id} td.col-estado span`);
            if ($badge.length > 0 && $badge.text().toLowerCase() === "pendiente") {
                $badge.fadeOut(200, function () {
                    $(this)
                        .removeClass("bg-warning text-dark")
                        .addClass("bg-primary")
                        .text("Leído")
                        .fadeIn(200);
                });
            }
        }
    }, "json");

    // Cargar contenido HTML actualizado del mensaje
    $.get("mensajeModal.php", { id: id }, function (respuesta) {
      $("#contenidoModalMensaje").html(respuesta);

      // Extraer y volver a mostrar el botón de importante
      const botonHTML = $("#contenidoModalMensaje")
        .find("#botonImportanteHTML")
        .html();
      $("#botonImportanteWrapper").html(botonHTML);
    }).fail(function () {
      $("#contenidoModalMensaje").html(
        '<p class="text-danger">Error al cargar el mensaje.</p>'
      );
    });
  };

  window.eliminarMensaje = function (id) {
    if (confirm("¿Estás seguro de que deseas eliminar este mensaje?")) {
      $.post(
        "mensajesAjax.php",
        { accion: "eliminar", id: id },
        function (response) {
          if (response.success) {
            alert("Mensaje eliminado correctamente.");
            tabla.ajax.reload(null, false);
            actualizarContadorMensajesPendientes();
          } else {
            alert("Hubo un error al intentar eliminar el mensaje.");
          }
        },
        "json"
      );
    }
  };

  window.recuperarMensaje = function (id) {
    if (confirm("¿Deseas recuperar este mensaje?")) {
      $.post(
        "mensajesAjax.php",
        { accion: "recuperar", id: id },
        function (response) {
          if (response.success) {
            tabla.ajax.reload(null, false);
            actualizarContadorMensajesPendientes();
            alert("Mensaje recuperado con éxito.");
          } else {
            alert("No se pudo recuperar el mensaje.");
          }
        },
        "json"
      );
    }
  };

  $(document).on("click", ".marcarImportante", function () {
    const icono = $(this);
    const id = $(this).data("id");
    const valorActual = $(this).data("valor");
    const nuevoValor = valorActual == 1 ? 0 : 1;

    $.post(
      "mensajesAjax.php",
      {
        accion: "importante",
        mensaje_id: id,
        importante: nuevoValor,
      },
      function (response) {
        if (response.success) {
          const nuevaClase = nuevoValor == 1 ? "bi-star-fill text-warning" : "bi-star text-muted";
          icono
            .removeClass("bi-star bi-star-fill text-muted text-warning")
            .addClass(nuevaClase)
            .data("valor", nuevoValor); // actualiza el valor del data-valor
        } else {
          alert("No se pudo actualizar el estado de importancia.");
        }
      },
      "json"
    );
  });
  
  window.toggleImportanteMensaje = function (id, estadoActual) {
      const nuevoValor = estadoActual === 1 ? 0 : 1;

      $.post('mensajesAjax.php', {
          accion: 'importante',
          mensaje_id: id,
          importante: nuevoValor
      }, function(response) {
          if (response.success) {
              const boton = $('#btnImportante');
              const icono = $('#iconoImportante');
              const texto = $('#textoImportante');
              
              icono.fadeOut(150, function () {
                icono.addClass("balanceando");
                setTimeout(() => {
                icono.removeClass("balanceando");
                }, 500);
                if (nuevoValor === 1) {
                    boton.removeClass('btn-warning').addClass('btn-outline-warning');
                    icono.removeClass('bi-star').addClass('bi-star-fill');
                    texto.text('Marcar como no importante');
                } else {
                    boton.removeClass('btn-outline-warning').addClass('btn-warning');
                    icono.removeClass('bi-star-fill').addClass('bi-star');
                    texto.text('Marcar como importante');
                }
                boton.attr('onclick', `toggleImportanteMensaje(${id}, ${nuevoValor})`);
                icono.fadeIn(150);
            });

              if (nuevoValor === 1) {
                  boton.removeClass('btn-warning').addClass('btn-outline-warning');
                  icono.removeClass('bi-star').addClass('bi-star-fill');
                  texto.text('Marcar como no importante');
              } else {
                  boton.removeClass('btn-outline-warning').addClass('btn-warning');
                  icono.removeClass('bi-star-fill').addClass('bi-star');
                  texto.text('Marcar como importante');
              }

              const iconoTabla = $(`#row_${id} .marcarImportante`);
              if (iconoTabla.length > 0) {
                  iconoTabla
                      .removeClass('bi-star bi-star-fill text-warning text-muted')
                      .addClass(nuevoValor === 1 ? 'bi-star-fill text-warning' : 'bi-star text-muted')
                      .attr('data-valor', nuevoValor)
                      .addClass('balanceando');

                  setTimeout(() => {
                      iconoTabla.removeClass('balanceando');
                  }, 500);
              }
          } else {
              alert('No se pudo actualizar el estado de importancia.');
          }
      }, 'json');
  }
  // Envío del formulario de respuesta
  $(document).on("submit", "#formRespuesta", function (e) {
      e.preventDefault();

      const formData = $(this).serialize();
      const id = $('input[name="mensaje_id"]').val();
      console.log("Formulario actual corresponde a ID:", id);

      $.post("mensajesAjax.php", formData, function (respuesta) {
          if (respuesta.success) {
              $('#formRespuesta').remove();

              const replyHTML = `
                  <div class="reply mt-3">
                      <div class="reply-box" style="width: 100%; position: relative;">
                          <div class="reply-header d-flex justify-content-between align-items-center">
                              <div class="d-flex flex-wrap align-items-center gap-2">
                                  <span class="reply-name">${respuesta.admin_nombre} ${respuesta.admin_apellido}</span>
                                  <span class="rol">${respuesta.rol}</span>
                              </div>
                              <span class="text-muted ms-2">${respuesta.fecha}</span>
                          </div>
                          <span class="reply-text texto-largo-contenido">${respuesta.respuesta}</span>
                      </div>
                  </div>
              `;

              $("#resultado_filtro_mensaje .position-relative").append(replyHTML);

              const fila = $(`#row_${id} td.col-estado span`);
              if (fila.length > 0) {
                  fila.fadeOut(200, function () {
                      $(this)
                          .removeClass("bg-warning bg-primary text-dark")
                          .addClass("bg-success")
                          .text("Respondido")
                          .fadeIn(200);
                  });
              }

              $("#resultado_filtro_mensaje .badge")
                  .removeClass("bg-warning bg-primary text-dark")
                  .addClass("bg-success")
                  .text("Respondido");

              if (typeof actualizarContadorMensajesPendientes === "function") {
                  actualizarContadorMensajesPendientes();
              }

              $('#resultado_filtro_mensaje .alert').remove();
              $('#resultado_filtro_mensaje').prepend(`
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                      Respuesta enviada correctamente.
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                  </div>
              `);
          } else {
              $('#resultado_filtro_mensaje .alert').remove();
              $('#resultado_filtro_mensaje').prepend(`
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      ${respuesta.error || "Ocurrió un error al guardar la respuesta."}
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                  </div>
              `);
          }
      }, "json");
  });

  function esperarYInicializarTabla() {
    const tabla = document.querySelector("#tablaMensajes");
    if (tabla) {
      delete window.estadoPersonalizado;
      inicializarTablaMensajes();
    } else {
      setTimeout(esperarYInicializarTabla, 100);
    }
  }

  window.addEventListener("DOMContentLoaded", () => {
    esperarYInicializarTabla();
  });

  window.cargarVistaMensajes = function () {
    delete window.estadoPersonalizado;

    if ($.fn.DataTable.isDataTable("#tablaMensajes")) {
      tabla.destroy();
      $("#tablaMensajes").empty();
      tabla = null;
    }

    inicializarTablaMensajes();
  };
  window.actualizarContadorMensajesPendientes = function () {
    $.post('mensajesAjax.php', { accion: 'contarPendientes' }, function(res) {
        if (res.success) {
            $('#mensajesPorResponder').text(`${res.pendientes} de ${res.total}`);
        }
    }, 'json');
  }
})();
