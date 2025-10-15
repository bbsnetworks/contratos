<?php
include('conexion.php');

if ($conexion->connect_error) {
    die('Conexión fallida: ' . $conexion->connect_error);
}
$idcontrato=$_POST['id'];
  //consultas
    $sql = "select * from contratos where idcontrato=".$idcontrato;
    $sql2 = "select * from nodos";
    $sql3 = "select * from localidad";
    $result = $conexion->query($sql);
    $result2 = $conexion->query($sql2);
    $result3 = $conexion->query($sql3);
    

  if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
      $name= $row["nombre"];
    }
  }
  
  echo "
<div class='row'>
  <form id='userForm'>
    <div class='col-4'>
      <label for='exampleInputEmail1' class='form-label'>ID:</label>
      <input type='text' class='form-control' id='id' aria-describedby='' name='id' disabled value='".$idcontrato."'>
    </div>
    <div class='col-8'>
      <label for='exampleInputEmail1' class='form-label'>Nombre:</label>
      <input type='text' class='form-control' id='nombre' aria-describedby='' name='nombre' disabled value='".$name."'>
    </div>
    <div class='col-lg-6'>
      <label for='exampleInputPassword1' class='form-label'>Localidad</label>
      <select id='localidad' class='form-select' aria-label='Default select example' name='localidad' required>
        <option value='' selected>Seleccionar Localidad</option>";

if ($result3->num_rows > 0) {
  while ($row = $result3->fetch_assoc()) {
    echo "<option value='" . htmlspecialchars($row['idlocalidad']) . "'>" . htmlspecialchars($row['nombrelocalidad']) . "</option>";
  }
} else {
  echo "<option>No hay nodos disponibles</option>";
}

echo "
      </select>
    </div>
    <div class='col-lg-6'>
      <label for='exampleInputPassword1' class='form-label'>Nodo</label>
      <select id='nodo' class='form-select' aria-label='Default select example' name='nodo' required>
        <option value='' selected>Seleccionar Nodo</option>";

if ($result2->num_rows > 0) {
  while ($row = $result2->fetch_assoc()) {
    echo "<option value='" . htmlspecialchars($row['idnodo']) . "'>" . htmlspecialchars($row['nombre']) . "</option>";
  }
} else {
  echo "<option>No hay nodos disponibles</option>";
}

echo "
      </select>
    </div>
    <div class='col-lg-6'>
      <label for='exampleInputPassword1' class='form-label'>IP</label>
      <input type='text' class='form-control' id='ip' name='ip' pattern='\\b(?:\\d{1,3}\\.){3}\\d{1,3}\\b' title='Por favor, ingrese una dirección IP válida (por ejemplo, 192.168.0.1)' required>
    </div>
    <div class='col-lg-6'>
      <label for='exampleInputPassword1' class='form-label'>Email</label>
      <input type='email' class='form-control' id='email' name='email' required>
    </div>
    <div class='col-lg-6'>
      <label for='exampleInputPassword1' class='form-label'>Splitter</label>
      <input type='text' class='form-control' id='splitter' name='splitter'>
    </div>
    <div class='col-2 mt-3'>
      <button type='button' class='btn btn-info' onclick='validateAndAddUsuario(".$idcontrato.")'>Agregar</button>
    </div>
  </form>
</div>
";



          ?>