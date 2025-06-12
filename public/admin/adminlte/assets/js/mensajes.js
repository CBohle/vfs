(function () {
  let tabla = null;
  window.ordenManualActivado = window.ordenManualActivado || false;
  function inicializarTablaMensajes() {
    if (tabla) return; // Ya inicializada

    tabla = $("#tablaMensajes").DataTable({
      serverSide: true,
      processing: true,
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
            return `<span class="${clase}">${
              data.charAt(0).toUpperCase() + data.slice(1)
            }</span>`;
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

            if (ROL_ID === 4 || ROL_ID === 5) {
              // Practicante solo puede ver
              return verBtn;
            }

            if (row.estado.toLowerCase() === "eliminado") {
              return verBtn + `
                <button class="btn btn-sm btn-success" title="Recuperar" onclick="recuperarMensaje(${row.id})">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>`;
            } else {
              return verBtn + `
                <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarMensaje(${row.id})">
                    <i class="bi bi-trash"></i>
                </button>`;
            }
          },
        },
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
    $("#contenidoModalMensaje").html(
      '<p class="text-center text-muted">Cargando...</p>'
    );
    $("#modalVerMensaje").modal("show");

    $.post(
      "mensajesAjax.php",
      { accion: "marcarLeido", id: id },
      function (response) {
        if (response.success && typeof tabla !== "undefined") {
          tabla.ajax.reload(null, false);
        }
      },
      "json"
    );

    $.get("mensajeModal.php", { id: id }, function (respuesta) {
      $("#contenidoModalMensaje").html(respuesta);
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
          tabla.ajax.reload(null, false);
        } else {
          alert("No se pudo actualizar el estado de importancia.");
        }
      },
      "json"
    );
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
})();
