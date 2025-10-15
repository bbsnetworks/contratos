<!Doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Contratos BBS</title>
  <script src="../js/jspdf.min.js"></script>
  <script src="../js/signature_pad.umd.min.js"></script>
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

//echo "Bienvenido, " . $_SESSION['username'];
?>
<body class="">

  <div class="container-fluid">
    <?php
    include("../includes/sidebar.php");

    ?>
    <div class="row">
      <div class="col-12 centrar txt1">
        <span>Lista de Contratos</span>
      </div>
      <div class="col-12 d-flex justify-content-end mb-3">
  <select id="filtro-estado" class="form-select w-auto bg-dark text-white border-gray-700" onchange="cargarTabla()">
    <option value="activo" selected>Activos</option>
    <option value="cancelado">Cancelados</option>
    <option value="todos">Todos</option>
  </select>
</div>

      <div class="col-12 tabla" id="tabla">

      </div>
      <div class="respuesta" id="respuesta"></div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade modal-xl" id="modalAgregar" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Agregar Cliente</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modal">
          
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade modal-xl" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Editar Contrato</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modal2">
          
        </div>
      </div>
    </div>
  </div>

  <script src="../js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://momentjs.com/downloads/moment.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://cdn.datatables.net/2.1.4/js/dataTables.min.js"></script>
  <script src="../js/bootstrapval.js"></script>
  <script src="../js/booststraptoogletips.js"></script>
  <script src="../js/lista.js"></script>
  <script src="../js/sidebar.js"></script>
  <script src="../js/swalConfig.js"></script>
  <script>
    $('.crear').show(); 
    $('.lista').hide(); 
  </script>
</body>

</html>