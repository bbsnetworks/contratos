<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/conexion.php';

try {
    $result = $conexion->query("SELECT COALESCE(MAX(idcontrato), 0) + 1 AS siguiente FROM contratos");

    if (!$result) {
        throw new Exception('No se pudo obtener el siguiente número.');
    }

    $row = $result->fetch_assoc();

    echo json_encode([
        'ok' => true,
        'numero' => (int)$row['siguiente']
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'message' => $e->getMessage()
    ]);
}