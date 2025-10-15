<?php
if (isset($_FILES['archivo']) && isset($_POST['numero'])) {
    $contraton = $_POST['numero'];
    $targetDir = "../evidencia/";
    $fileExtension = pathinfo($_FILES['archivo']['name'], PATHINFO_EXTENSION); // Obtén la extensión del archivo
    $targetFile = $targetDir . "evidencia" . $contraton . "." . $fileExtension; // Construye el nombre del archivo

    if (move_uploaded_file($_FILES['archivo']['tmp_name'], $targetFile)) {
        //echo "Archivo guardado exitosamente.";
    } else {
        echo "Hubo un error al guardar el archivo.";
    }
} else {
    echo "No se recibió el archivo o el ID de contrato.";
}
?>
