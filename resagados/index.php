<!Doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contratos BBS</title>

  <link href="../css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="../css/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../css/generales.css">
  <link rel="stylesheet" href="../css/index.css">
  <link rel="stylesheet" href="../css/lista.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/2.1.4/css/dataTables.dataTables.min.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet" />
</head>

<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: ../../menu/login/index.php");
  exit();
}
?>

<body class="">
  <div class="container-fluid">
    <?php include("../includes/sidebar.php"); ?>

    <div class="row">
      <div class="col-12 centrar txt1">
        <span>Clientes Resagados</span>
      </div>

      <div class="col-12 d-flex justify-content-end gap-2 mb-3">
        <select id="filtro-resagados" class="form-select w-auto bg-dark text-white border-gray-700" onchange="cargarTablaResagados()">
          <option value="pendientes" selected>Pendientes (sin contrato)</option>
          <option value="legado">Legados (es_legado=1)</option>
          <option value="cancelados">Cancelados (legado)</option>
          <option value="todos">Todos</option>
        </select>
      </div>

      <div class="col-12 tabla" id="tabla-resagados"></div>
      <div class="respuesta" id="respuesta"></div>
    </div>
  </div>
  <script src="../js/jspdf.min.js"></script>
  <script src="../js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://momentjs.com/downloads/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.datatables.net/2.1.4/js/dataTables.min.js"></script>

  <!-- para que Cancelaciones.confirmarCancelacion funcione igual -->
  <script src="../js/cancelaciones.js"></script>

  <script src="../js/sidebar.js"></script>
  <script src="../js/swalConfig.js"></script>
  <script src="../js/resagados.js"></script>

  <script>
    // ajusta estas clases según tu sidebar (igual que en lista)
    $('.crear').show();
    $('.lista').hide();
  </script>
</body>
</html>