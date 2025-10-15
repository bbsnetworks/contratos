<?php
include("conexion.php");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

$postdata = json_decode(file_get_contents("php://input"));
$estado = isset($postdata->estado) ? strtolower(trim($postdata->estado)) : 'activo';

// Filtro SQL
$filtro_estado = "";
if ($estado === 'activo' || $estado === 'cancelado' || $estado === 'pausado') {
    $filtro_estado = "WHERE status = '$estado'";
}

// Consulta (incluye fecha_cancelacion)
$sql = "
SELECT 
  idcontrato,
  nombre,
  CONCAT(calle, ' ', numero, ', ', colonia, ', ', municipio) AS direccion,
  fecha,
  fecha_cancelacion,
  tarifa,
  status
FROM contratos
$filtro_estado
ORDER BY fecha DESC
";
$result = $conexion->query($sql);

if ($result && $result->num_rows > 0) {
    echo "<table id='contratos-table' class='table table-dark table-striped dark'>";
    echo "<thead><tr>";
    echo "<th>Creado</th>";
    echo "<th>ID</th>";
    echo "<th>Nombre</th>";
    echo "<th>Dirección</th>";
    echo "<th>Fecha</th>";
    echo "<th>Status</th>";

    // Columna dinámica: Paquete o Fecha de cancelación
    if ($estado === 'cancelado') {
        echo "<th>Fecha de cancelación</th>";
    } else {
        echo "<th>Paquete</th>";
    }

    echo "<th>Editar</th>";
    echo "<th>Descargar</th>";
    echo "<th>Crear</th>";

    // Mostrar columna de acción solo si NO es 'todos'
    if ($estado === 'activo') {
        echo "<th>Cancelar</th>";
    } elseif ($estado === 'cancelado') {
        echo "<th>Reactivar</th>";
    }
    echo "</tr></thead><tbody>";

    while($row = $result->fetch_assoc()) {
        $idc   = (int)$row['idcontrato'];
        $nombre = htmlspecialchars($row['nombre'] ?? '', ENT_QUOTES, 'UTF-8');
        $dir    = htmlspecialchars($row['direccion'] ?? '', ENT_QUOTES, 'UTF-8');
        $fecha  = htmlspecialchars($row['fecha'] ?? '', ENT_QUOTES, 'UTF-8');
        $fcanc  = htmlspecialchars($row['fecha_cancelacion'] ?? '', ENT_QUOTES, 'UTF-8');
        $status = strtolower($row['status'] ?? '');
        $tarifa = htmlspecialchars($row['tarifa'] ?? '', ENT_QUOTES, 'UTF-8');

        // ¿Existe cliente creado?
        $sql2= "SELECT CASE 
                    WHEN EXISTS (
                        SELECT 1 
                        FROM contratos c
                        JOIN clientes cl ON c.idcontrato = cl.idcliente
                        WHERE c.idcontrato = $idc
                    ) THEN TRUE
                    ELSE FALSE
                END AS usuario_creado;";
        $result2 = $conexion->query($sql2);
        $usuario_creado = 0;
        if ($result2 && $row2 = $result2->fetch_assoc()) {
            $usuario_creado = (int)$row2['usuario_creado'];
        }

        echo "<tr>";

        // Columna 'Creado' con ícono
        if ($status === "cancelado") {
            echo "<td><i class='fa-solid fa-ban text-danger text-3xl' title='Contrato cancelado'></i></td>";
        } elseif ($usuario_creado === 1) {
            echo "<td><i class='fa-solid fa-circle-check text-success text-3xl' title='Cliente creado'></i></td>";
        } else {
            echo "<td><i class='fa-solid fa-circle-exclamation text-warning text-3xl' title='Cliente no creado'></i></td>";
        }

        echo "<td>{$idc}</td>";
        echo "<td>{$nombre}</td>";
        echo "<td>{$dir}</td>";
        echo "<td>{$fecha}</td>";

        // Status coloreado
        if ($status === "activo") {
            echo "<td><span class='text-success fw-bold'>Activo</span></td>";
        } elseif ($status === "cancelado") {
            echo "<td><span class='text-danger fw-bold'>Cancelado</span></td>";
        } elseif ($status === "pausado") {
            echo "<td><span class='text-warning fw-bold'>Pausado</span></td>";
        } else {
            echo "<td>{$status}</td>";
        }

        // Columna dinámica: Paquete o Fecha de cancelación
        if ($estado === 'cancelado') {
            $fcanc_fmt = $fcanc ? date('Y-m-d H:i', strtotime($fcanc)) : '—';
            echo "<td>{$fcanc_fmt}</td>";
        } else {
            $paqueteTxt = '';
            switch($tarifa){
                case "1": $paqueteTxt = "Residencial 7 MB/s"; break;
                case "2": $paqueteTxt = "Residencial 10 MB/s"; break;
                case "3": $paqueteTxt = "Residencial 15 MB/s"; break;
                case "4": $paqueteTxt = "Residencial 20 MB/s"; break;
                case "5": $paqueteTxt = "Residencial 40 MB/s"; break;
                case "6": $paqueteTxt = "Residencial 50 MB/s"; break;
                case "7": $paqueteTxt = "Residencial 30 MB/s"; break;
                case "8": $paqueteTxt = "Residencial 80 MB/s"; break;
                default:  $paqueteTxt = $tarifa;
            }
            echo "<td>{$paqueteTxt}</td>";
        }

        // Botones comunes
        echo "<td><button class='btn btn-warning' data-bs-toggle='modal' data-bs-target='#modalEditar' onclick=\"editContract({$idc})\"><img src='../img/editar.png' height='20px'/></button></td>";
        echo "<td><button class='btn btn-success' onclick=\"descargarContrato({$idc})\"><img src='../img/descargas.png' height='20px'/></button></td>";
        echo "<td><button class='btn btn-info' data-bs-toggle='modal' data-bs-target='#modalAgregar' onclick=\"addContract({$idc})\"><img src='../img/crear.png' height='20px'/></button></td>";

        // Acción dinámica (solo en activo/cancelado)
        if ($estado === 'activo') {
            echo "<td><button class='btn btn-danger' onclick=\"confirmarCancelacion({$idc})\"><img src='../img/error.png' height='20px'/></button></td>";
        } elseif ($estado === 'cancelado') {
            echo "<td><button class='btn btn-success' onclick=\"confirmarReactivacion({$idc})\"><img src='../img/check.png' height='20px'/></button></td>";
        }

        echo "</tr>";
    }

    echo "</tbody></table>";
} else {
    echo "0 resultados";
}

$conexion->close();

// Inicializa DataTable
echo("<script>$('#contratos-table').DataTable();</script>");
