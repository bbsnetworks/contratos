<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexion.php';

try {
    if (!isset($_POST['ncontrato']) || $_POST['ncontrato'] === '') {
        throw new Exception('Número de contrato no recibido.');
    }

    $idInput = (int) $_POST['ncontrato'];

    $stmt = $conexion->prepare("SELECT COUNT(*) FROM clientes WHERE idcliente = ?");
    $stmt->bind_param("i", $idInput);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    echo json_encode([
        'ok' => true,
        'exists' => $count > 0
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'message' => $e->getMessage()
    ]);
}