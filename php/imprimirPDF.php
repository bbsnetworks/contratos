<?php
include("conexion.php");

// Obtener el ID del contrato desde la solicitud POST
$id = $_POST['id'];

// Consulta a la base de datos
$sql = "SELECT * FROM contratos WHERE idcontrato = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$data = array();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc(); // Solo un resultado ya que se filtra por ID único
}

$stmt->close();
$conexion->close();

// Devolver los datos en formato JSON
echo json_encode($data);
?>
