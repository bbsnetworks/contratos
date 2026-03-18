<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__ . '/conexion.php';

$raw = file_get_contents("php://input");
$body = json_decode($raw, true);
$filtro = $body['filtro'] ?? 'pendientes';

$where = "";
switch ($filtro) {
  case 'pendientes':
    // clientes sin contrato todavía
    $where = "co.idcontrato IS NULL";
    break;
  case 'legado':
    // ya existe contrato pero marcado como legado
    $where = "co.idcontrato IS NOT NULL AND co.es_legado = 1";
    break;
  case 'cancelados':
    $where = "co.idcontrato IS NOT NULL AND co.es_legado = 1 AND co.status = 'cancelado'";
    break;
  case 'todos':
  default:
    $where = "(co.idcontrato IS NULL OR co.es_legado = 1)";
    break;
}

$sql = "
  SELECT
    c.idcliente,
    c.nombre,
    c.direccion,
    c.localidad,
    c.estado AS estado_cliente,
    c.telefono,
    c.email,
    c.paquete,
    c.mensualidad,
    co.status AS status_contrato,
    co.es_legado,
    co.folio_cancelacion,
    co.fecha_cancelacion
  FROM clientes c
  LEFT JOIN contratos co
    ON co.idcontrato = c.idcliente
  WHERE $where
  ORDER BY c.idcliente DESC
";

$res = $conexion->query($sql);
if (!$res) {
  echo "<div class='alert alert-danger'>Error SQL: " . htmlspecialchars($conexion->error) . "</div>";
  exit;
}

echo '<table id="tablaResagados" class="display w-100">';
echo '<thead>
  <tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Teléfono</th>
    <th>Paquete</th>
    <th>Mensualidad</th>
    <th>Estado</th>
    <th>Tipo</th>
    <th>Acciones</th>
  </tr>
</thead><tbody>';

while ($row = $res->fetch_assoc()) {
  $id = (int)$row['idcliente'];

  $status = $row['status_contrato'] ?? 'sin_contrato';
  $tipo = ($row['es_legado'] ?? 0) == 1 ? "LEGADO" : "PENDIENTE";

  $badge = "secondary";
  if ($status === 'activo') $badge = "success";
  if ($status === 'cancelado') $badge = "danger";
  if ($status === 'pausado') $badge = "warning";
  if ($status === 'sin_contrato') $badge = "secondary";

  echo "<tr>";
  echo "<td>{$id}</td>";
  echo "<td>" . htmlspecialchars($row['nombre'] ?? '') . "</td>";
  echo "<td>" . htmlspecialchars($row['telefono'] ?? '') . "</td>";
  echo "<td>" . htmlspecialchars($row['paquete'] ?? '') . "</td>";
  echo "<td>$" . htmlspecialchars((string)($row['mensualidad'] ?? '0')) . "</td>";
  echo "<td><span class='badge text-bg-{$badge}'>" . htmlspecialchars($status) . "</span></td>";
  echo "<td><span class='badge text-bg-info'>" . htmlspecialchars($tipo) . "</span></td>";

  // Acciones:
  // - Cancelar: llama a tu flujo actual de cancelar (cancelaciones.js)
  //   (más adelante ajustamos cancelar_contrato.php para que si NO existe contrato -> cree legado automáticamente)
  $btnCancel = "<button class='btn btn-sm btn-danger' onclick='cancelarResagado({$id})'>
                  <img src='../img/error.png' height='15px' width='30px'> Cancelar
                </button>";

  echo "<td class='d-flex gap-2'>{$btnCancel}</td>";
  echo "</tr>";
}

echo "</tbody></table>";