<?php

    $idInput = $_POST['ncontrato'];
    // Conectar a la base de datos
    include_once("conexion.php");


    // Preparar la consulta para evitar inyecciones SQL
    $stmt = $conexion->prepare("SELECT COUNT(*) FROM clientes WHERE idcliente = ?");
    $stmt->bind_param("i", $idInput);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    $conexion->close();

    if ($count > 0) {
        echo "exists";
    } else {
        echo "available";
    }

?>
