/* ===================== lista.js ===================== */
/* Funciones de listado, edición y PDF de contrato (NO cancelaciones) */

function cargarTabla() {
  const estado = document.getElementById("filtro-estado").value;
  $.ajax({
    url: "../php/cargarTabla.php",
    type: "POST",
    data: JSON.stringify({ estado }),
    contentType: "application/json",
    success: function (response) { $("#tabla").html(response); },
    error: function (_, textStatus) { $("#tabla").html("Error al cargar la tabla: " + textStatus); },
  });
}

/* --- Utilidades compartidas con el PDF de contrato --- */
async function loadImage(url, typeHint) {
  const res = await fetch(url, { cache: "no-store" });
  if (!res.ok) throw new Error(`HTTP ${res.status} al cargar ${url}`);
  const ct = (res.headers.get("Content-Type") || "").toLowerCase();
  if (!ct.startsWith("image/")) throw new Error(`No es imagen (${ct}) -> ${url}`);
  const blob = await res.blob();
  const mime = (blob.type || typeHint || "").toLowerCase();
  const dataUrl = await new Promise((resolve, reject) => {
    const fr = new FileReader();
    fr.onload = () => resolve(fr.result);
    fr.onerror = reject;
    fr.readAsDataURL(blob);
  });
  return { dataUrl, mime };
}
const mimeToFormat = (mime) => (mime && mime.includes("png") ? "PNG" : "JPEG");
const addImg = (pdf, img, x, y, w, h) => {
  const format = mimeToFormat(img.mime);
  pdf.addImage(img.dataUrl, format, x, y, w, h);
};
const pad2 = (n) => String(n).padStart(2, "0");
function formateaFechaMX(f) {
  const d = new Date((f || "").replace(" ", "T"));
  if (isNaN(d)) return String(f || "");
  return `${pad2(d.getDate())}/${pad2(d.getMonth() + 1)}/${d.getFullYear()} ${pad2(d.getHours())}:${pad2(d.getMinutes())}`;
}

/* -------------------- PDF del CONTRATO -------------------- */
let signaturePad = null;
let signaturePad2 = null;

async function imprimirContrato(
  idcontrato, nombre, rlegal, calle, numero, colonia, municipio, cp, estado,
  telefono, ttelefono, rfc, fecha, tarifa, tmensualidad, reconexion, mdesconexion,
  plazo, modeme, marca, modelo, nserie, nequipo, pagoum, pequipo, domicilioi,
  fechai, hora, costoi, autorizacion, mpago, vigencia, banco, notarjeta,
  sadicional1, dadicional1, costoa1, sadicional2, dadicional2, costoa2,
  sfacturable1, dfacturable1, costof1, sfacturable2, dfacturable2, costof2,
  ccontrato, cderechos, cciudad, firma1, firma2
) {
  const image1  = await loadImage("../img/bbs-c-1.jpg");
  const image2  = await loadImage("../img/bbs-c-2.jpg");
  const image3  = await loadImage("../img/bbs-c-3.jpg");
  const image4  = await loadImage("../img/bbs-c-4.jpg");
  const image5  = await loadImage("../img/bbs-c-5.jpg");
  const image6  = await loadImage("../img/bbs-c-6.jpg");
  const image7  = await loadImage("../img/bbs-c-7.jpg");
  const image8  = await loadImage("../img/bbs-c-8.jpg");
  const image9  = await loadImage("../img/bbs-c-9.jpg");
  const image11 = await loadImage("../img/bbs-c-11.jpg");
  const firma   = await loadImage("../img/firma-s.png");

  const pdf = new jsPDF("p", "pt", "letter");

  // Página 1 (plantilla)
  addImg(pdf, image1, 0, 0, 565, 792);

  pdf.setFontSize(7);
  if (pdf.setFontStyle) pdf.setFontStyle("bold"); // compatibilidad

  pdf.text(nombre, 200, 113);
  pdf.text(rlegal, 155, 126);
  pdf.text(calle, 50, 157);
  pdf.text(numero, 215, 157);
  pdf.text(colonia, 240, 157);
  pdf.text(municipio, 305, 157);
  pdf.text(cp, 377, 157);
  pdf.text(estado, 420, 157);
  pdf.text(rfc, 377, 183);

  if (ttelefono == "movil") { pdf.text(telefono, 230, 181); pdf.circle(83, 167, 3, "F"); }
  else { pdf.text(telefono, 228, 182); pdf.circle(132, 167, 3, "F"); }

  if (parseInt(tarifa) === 1) pdf.text("Residencial 7 MBPS", 230, 240);
  else if (parseInt(tarifa) === 2) pdf.text("Residencial 10 MBPS", 230, 240);
  else if (parseInt(tarifa) === 3) pdf.text("Residencial 15 MBPS", 230, 240);
  else if (parseInt(tarifa) === 7) pdf.text("Residencial 30 MBPS", 230, 240);
  else if (parseInt(tarifa) === 4) pdf.text("Empresarial 50 MBPS", 230, 240);
  else if (parseInt(tarifa) === 8) pdf.text("Empresarial 80 MBPS", 230, 240);

  pdf.text(plazo, 425, 272);
  pdf.text("1 al 5", 455, 230);
  pdf.text("cada mes", 455, 236);
  pdf.text(tmensualidad, 270, 252);

  if (parseInt(reconexion) === 1) { pdf.circle(215, 303, 3, "F"); pdf.text("0", 285, 295); pdf.circle(357, 250, 3, "F"); }
  else if (parseInt(reconexion) === 2) { pdf.circle(193, 302, 3, "F"); pdf.text("500", 285, 295); }

  if (parseInt(modeme) === 1) pdf.circle(200, 349, 3, "F");
  else if (parseInt(modeme) === 2) pdf.circle(406, 350, 3, "F");

  pdf.text(marca, 210, 365);
  pdf.text(modelo, 210, 376);
  pdf.text(nserie, 210, 387);
  pdf.text(nequipo, 210, 399);

  if (parseInt(pagoum) === 1) pdf.circle(386, 442, 3, "F");
  else if (parseInt(pagoum) === 2) pdf.circle(350, 442, 3, "F");

  pdf.text(pequipo, 242, 444);
  pdf.text(domicilioi, 190, 482);
  pdf.text(fechai, 180, 493);
  pdf.text(hora, 350, 493);
  pdf.text(costoi, 180, 506);

  if (autorizacion == "si") pdf.circle(158, 645, 3, "F");
  else pdf.circle(199, 645, 3, "F");

  if (parseInt(mpago) === 1) pdf.circle(42, 583, 3, "F");
  else if (parseInt(mpago) === 2) pdf.circle(42, 593, 3, "F");
  else if (parseInt(mpago) === 3) pdf.circle(42, 603, 3, "F");
  else if (parseInt(mpago) === 4) pdf.circle(42, 613, 3, "F");

  pdf.text(vigencia, 455, 662);

  // Página 2
  pdf.addPage();
  addImg(pdf, image2, 0, 0, 565, 792);
  pdf.text(banco, 80, 91.5); pdf.text(notarjeta, 330, 92);

  pdf.text(sadicional1, 120, 94); pdf.text(dadicional1, 50, 117); pdf.text(costoa1, 240, 117);
  pdf.text(sadicional2, 320, 94); pdf.text(dadicional2, 290, 117); pdf.text(costoa2, 445, 117);

  pdf.text(sfacturable1, 120, 178); pdf.text(dfacturable1, 50, 200); pdf.text(costof1, 240, 200);
  pdf.text(sfacturable2, 320, 178); pdf.text(dfacturable2, 290, 200); pdf.text(costof2, 445, 200);

  if (ccontrato == true) pdf.circle(448, 226, 3, "F"); else pdf.circle(464, 226, 3, "F");
  if (cderechos == true) pdf.circle(448, 238, 3, "F"); else pdf.circle(464, 238, 3, "F");

  pdf.text(idcontrato, 155, 440);

  let dayContrato = fecha.substring(8, 10);
  let monthContrato = fecha.substring(5, 7);
  let yearContrato = fecha.substring(0, 4);
  let mes;
  switch (monthContrato) {
    case "01": mes = "Enero"; break; case "02": mes = "Febrero"; break; case "03": mes = "Marzo"; break;
    case "04": mes = "Abril"; break; case "05": mes = "Mayo"; break; case "06": mes = "Junio"; break;
    case "07": mes = "Julio"; break; case "08": mes = "Agosto"; break; case "09": mes = "Septiembre"; break;
    case "10": mes = "Octubre"; break; case "11": mes = "Noviembre"; break; case "12": mes = "Diciembre"; break;
  }

  pdf.text(cciudad, 255, 496);
  pdf.text(dayContrato, 323, 496);
  pdf.text(mes, 360, 496);
  pdf.text(yearContrato, 415, 496);

  // Firma base (imagen estática)
  addImg(pdf, firma, 70, 515, 200, 30);

  // Firma del cliente (dataURL que viene del back)
  pdf.addImage(firma1, "PNG", 270, 515, 200, 30);

  // Páginas 3 a 9
  pdf.addPage(); addImg(pdf, image3, 0, 0, 565, 792);
  pdf.addPage(); addImg(pdf, image4, 0, 0, 565, 792);
  pdf.addPage(); addImg(pdf, image5, 0, 0, 565, 792);
  pdf.addPage(); addImg(pdf, image6, 0, 0, 565, 792);
  pdf.addPage(); addImg(pdf, image7, 0, 0, 565, 792);
  pdf.addPage(); addImg(pdf, image8, 0, 0, 565, 792);
  pdf.addPage(); addImg(pdf, image9, 0, 0, 565, 792);

  // Página 11
  pdf.addPage();
  addImg(pdf, image11, 0, 0, 565, 792);
  if (parseInt(modeme) == 1) {
    pdf.text(`${dayContrato}/${monthContrato}/${yearContrato}`, 161, 252);
    pdf.text(nombre, 123, 323.5);
    pdf.text(municipio, 240, 299);
    pdf.text(dayContrato, 349.5, 299);
    pdf.text(monthContrato, 375, 299);
    pdf.text(yearContrato.substring(2, 4), 412, 299);
    pdf.text("Av. José María Morelos 147, Loma Linda", 126, 335);
    pdf.text("38980 Uriangato, Gto.", 100, 346);

    // Firma en página 11 (dataURL que viene del back)
    pdf.addImage(firma2, "PNG", 280, 335, 190, 30);
  }

  window.open(pdf.output("bloburl"), "_blank");
}

function descargarContrato(id) {
  $.ajax({
    url: "../php/imprimirPDF.php",
    type: "POST",
    data: { id },
    success: function (response) {
      const data = JSON.parse(response);
      imprimirContrato(
        "" + data.idcontrato, data.nombre, data.rlegal, data.calle, data.numero,
        data.colonia, data.municipio, data.cp, data.estado, data.telefono, data.ttelefono,
        data.rfc, data.fecha, data.tarifa, "" + data.tmensualidad, data.reconexion,
        data.mdesconexion, "" + data.plazo, data.modeme, data.marca, data.modelo,
        data.nserie, "" + data.nequipo, data.pagoum, "" + data.pequipo, data.domicilioi,
        data.fechai, data.hora, "" + data.costoi, data.autorizacion, data.mpago,
        "" + data.vigencia, data.banco, data.notarjeta, data.sadicional1, data.dadicional1,
        "" + data.costoa1, data.sadicional2, data.dadicional2, "" + data.costoa2,
        data.sfacturable1, data.dfacturable1, "" + data.costof1, data.sfacturable2,
        data.dfacturable2, "" + data.costof2, data.ccontrato, data.cderechos,
        data.cciudad, data.firma1, data.firma2
      );
    },
    error: function (_, textStatus) {
      $("#resultado").html("Error al eliminar el contrato: " + textStatus);
    },
  });
}

/* Altas/ediciones */
function addContract(id) {
  $.ajax({
    url: "../php/agregarCliente.php",
    type: "POST",
    data: { id },
    success: function (response) { $("#modal").html(response); },
    error: function (_, textStatus) { $("#resultado").html("Error al eliminar el contrato: " + textStatus); },
  });
}
function validateAndAddUsuario(id) {
  var localidad = $("#localidad").val();
  var nodo = $("#nodo").val();
  var ip = $("#ip").val();
  var email = $("#email").val();
  var splitter = $("#splitter").val();

  if (localidad === "" || nodo === "" || ip === "" || email === "") {
    Swal.fire({ title: "Campos incompletos", text: "Por favor, complete todos los campos obligatorios.", icon: "warning" });
    return;
  }
  var ipPattern = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
  if (!ipPattern.test(ip)) {
    Swal.fire({ title: "IP no válida", text: "Por favor, ingrese una dirección IP válida (ej. 192.168.0.1).", icon: "error" });
    return;
  }
  addUsuario(id);
}
function addUsuario(id) {
  $.ajax({
    url: "../php/insertCliete.php",
    type: "GET",
    data: {
      id,
      localidad: $("#localidad option:selected").html(),
      nodo: $("#nodo option:selected").html(),
      ip: $("#ip").val(),
      email: $("#email").val(),
      splitter: $("#splitter").val(),
    },
    success: function (response) {
      if (response == "insercion exitosa") {
        $("#modalAgregar").modal("hide");
        cargarTabla();
        Swal.fire("¡Creado!", "El usuario se ha creado correctamente.", "success");
      } else {
        Swal.fire({
          title: "No se pudo generar el usuario",
          html: "<div style='text-align:center;font-weight:500;'>El ID ingresado ya existe.</div>",
          icon: "error",
          width: "35rem",
        });
      }
    },
    error: function (_, textStatus) {
      $("#resultado").html("Error al eliminar el contrato: " + textStatus);
    },
  });
}
function editContract(id) {
  $.ajax({
    url: "../php/editarContrato.php",
    type: "POST",
    data: { id },
    success: function (response) { $("#modal2").html(response); },
    error: function (_, textStatus) { $("#resultado").html("Error al editar el contrato: " + textStatus); },
  });
}
function updateContrato() {
  let idcontrato = document.getElementById("ncontrato").value;
  let name = document.getElementById("name").value;
  let rlegal = document.getElementById("rlegal").value;
  let street = document.getElementById("street").value;
  let number = document.getElementById("number").value;
  let colonia = document.getElementById("colonia").value;
  let municipio = document.getElementById("municipio").value;
  let cp = document.getElementById("cp").value;
  let estado = document.getElementById("estado").value;
  let rfc = document.getElementById("rfc").value;
  let telefono = document.getElementById("telefono").value;
  let ttipo = document.querySelector('input[name="ttipo"]:checked').value;
  let tarifa = document.getElementById("tarifa").value;
  let total = document.getElementById("totalm").value;
  let plazo = document.getElementById("pmeses").value;
  let reconexion = document.getElementById("reconexion").value;
  let mdesco = document.getElementById("descm").value;
  let modemt = document.getElementById("modemt").value;
  let marca = document.getElementById("marca").value;
  let modelo = document.getElementById("modelo").value;
  let serie = document.getElementById("serie").value;
  let nequipos = document.getElementById("nequipos").value;
  let tpago = document.getElementById("tpago").value;
  let cequipos = document.getElementById("cequipos").value;
  let domicilioi = document.getElementById("domicilioi").value;
  let fechai = document.getElementById("fechai").value;
  let horai = document.getElementById("horai").value;
  let costoi = document.getElementById("costoi").value;
  let acargo = document.querySelector('input[name="acargo"]:checked').value;
  let mpago = document.getElementById("mpago").value;
  let cmes = document.getElementById("cmes").value;
  let banco = document.getElementById("banco").value;
  let ntarjeta = document.getElementById("ntarjeta").value;
  let sadicional1 = document.getElementById("sadicional1").value;
  let sdescripcion1 = document.getElementById("sdescripcion1").value;
  let scosto1 = document.getElementById("scosto1").value;
  let sadicional2 = document.getElementById("sadicional2").value;
  let sdescripcion2 = document.getElementById("sdescripcion2").value;
  let scosto2 = document.getElementById("scosto2").value;
  let fadicional1 = document.getElementById("fadicional1").value;
  let fdescripcion1 = document.getElementById("fdescripcion1").value;
  let fcosto1 = document.getElementById("fcosto1").value;
  let fadicional2 = document.getElementById("fadicional2").value;
  let fdescripcion2 = document.getElementById("fdescripcion2").value;
  let fcosto2 = document.getElementById("fcosto2").value;
  let ccontrato = document.getElementById("ccontrato").checked;
  let cdminimos = document.getElementById("cdminimos").checked;
  let ciudad = document.getElementById("ciudad").value;
  let scontrato = document.getElementById("scontrato").checked;
  let ncontrato = document.getElementById("ncontrato").value;
  let equiposDev = document.getElementById("equipos_devueltos")?.value || "";
  let fechaCancel = document.getElementById("fecha_cancelacion")?.value || "";

  var formData = new FormData();
  formData.append("nombre", name);
  formData.append("idcontrato", idcontrato);
  formData.append("rlegal", rlegal);
  formData.append("calle", street);
  formData.append("numero", number);
  formData.append("colonia", colonia);
  formData.append("municipio", municipio);
  formData.append("cp", cp);
  formData.append("estado", estado);
  formData.append("rfc", rfc);
  formData.append("fechac", fechac.value);
  formData.append("telefono", telefono);
  formData.append("ttipo", ttipo);
  formData.append("tarifa", tarifa);
  formData.append("total", total);
  formData.append("reconexion", reconexion);
  formData.append("mdesco", mdesco);
  formData.append("plazo", plazo);
  formData.append("modemt", modemt);
  formData.append("marca", marca);
  formData.append("modelo", modelo);
  formData.append("serie", serie);
  formData.append("nequipos", nequipos);
  formData.append("tpago", tpago);
  formData.append("cequipos", cequipos);
  formData.append("domicilioi", domicilioi);
  formData.append("fechai", fechai);
  formData.append("horai", horai);
  formData.append("costoi", costoi);
  formData.append("acargo", acargo);
  formData.append("mpago", mpago);
  formData.append("cmes", cmes);
  formData.append("banco", banco);
  formData.append("ntarjeta", ntarjeta);
  formData.append("sadicional1", sadicional1);
  formData.append("sdescripcion1", sdescripcion1);
  formData.append("scosto1", scosto1);
  formData.append("sadicional2", sadicional2);
  formData.append("sdescripcion2", sdescripcion2);
  formData.append("scosto2", scosto2);
  formData.append("fadicional1", fadicional1);
  formData.append("fdescripcion1", fdescripcion1);
  formData.append("fcosto1", fcosto1);
  formData.append("fadicional2", fadicional2);
  formData.append("fdescripcion2", fdescripcion2);
  formData.append("fcosto2", fcosto2);
  formData.append("ccontrato", ccontrato);
  formData.append("cdminimos", cdminimos);
  formData.append("scontrato", scontrato);
  formData.append("ciudad", ciudad);
  formData.append("ncontrato", ncontrato);
  formData.append("plazo", plazo);
  formData.append("ex", scontrato);
  formData.append("equipos_devueltos", equiposDev);
  formData.append("fecha_cancelacion", fechaCancel);

  $.ajax("../php/updateContrato.php", {
    method: "POST",
    data: formData,
    processData: false,
    contentType: false,
    success: function (data) {
      var jsonResponse = JSON.parse(data);
      if (jsonResponse.status === "success") {
        $("#modalEditar").modal("hide");
        cargarTabla();
        Swal.fire("Éxito", jsonResponse.message, "success");
      } else {
        Swal.fire("Error", jsonResponse.message, "error");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", textStatus, errorThrown, jqXHR.responseText);
      Swal.fire({ title: "No se pudo generar el contrato", icon: "error", width: "35rem" });
    },
  });
}

/* Listeners */
$(document).on("change", "#tarifa", function () {
  let tarifa = $(this).val(), mensualidad = $("#totalm");
  switch (tarifa) {
    case "1": mensualidad.val("250"); break;
    case "2": mensualidad.val("350"); break;
    case "3": mensualidad.val("450"); break;
    case "4": mensualidad.val("500"); break;
    case "5": mensualidad.val("600"); break;
    case "7": mensualidad.val("350"); break;
    case "8": mensualidad.val("800"); break;
    default: mensualidad.val("");
  }
});
$(document).on("change", "#reconexion", function () {
  let reconexion = document.getElementById("reconexion");
  let mdesconexion = document.getElementById("descm");
  reconexion.addEventListener("change", () => {
    if (reconexion.value == 1) mdesconexion.value = "$0";
    else if (reconexion.value == 2) mdesconexion.value = "$500";
  });
});

cargarTabla();
/* =================== fin lista.js =================== */
