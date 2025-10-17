<?php
require_once "conexion.php";
header('Content-Type: application/json; charset=utf-8');
session_start();

try {
    if ($conexion->connect_error) {
        throw new Exception('Conexión fallida: '.$conexion->connect_error);
    }

    $input   = json_decode(file_get_contents("php://input"), true) ?? [];
    $id      = isset($input['id']) ? (int)$input['id'] : 0;
    $equipos = trim($input['equipos'] ?? "");
    $user    = $_SESSION['username'] ?? 'sistema';

    if ($id <= 0)        throw new Exception('ID de contrato inválido');
    if ($equipos === "") throw new Exception('Debes ingresar los equipos devueltos');

    // 1) Leemos si ya estaba cancelado y con folio
    $q = $conexion->prepare("SELECT status, folio_cancelacion, fecha_cancelacion, cancelado_por
                             FROM contratos WHERE idcontrato=?");
    $q->bind_param("i", $id);
    $q->execute();
    $row = $q->get_result()->fetch_assoc();
    $q->close();
    if (!$row) throw new Exception('Contrato no encontrado');

    $yaTieneFolio = !empty($row['folio_cancelacion']);

    // 2) Si NO hay folio, generamos uno nuevo (consecutivo diario)
    $folioBonito = $row['folio_cancelacion'] ?? null;

    $conexion->begin_transaction();

    if (!$yaTieneFolio) {
        // UPSERT que incrementa y pone el nuevo valor en LAST_INSERT_ID()
        $sqlSeq = "
            INSERT INTO folio_cancelacion_seq (fecha, consecutivo)
            VALUES (CURDATE(), 1)
            ON DUPLICATE KEY UPDATE consecutivo = LAST_INSERT_ID(consecutivo + 1)
        ";
        if (!$conexion->query($sqlSeq)) {
            throw new Exception('Error generando folio: '.$conexion->error);
        }
        $n = (int)$conexion->insert_id; // ← el nuevo consecutivo del día
        $folioBonito = sprintf('CNL-%s-%06d', date('Ymd'), $n);
    }

    // 3) Actualizamos el contrato (no pisamos si ya había valores)
    $now = date('Y-m-d H:i:s');
    $upd = $conexion->prepare("
        UPDATE contratos
        SET status='cancelado',
            equipos_devueltos=?,
            fecha_cancelacion = COALESCE(fecha_cancelacion, ?),
            cancelado_por     = COALESCE(cancelado_por, ?),
            folio_cancelacion = COALESCE(folio_cancelacion, ?)
        WHERE idcontrato=?
    ");
    $upd->bind_param("ssssi", $equipos, $now, $user, $folioBonito, $id);
    if (!$upd->execute()) {
        throw new Exception('Error al cancelar: '.$upd->error);
    }
    $upd->close();

    // 4) Devolvemos los datos completos para el PDF
    $sel = $conexion->prepare("
      SELECT idcontrato, status, fecha_cancelacion, equipos_devueltos, cancelado_por,
             folio_cancelacion, nombre, rfc, telefono, marca, modelo, nserie, nequipo,
             CONCAT(calle,' ',numero,', ',colonia,', ',municipio,', ',estado,' C.P. ',cp) AS direccion
      FROM contratos
      WHERE idcontrato=?
    ");
    $sel->bind_param("i", $id);
    $sel->execute();
    $res = $sel->get_result()->fetch_assoc();
    $sel->close();

    $conexion->commit();

    echo json_encode([
      'ok' => true,
      'message' => 'Contrato cancelado correctamente',
      'data' => [
        'idcontrato'        => (string)$res['idcontrato'],
        'status'            => $res['status'],
        'fecha_cancelacion' => $res['fecha_cancelacion'],
        'equipos_devueltos' => $res['equipos_devueltos'],
        'cancelado_por'     => $res['cancelado_por'] ?: $user,
        'folio_cancelacion' => $res['folio_cancelacion'], // ya viene "CNL-YYYYMMDD-######"
        'nombre'            => $res['nombre'],
        'rfc'               => $res['rfc'],
        'telefono'          => $res['telefono'],
        'direccion'         => $res['direccion'],
        'marca'             => $res['marca'],
        'modelo'            => $res['modelo'],
        'nserie'            => $res['nserie'],
        'nequipo'           => $res['nequipo']
      ]
    ]);
} catch (Exception $e) {
    if ($conexion && $conexion->errno === 0) { /* noop */ }
    if ($conexion instanceof mysqli) {
        // Si hay una transacción abierta, revertimos
        @ $conexion->rollback();
    }
    echo json_encode(['ok' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($conexion) && $conexion instanceof mysqli) { $conexion->close(); }
}
