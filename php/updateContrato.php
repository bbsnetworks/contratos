<?php
include("conexion.php");

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

// Normaliza checkboxes
$ccontrato = !empty($_POST['ccontrato']) ? "1" : "0";
$cdminimos = !empty($_POST['cdminimos']) ? "1" : "0";

// Escapar strings (rápido y compatible con tu estilo actual)
$nombre       = $conexion->real_escape_string($_POST['nombre'] ?? '');
$rlegal       = $conexion->real_escape_string($_POST['rlegal'] ?? '');
$calle        = $conexion->real_escape_string($_POST['calle'] ?? '');
$numero       = $conexion->real_escape_string($_POST['numero'] ?? '');
$colonia      = $conexion->real_escape_string($_POST['colonia'] ?? '');
$municipio    = $conexion->real_escape_string($_POST['municipio'] ?? '');
$cp           = $conexion->real_escape_string($_POST['cp'] ?? '');
$estado       = $conexion->real_escape_string($_POST['estado'] ?? '');
$telefono     = $conexion->real_escape_string($_POST['telefono'] ?? '');
$ttipo        = $conexion->real_escape_string($_POST['ttipo'] ?? '');
$rfc          = $conexion->real_escape_string($_POST['rfc'] ?? '');
$fechac       = $conexion->real_escape_string($_POST['fechac'] ?? '');
$tarifa       = $conexion->real_escape_string($_POST['tarifa'] ?? '');
$total        = $conexion->real_escape_string($_POST['total'] ?? '0');
$reconexion   = $conexion->real_escape_string($_POST['reconexion'] ?? '');
$mdesco       = $conexion->real_escape_string($_POST['mdesco'] ?? '0');
$plazo        = $conexion->real_escape_string($_POST['plazo'] ?? '0');
$modemt       = $conexion->real_escape_string($_POST['modemt'] ?? '');
$marca        = $conexion->real_escape_string($_POST['marca'] ?? '');
$modelo       = $conexion->real_escape_string($_POST['modelo'] ?? '');
$serie        = $conexion->real_escape_string($_POST['serie'] ?? '');
$nequipos     = $conexion->real_escape_string($_POST['nequipos'] ?? '0');
$tpago        = $conexion->real_escape_string($_POST['tpago'] ?? '');
$cequipos     = $conexion->real_escape_string($_POST['cequipos'] ?? '0');
$domicilioi   = $conexion->real_escape_string($_POST['domicilioi'] ?? '');
$fechai       = $conexion->real_escape_string($_POST['fechai'] ?? '');
$horai        = $conexion->real_escape_string($_POST['horai'] ?? '');
$costoi       = $conexion->real_escape_string($_POST['costoi'] ?? '');
$acargo       = $conexion->real_escape_string($_POST['acargo'] ?? '');
$mpago        = $conexion->real_escape_string($_POST['mpago'] ?? '');
$cmes         = $conexion->real_escape_string($_POST['cmes'] ?? '0');
$banco        = $conexion->real_escape_string($_POST['banco'] ?? '');
$ntarjeta     = $conexion->real_escape_string($_POST['ntarjeta'] ?? '');
$sadicional1  = $conexion->real_escape_string($_POST['sadicional1'] ?? '');
$sdescripcion1= $conexion->real_escape_string($_POST['sdescripcion1'] ?? '');
$scosto1      = $conexion->real_escape_string($_POST['scosto1'] ?? '0');
$sadicional2  = $conexion->real_escape_string($_POST['sadicional2'] ?? '');
$sdescripcion2= $conexion->real_escape_string($_POST['sdescripcion2'] ?? '');
$scosto2      = $conexion->real_escape_string($_POST['scosto2'] ?? '0');
$fadicional1  = $conexion->real_escape_string($_POST['fadicional1'] ?? '');
$fdescripcion1= $conexion->real_escape_string($_POST['fdescripcion1'] ?? '');
$fcosto1      = $conexion->real_escape_string($_POST['fcosto1'] ?? '0');
$fadicional2  = $conexion->real_escape_string($_POST['fadicional2'] ?? '');
$fdescripcion2= $conexion->real_escape_string($_POST['fdescripcion2'] ?? '');
$fcosto2      = $conexion->real_escape_string($_POST['fcosto2'] ?? '0');
$ciudad       = $conexion->real_escape_string($_POST['ciudad'] ?? '');
$idcontrato   = (int)($_POST['ncontrato'] ?? 0);

// NUEVOS CAMPOS
$equipos_devueltos = $conexion->real_escape_string($_POST['equipos_devueltos'] ?? '');

// fecha_cancelacion (viene de input datetime-local: 'YYYY-MM-DDTHH:MM')
$fecha_cancelacion = $_POST['fecha_cancelacion'] ?? '';
$fecha_cancelacion_sql = "NULL";
if ($fecha_cancelacion !== '') {
    // Normaliza a 'YYYY-MM-DD HH:MM:SS'
    $fecha_cancelacion_norm = str_replace('T', ' ', $fecha_cancelacion);
    if (strlen($fecha_cancelacion_norm) === 16) { // sin segundos
        $fecha_cancelacion_norm .= ':00';
    }
    $fecha_cancelacion_sql = "'" . $conexion->real_escape_string($fecha_cancelacion_norm) . "'";
}

$query = "
UPDATE contratos SET
    nombre = '$nombre',
    rlegal = '$rlegal',
    calle = '$calle',
    numero = '$numero',
    colonia = '$colonia',
    municipio = '$municipio',
    cp = '$cp',
    estado = '$estado',
    telefono = '$telefono',
    ttelefono = '$ttipo',
    rfc = '$rfc',
    fecha = '$fechac',
    tarifa = '$tarifa',
    tmensualidad = $total,
    reconexion = '$reconexion',
    mdesconexion = $mdesco,
    plazo = $plazo,
    modeme = '$modemt',
    marca = '$marca',
    modelo = '$modelo',
    nserie = '$serie',
    nequipo = $nequipos,
    pagoum = '$tpago',
    pequipo = $cequipos,
    domicilioi = '$domicilioi',
    fechai = '$fechai',
    hora = '$horai',
    costoi = '$costoi',
    autorizacion = '$acargo',
    mpago = '$mpago',
    vigencia = '$cmes',
    banco = '$banco',
    notarjeta = '$ntarjeta',
    sadicional1 = '$sadicional1',
    dadicional1 = '$sdescripcion1',
    costoa1 = '$scosto1',
    sadicional2 = '$sadicional2',
    dadicional2 = '$sdescripcion2',
    costoa2 = '$scosto2',
    sfacturable1 = '$fadicional1',
    dfacturable1 = '$fdescripcion1',
    costof1 = '$fcosto1',
    sfacturable2 = '$fadicional2',
    dfacturable2 = '$fdescripcion2',
    costof2 = '$fcosto2',
    ccontrato = $ccontrato,
    cderechos = $cdminimos,
    cciudad = '$ciudad',

    -- NUEVOS CAMPOS
    equipos_devueltos = '$equipos_devueltos',
    fecha_cancelacion = $fecha_cancelacion_sql

WHERE idcontrato = $idcontrato
";

// Ejecutar
if ($conexion->query($query) === TRUE) {
    $response = array("status" => "success", "message" => "Contrato actualizado correctamente.");
} else {
    $response = array("status" => "error", "message" => "Error al actualizar el contrato: " . $conexion->error);
}

$conexion->close();
echo json_encode($response);
