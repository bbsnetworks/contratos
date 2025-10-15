<!Doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contratos BBS</title>
  <script src="js/jspdf.min.js"></script>
  <script src="js/signature_pad.umd.min.js"></script>
  <link href="css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <link rel="stylesheet" href="css/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/generales.css">
  <link rel="stylesheet" href="css/index.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />


</head>
<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../menu/login/index.php");
    exit();
}

//echo "Bienvenido, " . $_SESSION['username'];
?>
<body class="">
  <?php
  include_once("includes/sidebar.php");
  //include("php/navbar.php");
  ?>
  <form class="row centrar needs-validation" id="form" novalidate>
    <div class="row">
      <div class="col-12 encabezado centrar txt-center">
        <span>TEKNE SEND.4, S. DE R.L. DE C.V.<br>
          RFC: TSE230302694<br>
          DOMICILIO: EDUARDO ECHEVERRÍA, NÚMERO 21, INTERIOR B, LOCALIDAD MONTE DE LOS JUÁREZ, C.P. 38950, YURIRIA,
          GUANAJUATO.
        </span>
      </div>
      <!-- <div class="col-12 centrar tittle"><span>Primera Parte</span></div> -->
      <div class="col-md-4">
        <label for="inputEmail4" class="form-label">Contrato No</label>
        <?php
        include_once("php/conexion.php");
        // Check connection
        if ($conexion->connect_error) {
          die("Connection failed: " . $conexion->connect_error);
        }

        //consultas
        $sql = "select max(idcontrato) as mayor from contratos;";

        $result = $conexion->query($sql);

        if ($result->num_rows > 0) {
          // Output data of each row
          $mayor = "";
          while ($row = $result->fetch_assoc()) {
            //echo "ID: " . $row["nombre"];
            $mayor = intval($row['mayor']) + 1;
          }
        }
        
        ?>
        <input type="number" class="form-control requerido" id="ncontrato" name="ncontrato" value="<?php echo $mayor?>" novalidate>
        <div id="error-message" style="color:red;"></div>
      </div>
      <div class="col-md-6 space centrar d-none" id="euser">
        <div class="form-check" id="divContrato">
          <input class="form-check-input" type="checkbox" id="scontrato" name="scontrato">
          <label class="form-check-label" for="scontrato">
            Estoy de acuerdo de sobreescribir el contrato ya existente
          </label>
        </div>
      </div>
      <div class="col-md-12 centrar space">
        <span class="titulo">Contacto del Cliente</span>
      </div>
      <div class="col-md-6">
        <label for="inputEmail4" class="form-label">NOMBRE/RAZÓN O DENOMINACIÓN SOCIAL</label>
        <input type="text" class="form-control requerido" id="name" name="name" required novalidate>
      </div>
      <div class="col-md-6">
        <label for="inputPassword4" class="form-label">REPRESENTANTE LEGAL</label>
        <input type="text" class="form-control" id="rlegal" name="rlegal">
      </div>
      <div class="col-md-4">
        <label for="inputEmail4" class="form-label">Calle</label>
        <input type="text" class="form-control requerido" id="street" name="calle" required novalidate>
      </div>
      <div class="col-md-2">
        <label for="inputPassword4" class="form-label">Numero</label>
        <input type="text" class="form-control requerido" id="number" name="number" required novalidate>
      </div>
      <div class="col-md-2">
        <label for="inputPassword4" class="form-label">Colonia</label>
        <input type="text" class="form-control requerido" id="colonia" name="colonia" required novalidate>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Municipio</label>
        <input type="text" class="form-control requerido" id="municipio" name="municipio" required novalidate
          onchange="cambioCiudad()">
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">CP</label>
        <input type="number" class="form-control" id="cp" name="cp" novalidate minlength="4" maxlength="5">
      </div>
      <div class="col-md-2">
        <label for="inputPassword4" class="form-label">Estado</label>
        <input type="text" class="form-control requerido" id="estado" name="estado" required novalidate>
      </div>
      <div class="col-md-4">
        <label for="telefono" class="form-label">Telefono</label>
        <input type="number" class="form-control requerido" id="telefono" name="telefono" required novalidate
          minlength="10" maxlength="15">
      </div>
      <div class="col-md-2">
        <label for="inputPassword4" class="form-label">TELÉFONO MÓVIL/FIJO</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="ttipo" id="movil" value="movil" checked>
          <label class="form-check-label" for="flexRadioDefault1">
            Fijo
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="ttipo" id="fijo" value="fijo">
          <label class="form-check-label" for="flexRadioDefault2">
            Movil
          </label>
        </div>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">RFC</label>
        <input type="text" class="form-control" id="rfc" name="rfc" maxlength="13">
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Fecha</label>
        <input type="date" class="form-control requerido" id="fechac" name="fechac" value="" required>
      </div>

      <div class="col-md-12 centrar space txt-center">
        <span class="titulo">Servicio de Internet Fijo en Casa</span>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Tarifa:</label>

        <select class="form-select" aria-label="Default select example" name="tarifa" id="tarifa">
          <option value="1" selected>Residencial 7 MB/s</option>
          <option value="2">Residencial 10 MB/s</option>
          <option value="3">Residencial 15 MB/s</option>
          <option value="4">Residencial 20 MB/s</option>
          <option value="7">Residencial 30 MB/s</option>
          <option value="5">Residencial 40 MB/s</option>
          <option value="6">Residencial 50 MB/s</option>
        </select>

      </div>

      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Total Mensualidad:</label>
        <input type="number" class="form-control requerido" id="totalm" name="totalm" placeholder="$" value="250"
          required>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Aplica tarifa por reconexión:</label>
        <select class="form-select" aria-label="Default select example" id="reconexion" name="reconexion">
          <option value="1" selected>No</option>
          <option value="2">Si</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Monto por Desconexion:</label>
        <input type="text" class="form-control" id="descm" name="descm" disabled value="0">
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Plazo mínimo en meses (0-12): <span data-bs-toggle="tooltip"
            data-bs-placement="top"
            data-bs-title="Pagando el costo remanente del equipo sin penalidad por el servicio"><i
              class="bi bi-question-circle"></i></span></label>
        <input type="number" class="form-control requerido" id="pmeses" name="pmeses" min="0" value="0" required>
      </div>
      <div class="col-12 aviso">
        <span>(En el Estado de cuenta y/o factura se podrá visualizar la fecha de corte del servicio y fecha de
          pago.)</span>
      </div>
      <div class="col-md-12 centrar space">
        <span class="titulo">Datos del Equipo</span>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Modem Entregado</label>
        <select class="form-select" aria-label="Default select example" id="modemt" name="modemt">
          <option value="1" selected>Comodato</option>
          <option value="2">Compraventa</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Marca</label>
        <input type="text" class="form-control requerido" id="marca" name="marca" required>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Modelo</label>
        <input type="text" class="form-control requerido" id="modelo" name="modelo" required>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Numero de Serie</label>
        <input type="text" class="form-control requerido" id="serie" name="serie" required>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Numero de Equipos</label>
        <input type="number" class="form-control requerido" id="nequipos" name="nequipos" required maxlength="50"
          min="1">
      </div>
      <div class="col-12 aviso text-center">
        <span>
          Garantía de cumplimiento de obligación<br>
          Pagaré para garantizar la devolución del equipo entregado SOLO en comodato.
          Visible en el anexo de la presente carátula y contrato de adhesión.

        </span>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Pago Unico/Mes :</label>
        <select class="form-select" aria-label="Default select example" id="tpago" name="tpago">
          <option value="1" selected>Pago Unico</option>
          <option value="2">Mes</option>
          <option value="3">Vacio</option>
        </select>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Cantidad a Pagar por Equipo</label>
        <input type="number" class="form-control requerido" id="cequipos" name="cequipos" value="0" min=0 required>
      </div>
      <div class="col-md-12 centrar space">
        <span class="titulo">Instalacion de Equipo</span>
      </div>
      <div class="col-md-6">
        <label for="inputPassword4" class="form-label">Domicilio de la Instalacion</label>
        <input type="text" class="form-control requerido" id="domicilioi" name="domicilioi" required>
      </div>
      <div class="col-md-3">
        <label for="inputPassword4" class="form-label">Fecha</label>
        <input type="date" class="form-control requerido" id="fechai" name="fechai" required>
      </div>
      <div class="col-md-3">
        <label for="inputPassword4" class="form-label">Hora</label>
        <input type="time" class="form-control requerido" id="horai" name="horai" required>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Costo</label>
        <input type="text" class="form-control requerido" id="costoi" name="costoi" placeholder="$" value="1300"
          required>
      </div>
      <div class="col-12 aviso text-center">
        <span>
          EL PROVEEDOR" deberá efectuar las instalaciones y empezar a prestar el servicio en un plazo que no exceda de
          10 días hábiles a partir de la firma del contrato

        </span>
      </div>
      <div class="col-md-12 centrar space">
        <span class="titulo">Metodo de Pago</span>
      </div>

      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Autorizacion por cargo a tarjeta</label>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="acargo" id="flexRadioDefault1" value="si">
          <label class="form-check-label" for="flexRadioDefault1">
            Si
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="acargo" id="flexRadioDefault2" value="no" checked>
          <label class="form-check-label" for="flexRadioDefault2">
            No
          </label>
        </div>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Metodo de Pago</label>
        <select class="form-select" aria-label="Default select example" id="mpago" name="mpago">
          <option value="1" selected>Efectivo</option>
          <option value="2">Tarjeta de credito/debito</option>
          <option value="3">Transferencia Bancaria</option>
          <option value="4">Deposito a cuenta bancaria</option>
        </select>
      </div>

      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Vigencia de Cargos/Mes</label>
        <input type="number" class="form-control" id="cmes" name="cmes" value="12" min="1">
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Banco</label>
        <input type="text" class="form-control" id="banco" name="banco" disabled value="">
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">No de Tarjeta</label>
        <input type="text" class="form-control" id="ntarjeta" name="ntarjeta" disabled maxlength="16" value="">
      </div>

      <div class="col-md-12 centrar space">
        <span class="titulo">Servicios Adicionales</span>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Servicio Adicional 1</label>
        <input type="text" class="form-control" id="sadicional1" name="sadicional1" value="">
      </div>
      <div class="col-md-8">
        <label for="inputPassword4" class="form-label">Descripcion</label>
        <input type="text" class="form-control" id="sdescripcion1" name="sdescripcion1" value="">
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Costo</label>
        <input type="number" class="form-control" id="scosto1" name="scosto1" value="">
      </div>
      <div class="col-8"></div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Servicio Adicional 2</label>
        <input type="text" class="form-control" id="sadicional2" name="sadicional2" value="">
      </div>
      <div class="col-md-8">
        <label for="inputPassword4" class="form-label">Descripcion</label>
        <input type="text" class="form-control" id="sdescripcion2" name="sdescripcion2" value="">
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Costo</label>
        <input type="number" class="form-control" id="scosto2" name="scosto2" value="">
      </div>
      <div class="col-8"></div>
      <div class="col-md-12 centrar space">
        <span>Facturables (Ejemplo: Costo por cambio de domicilio, Costos administrativos adicionales)</span>
      </div>

      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Facturable 1</label>
        <input type="text" class="form-control" id="fadicional1" name="fadicional1" value="">
      </div>
      <div class="col-md-8">
        <label for="inputPassword4" class="form-label">Descripcion</label>
        <input type="text" class="form-control" id="fdescripcion1" name="fdescripcion1" value="">
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Costo</label>
        <input type="number" class="form-control" id="fcosto1" name="fcosto1" value="">
      </div>
      <div class="col-8"></div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Facturable 2</label>
        <input type="text" class="form-control" id="fadicional2" name="fadicional2" value="">
      </div>
      <div class="col-md-8">
        <label for="inputPassword4" class="form-label">Descripcion</label>
        <input type="text" class="form-control" id="fdescripcion2" name="fdescripcion2" value="">
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Costo</label>
        <input type="number" class="form-control" id="fcosto2" name="fcosto2" value="">
      </div>
      <div class="col-8"></div>

      <div class="col-md-4 space">
        <label class="form-label space">Recepcion de Documentos</label>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="ccontrato" name="ccontrato">
          <label class="form-check-label" for="ccontrato">
            Copia de contrato y caratula
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" value="" id="cdminimos" name="cdminimos">
          <label class="form-check-label" for="cdminimos">
            Carta de derechos minimos
          </label>
        </div>
      </div>
    </div>
    <div class="row">
      <!-- <iframe src="files/pdf.pdf" width="100%" height="600px" frameborder="0" id="theFrame"></iframe>
        <p>Your web browser doesn't have a PDF plugin.
            Instead, you can <a href="files/pdf.pdf">click here to download the PDF file.</a>
        </p> -->
      <!-- <embed src="files/pdf.pdf" type="application/pdf" width="800" height="600"> -->
      <div class="paginas">
        <img src="img/sh-pdf/0001.jpg" alt="" width="100%">
        <img src="img/sh-pdf/0002.jpg" alt="" width="100%">
        <img src="img/sh-pdf/0005.jpg" alt="" width="100%">
        <img src="img/sh-pdf/0006.jpg" alt="" width="100%">
        <img src="img/sh-pdf/0007.jpg" alt="" width="100%">
        <img src="img/sh-pdf/0008.jpg" alt="" width="100%">
        <img src="img/sh-pdf/0009.jpg" alt="" width="100%">
        <img src="img/sh-pdf/0010.jpg" alt="" width="100%">
        <img src="img/sh-pdf/0011.jpg" alt="" width="100%">
      </div>
      <div class="form-check check-c">
        <input class="form-check-input" type="checkbox" value="" id="check-c" required>
        <label class="form-check-label" for="check-c">
          He leido y estoy de acuerdo con lo especificado en el contrato anterior.
        </label>
      </div>
    </div>
    <div class="row">

      <div class="col-12 centrar espacio text-center">
        <span>LA PRESENTE CARÁTULA SE RIGE CONFORME A LAS CLÁUSULAS DEL CONTRATO DE ADHESIÓN REGISTRADO EN PROFECO EL
          --/--/2023, CON NÚMERO:---/2023 DISPONIBLE EN EL SIGUIENTE CÓDIGO:

          LAS FIRMAS INSERTAS SON LA ACEPTACIÓN DE LA PRESENTA CARÁTULA Y CLAUSULADO DEL CONTRATO DE ADHESIÓN CON NÚMERO
          <span id="id"></span>
        </span>
      </div>
      <div class="col-md-4">
        <label for="inputPassword4" class="form-label">Ciudad</label>
        <input type="text" class="form-control requerido" id="ciudad" name="ciudad" required>
      </div>
      <div class="col-12 col-md-12 espacio">
        <span>Firma del Cliente</span>
        <div class="signature" style="border: 1px solid black; width: 100%; height: 200px;">
          <canvas id="signature-canvas" style=" width: 100%;height: 198px;"></canvas>
        </div>
      </div>
      <div class="col-2 space">
        <button type="button" class="btn btn-secondary" onclick="signaturePad.clear()">Limpiar</button>
      </div>
      <div class="col-12 centrar espacio text-center">
        <span>ANEXO 1 DEL CONTRATO DE PRESTACIÓN DE SERVICIOS DE INTERNET FIJO EN CASA -EL "CONTRATO", QUE CELEBRAN POR
          UNA PARTE TEKNE SEND.4, S. DE R.L. DE C.V., A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ "EL PROVEEDOR",
          REPRESENTADA EN ESTE ACTO POR SU APODERADO LEGAL Y POR LA OTRA PARTE LA PERSONA CUYO NOMBRE Y DIRECCIÓN QUEDAN
          ASENTADOS EN LA CARÁTULA DEL CONTRATO, A QUIEN EN LO SUCESIVO SE LE DENOMINARÁ "EL SUSCRIPTOR".
        </span>
      </div>
      <div class="col-12 centrar espacio pagare pagare-sign" id="pagare">
        <span><span>PAGARÉ</span><br><span>BUENO POR $3,000 (tres mil pesos 00/100 M.N.)</span><br><span>Debo y pagaré
            incondicionalmente por este pagaré, a la orden de TEKNE SEND.4, S. DE R.L. DE C.V. en, Guanajuato, la
            cantidad de $3,000 (tres mil pesos 00/100 M.N.) por cada equipo que se haya entregado en comodato y no haya
            sido devuelto, una vez terminada la relación contractual del presente contrato que fue celebrada con fecha
            <span id="fecha"></span>.

            (Este pagaré únicamente podrá cobrarse por las causas establecidas en el presente contrato de adhesión).

            Este pagaré se suscribe en la ciudad de <span id="ciudad">Uriangato</span>, Guanajuato, el día <span
              id="dia"></span> de <span id="mes"></span> de <span id="year"></span>
          </span>
        </span>
      </div>
      <div class="col-12 col-md-12 espacio pagare-sign">
        <span>Firma del cliente</span>
        <div class="signature" style="border: 1px solid black; width: 100%; height: 200px;">
          <canvas id="signature-canvas2" style=" width: 100%;height: 198px;"></canvas>
        </div>
      </div>
      <div class="col-6 col-lg-2 space pagare-sign">
        <button type="button" class="btn btn-secondary" onclick="signaturePad2.clear()">Limpiar</button>
      </div>
      <div class="col-12 space">
      <div>
        <label for="formFileLg" class="form-label">Ingresa la identificación del cliente.</label>
        <input class="form-control form-control-lg" id="evidencia" type="file">
        </div>
      </div>  
      <div class="col-6 col-lg-2 space">
        <button type="submit" class="btn btn-primary">Generar PDF</button>
      </div>
      <div class="col-12 col-lg-8 centrar resultado" id="resultado"></div>
    </div>
  </form>
  <div id="datos">

  </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </div>
  </div>

  <script src="js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="./js/app.js"></script>
  <script src="https://momentjs.com/downloads/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/bootstrapval.js"></script>
  <script src="js/booststraptoogletips.js"></script>
  <script src="js/id-check.js"></script>
  <script src="js/sidebar.js"></script>
  <script>
    $('.crear').hide(); 
    $('.lista').show(); 
    date = moment().format('YYYY-MM-DD');
    date = date.toString();
    document.getElementById('fechac').value = date;

    document.querySelector("#fecha").innerHTML = moment().format("DD/MM/yyyy");
    document.querySelector("#dia").innerHTML = moment().format("DD");
    document.querySelector("#mes").innerHTML = moment().format("MM");
    document.querySelector("#year").innerHTML = moment().format("yyyy");

    const modem = document.getElementById("modemt");
    const pagare = document.querySelectorAll(".pagare-sign");


    modem.addEventListener("change", (event) => {
      // console.log(modem.value);
      if (modem.value == 2) {
        // console.log(modem.value);
        for (var i = 0; i < pagare.length; i++) {
          pagare[i].classList.add('d-none');
          //console.log(pagare[i]);
        }

      } else {
        for (var i = 0; i < pagare.length; i++) {
          pagare[i].classList.remove('d-none');
          //console.log(pagare[i]);
        }

      }
      // console.log('cambio');

    });

    function cambioCiudad() {
      let municipio = document.getElementById("municipio");


      document.querySelector("#ciudad").innerHTML = municipio.value;


    }
  </script>
</body>

</html>