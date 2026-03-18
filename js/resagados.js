/* ===================== resagados.js ===================== */

function cargarTablaResagados() {
  const filtro = document.getElementById("filtro-resagados").value;

  $.ajax({
    url: "../php/cargarTablaResagados.php",
    type: "POST",
    data: JSON.stringify({ filtro }),
    contentType: "application/json",
    success: function (html) {
      $("#tabla-resagados").html(html);

      // Si el PHP devuelve una tabla con id="tablaResagados", inicializamos DataTables
      const t = document.getElementById("tablaResagados");
      if (t && !t.dataset.dtReady) {
        new DataTable("#tablaResagados", {
          pageLength: 25,
          order: [[0, "desc"]],
          language: {
            search: "Buscar:",
            lengthMenu: "Mostrar _MENU_",
            info: "Mostrando _START_ a _END_ de _TOTAL_",
            paginate: { previous: "Anterior", next: "Siguiente" },
            zeroRecords: "Sin resultados",
          },
        });
        t.dataset.dtReady = "1";
      }
    },
    error: function (_, textStatus) {
      $("#tabla-resagados").html("Error al cargar: " + textStatus);
    },
  });
}

// Cancelar un resagado: usamos tu misma función de cancelaciones
// Nota: si el contrato NO existe, el backend cancelar_contrato.php debe crearlo como legado (lo armamos luego)
function cancelarResagado(idcliente) {
  if (window.Cancelaciones?.confirmarCancelacion) {
    window.Cancelaciones.confirmarCancelacion(idcliente);
  } else if (typeof confirmarCancelacion === "function") {
    confirmarCancelacion(idcliente);
  } else {
    Swal.fire("Error", "No se encontró cancelaciones.js", "error");
  }
}

cargarTablaResagados();

/* =================== fin resagados.js =================== */