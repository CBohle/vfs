window.tabla = window.tabla || null;
window.ordenManualActivado = window.ordenManualActivado || false;

//console.log("postulaciones.js cargado");
function inicializarTablaPostulaciones() {
  if ($.fn.DataTable.isDataTable("#tablaPostulaciones")) {
    tabla.clear().destroy();
  }
  //console.log("Iniciando tabla de postulaciones"); 
  tabla = $("#tablaPostulaciones").DataTable({
    rowId: function(row) {
      return 'row_' + row.id;
    },
    responsive: false,
    scrollX: true,
    processing: true,
    serverSide: true,
    destroy: true,
    ajax: {
      url: BASE_ADMIN_URL + "/postulacionesAjax.php",
      type: "POST",
      data: function (d) {
        d.estado = $("#filtro_estado").val();
        d.importante = $("#filtro_importante").val();
        d.search.value = $("#filtro_busqueda").val();

        // Aplica orden manual (por filtro) solo si NO hay orden del usuario
        if (window.ordenManualActivado && (!d.order || d.order.length === 0)) {
          const ordenSeleccionado = $("#filtro_orden").val();
          if (ordenSeleccionado === "ASC" || ordenSeleccionado === "DESC") {
            d.order = [{ column: 27, dir: ordenSeleccionado.toLowerCase() }];
          }
        }
      },
      dataSrc: function (json) {
        if (
          json.totalPendientesPostulaciones !== undefined &&
          json.totalPostulaciones !== undefined
        ) {
          $("#PostulacionesPorResponder").text(
            json.totalPendientesPostulaciones + " de " + json.totalPostulaciones
          );
        }
        return json.data;
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
          return `<i class="bi ${icon} marcarImportantePostulacion" data-id="${row.id}" data-valor="${data}" style="cursor:pointer;"></i>`;
        },
      },
      {
        data: "importante_texto",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "id",
      },
      {
        data: "rut",
      },
      {
        data: "nombre",
      },
      {
        data: "apellido",
      },
      {
        data: "fecha_nacimiento",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "estudios",
      },
      {
        data: "institucion_educacional",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "ano_titulacion",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "formacion_tasacion",
        className: "text-center",
        render: function (data) {
          return data == 1
            ? '<i class="bi bi-check-circle-fill text-success fs-5"></i>'
            : '<i class="bi bi-x-circle-fill text-danger fs-5"></i>';
        },
        exportable: false
      },
      {
        data: "formacion_tasacion_texto",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "detalle_formacion",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "anos_experiencia_tasacion",
        className: "text-center",
      },
      {
        data: "otra_empresa",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "disponibilidad_comuna",
        className: "text-center",
        render: function (data, type, row) {
          if (type === 'display') {
            return data == 1
              ? '<i class="bi bi-check-circle-fill text-success fs-5"></i>'
              : '<i class="bi bi-x-circle-fill text-danger fs-5"></i>';
          } else if (type === 'export') {
            return data == 1 ? 'Sí' : 'No';
          } else {
            return data;
          }
        }
      },
      {
        data: "disponibilidad_comuna_texto",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "disponibilidad_region",
        className: "text-center",
        render: function (data, type, row) {
          if (type === 'display') {
            return data == 1
              ? '<i class="bi bi-check-circle-fill text-success fs-5"></i>'
              : '<i class="bi bi-x-circle-fill text-danger fs-5"></i>';
          } else if (type === 'export') {
            return data == 1 ? 'Sí' : 'No';
          } else {
            return data;
          }
        }
      },
      {
        data: "disponibilidad_region_texto",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "movilizacion_propia",
        className: "text-center",
        render: function (data, type, row) {
          if (type === 'display') {
            return data == 1
              ? '<i class="bi bi-check-circle-fill text-success fs-5"></i>'
              : '<i class="bi bi-x-circle-fill text-danger fs-5"></i>';
          } else if (type === 'export') {
            return data == 1 ? 'Sí' : 'No';
          } else {
            return data;
          }
        }
      },
      {
        data: "movilizacion_propia_texto",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "email",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "telefono",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "direccion",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "comuna",
        visible: false,
        render: function (data) {
          return data;
        }
      },
      {
        data: "region",
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
      {
        data: "fecha_creacion",
      },
      {
        data: "archivo",
        className: "text-center",
        render: function (data, type, row) {
          if (data) {
            return `<button class="btn btn-sm btn-secondary" onclick="verPDF('/${data}')"><i class="bi bi-file-earmark-pdf"></i></button>`;
          } else {
            return '<span class="text-muted">Vacio</span>';
          }
        },
      },
      {
        data: null,
        orderable: false,
        searchable: false,
        className: "text-center",
        render: function (data, type, row) {
          let botones = `
            <button class="btn btn-sm btn-primary me-1" title="Ver" onclick="verPostulacion(${row.id})">
              <i class="bi bi-eye"></i>
            </button>
          `;

          const puedeEliminar = PERMISOS?.postulaciones?.includes('eliminar');

          if (puedeEliminar) {
            if (row.estado.toLowerCase() === "eliminado") {
              botones += `
                <button class="btn btn-sm btn-success" title="Recuperar" onclick="recuperarPostulacion(${row.id})">
                  <i class="bi bi-arrow-counterclockwise"></i>
                </button>
              `;
            } else {
              botones += `
                <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarPostulacion(${row.id})">
                  <i class="bi bi-trash"></i>
                </button>
              `;
            }
          }

          return botones;
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
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 16, 18, 20, 21, 22, 23, 24, 25, 26, 27], // Seleccion de columnas a copiar
          format: {
            body: function (data, row, column, node) {
              return typeof data === 'string' ? data.replace(/<.*?>/g, '') : data;
            }
          }
        }
      },
      {
        extend: "excelHtml5",
        text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel',
        className: "btn btn-success btn-sm",
        exportOptions: {
          columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 11, 12, 13, 14, 16, 18, 20, 21, 22, 23, 24, 25, 26, 27], // Seleccion de columnas a exportar
          format: {
            body: function (data, row, column, node) {
              // Si es una celda con HTML, elimina los tags
              return typeof data === 'string' ? data.replace(/<.*?>/g, '') : data;
            }
          }
        }
      },
    ],
    initComplete: function () {
      $("#loaderTabla").hide();
      $("#tablaPostulaciones thead").show();
      $("#tablaPostulaciones_wrapper").show();
      tabla.columns.adjust().draw();
      tabla.buttons().container().appendTo("#exportButtons");
      $('#tablaPostulaciones thead').on('click', 'th', function () {
        $("#filtro_orden").val(""); // limpia visual
        window.ordenManualActivado = false; // desactiva orden manual
      });
    },
  });
}

// Evento para búsqueda en tiempo real
$(document).on("keyup", "#filtro_busqueda", function () {
  if (tabla) tabla.draw(); // Redibuja tabla con nuevo filtro
});

function filtrar() {
  const ordenSeleccionado = $("#filtro_orden").val();
  window.ordenManualActivado = (ordenSeleccionado === "ASC" || ordenSeleccionado === "DESC");
  inicializarTablaPostulaciones();
}

function resetearFiltros() {
  window.ordenManualActivado = false;
  $("#filtro_estado").val("");
  $("#filtro_orden").val("");
  $("#filtro_orden").prop("selectedIndex", 0);
  $("#filtro_importante").val("");
  $("#filtro_busqueda").val("");
  inicializarTablaPostulaciones();
}

function eliminarPostulacion(id) {
  Swal.fire({
    title: '¿Eliminar postulación?',
    text: 'Esta acción no se puede deshacer.',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33', // rojo Bootstrap
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(
        'postulacionesAjax.php',
        { accion: 'eliminar', id: id },
        function (response) {
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Postulación eliminada',
              text: 'La postulación fue eliminada correctamente.',
              timer: 2000,
              showConfirmButton: false
            });
            tabla.ajax.reload(null, false); // recarga sin reiniciar paginación
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error al eliminar',
              text: 'Hubo un problema al intentar eliminar la postulación.',
              confirmButtonText: 'Cerrar'
            });
          }
        },
        'json'
      ).fail(() => {
        Swal.fire({
          icon: 'error',
          title: 'Error de conexión',
          text: 'No se pudo contactar con el servidor.',
          confirmButtonText: 'Cerrar'
        });
      });
    }
  });
}

function verPostulacion(id) {
  $("#contenidoModalPostulacion").html(
    '<p class="text-center text-muted">Cargando...</p>'
  );
  $("#modalVerPostulacion").modal("show");
  $("#botonImportanteWrapper").empty();
  // Marcar como leído
  $.post(
    "postulacionesAjax.php",
    {
      accion: "marcarLeido",
      id: id,
    },
    function (response) {
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
    },
    "json"
  );
  // Cargar contenido del modal
  $.get(
    "postulacionModal.php",
    {
      id,
    },
    function (respuesta) {
      $("#contenidoModalPostulacion").html(respuesta);

      const botonHTML = $("#contenidoModalPostulacion")
        .find("#botonImportanteHTML")
        .html();
      $("#botonImportanteWrapper").html(botonHTML);
    }
  ).fail(function () {
    $("#contenidoModalPostulacion").html(
      '<p class="text-danger">Error al cargar la postulación.</p>'
    );
  });
}

function recuperarPostulacion(id) {
  Swal.fire({
    title: '¿Recuperar postulación?',
    text: 'La postulación volverá a estar disponible en el sistema.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonColor: '#198754', // verde Bootstrap
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí, recuperar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(
        'postulacionesAjax.php',
        { accion: 'recuperar', id: id },
        function (response) {
          if (response.success) {
            Swal.fire({
              icon: 'success',
              title: 'Postulación recuperada',
              text: 'La postulación fue recuperada con éxito.',
              timer: 2000,
              showConfirmButton: false
            });
            tabla.ajax.reload(null, false); // recarga sin reiniciar paginación
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error al recuperar',
              text: 'No se pudo recuperar la postulación.',
              confirmButtonText: 'Cerrar'
            });
          }
        },
        'json'
      ).fail(() => {
        Swal.fire({
          icon: 'error',
          title: 'Error de conexión',
          text: 'No se pudo contactar con el servidor.',
          confirmButtonText: 'Cerrar'
        });
      });
    }
  });
}

function verPDF(rutaArchivo) {
  $("#iframePDF").attr("src", rutaArchivo);
  $("#btnDescargarPDF").attr("href", rutaArchivo);
  $("#modalPDF").modal("show");
}

$(document).ready(function () {
  inicializarTablaPostulaciones();

    window.toggleImportante = function (id, estadoActual) {
      const nuevoValor = estadoActual === 1 ? 0 : 1;

      $.post('postulacionesAjax.php', {
          accion: 'importante',
          postulacion_id: id,
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
                boton.attr('onclick', `toggleImportante(${id}, ${nuevoValor})`);
                icono.fadeIn(150);
              });
              const iconoTabla = $(`#row_${id} .marcarImportantePostulacion`);
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
              Swal.fire({
                icon: 'error',
                title: 'No se pudo actualizar',
                text: 'Ocurrió un error al cambiar la importancia de la postulación.',
                confirmButtonText: 'Entendido'
            });
          }
      }, 'json');
    }
});
$(document).on("click", ".marcarImportantePostulacion", function () {
  const icono = $(this);
  const id = $(this).data("id");
  const valorActual = $(this).data("valor");
  const nuevoValor = valorActual == 1 ? 0 : 1;

  $.post(
    "postulacionesAjax.php",
    {
      accion: "importante",
      postulacion_id: id,
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