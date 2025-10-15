<?php
require_once "conexion.php";
header('Content-Type: application/json');

// Recibir datos
$input = json_decode(file_get_contents("php://input"), true);
$id = isset($input['id']) ? (int)$input['id'] : 0;
$equipos = trim($input['equipos'] ?? "");

if ($id <= 0) {
    echo json_encode(['ok' => false, 'message' => 'ID de contrato inválido']);
    exit;
}

if ($equipos === "") {
    echo json_encode(['ok' => false, 'message' => 'Debes ingresar los equipos devueltos']);
    exit;
}

// Ejecutar UPDATE
$stmt = $conexion->prepare("
    UPDATE contratos 
    SET status='cancelado', 
        equipos_devueltos=?, 
        fecha_cancelacion=NOW() 
    WHERE idcontrato=?
");
$stmt->bind_param("si", $equipos, $id);


if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'message' => 'Contrato cancelado correctamente']);
} else {
    echo json_encode(['ok' => false, 'message' => 'Error al cancelar: ' . $stmt->error]);
}

$stmt->close();
$conexion->close();
