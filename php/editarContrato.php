<?php
include('conexion.php');

$idcontrato = $_POST['id'];

if ($conexion->connect_error) {
    die('Conexión fallida: ' . $conexion->connect_error);
}

$sql = 'SELECT * from contratos where idcontrato='.(int)$idcontrato;
$result = $conexion->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {

        // ===== Valores normalizados para la sección de cancelación =====
        $fechaCancInput = '';
        if (!empty($row['fecha_cancelacion'])) {
            $fechaCancInput = date('Y-m-d\TH:i', strtotime($row['fecha_cancelacion']));
        }
        $equiposDevueltosVal = htmlspecialchars($row['equipos_devueltos'] ?? '', ENT_QUOTES, 'UTF-8');
        // =================================================================

        echo "
  <form class='row centrar needs-validation' id='form' novalidate>
    <div class='row'>
      <div class='col-12 encabezado'>
        <span>TEKNE SEND.4, S. DE R.L. DE C.V.<br>
          RFC: TSE230302694<br>
          DOMICILIO: EDUARDO ECHEVERRÍA, NÚMERO 21, INTERIOR B, LOCALIDAD MONTE DE LOS JUÁREZ, C.P. 38950, YURIRIA,
          GUANAJUATO.
        </span>
      </div>
      <!-- <div class='col-12 centrar tittle'><span>Primera Parte</span></div> -->
      <div class='col-md-4'>
        <label for='inputEmail4' class='form-label'>Contrato No</label>
        <input type='number' class='form-control requerido' id='ncontrato' name='ncontrato' value='".$row['idcontrato']."' novalidate disabled>
        <div id='error-message' style='color:red;'></div>
      </div>
      <div class='col-md-6 space centrar d-none' id='euser'>
        <div class='form-check' id='divContrato'>
          <input class='form-check-input' type='checkbox' id='scontrato' name='scontrato'>
          <label class='form-check-label' for='scontrato'>
            Estoy de acuerdo de sobreescribir el contrato ya existente
          </label>
        </div>
      </div>
      <div class='col-md-12 centrar space'>
        <span class='titulo'>Contacto del Cliente</span>
      </div>
      <div class='col-md-6'>
        <label for='inputEmail4' class='form-label'>NOMBRE/RAZÓN O DENOMINACIÓN SOCIAL</label>
        <input type='text' class='form-control requerido' id='name' name='name' value='".$row['nombre']."' required novalidate>
      </div>
      <div class='col-md-6'>
        <label for='inputPassword4' class='form-label'>REPRESENTANTE LEGAL</label>
        <input type='text' class='form-control' id='rlegal' name='rlegal' value='".$row['rlegal']."'>
      </div>
      <div class='col-md-4'>
        <label for='inputEmail4' class='form-label'>Calle</label>
        <input type='text' class='form-control requerido' id='street' name='calle' value='".$row['calle']."' required novalidate>
      </div>
      <div class='col-md-2'>
        <label for='inputPassword4' class='form-label'>Numero</label>
        <input type='text' class='form-control requerido' id='number' name='number' value='".$row['numero']."' required novalidate>
      </div>
      <div class='col-md-2'>
        <label for='inputPassword4' class='form-label'>Colonia</label>
        <input type='text' class='form-control requerido' id='colonia' name='colonia' value='".$row['colonia']."' required novalidate>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Municipio</label>
        <input type='text' class='form-control requerido' id='municipio' name='municipio' value='".$row['municipio']."' required novalidate
          onchange='cambioCiudad()'>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>CP</label>
        <input type='text' class='form-control' id='cp' name='cp' value='".$row['cp']."' novalidate minlength='4' maxlength='5'>
      </div>
      <div class='col-md-2'>
        <label for='inputPassword4' class='form-label'>Estado</label>
        <input type='text' class='form-control requerido' id='estado' name='estado' value='".$row['estado']."' required novalidate>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Telefono</label>
        <input type='text' class='form-control requerido' id='telefono' name='telefono' value='".$row['telefono']."' required novalidate
          minlength='10' maxlength='15'>
      </div>
      ";

        if($row['ttelefono']=="movil"){
            echo "<div class='col-md-2'>
        <label for='inputPassword4' class='form-label'>TELÉFONO MÓVIL/FIJO</label>
        <div class='form-check'>
        <input class='form-check-input' type='radio' name='ttipo' id='movil' value='movil' checked>
          <label class='form-check-label' for='flexRadioDefault1'>
            Fijo
          </label>
        </div>
        <div class='form-check'>
          <input class='form-check-input' type='radio' name='ttipo' id='fijo' value='fijo'>
          <label class='form-check-label' for='flexRadioDefault2'>
            Movil
          </label>
        </div>
      </div>";
        } else {
            echo "<div class='col-md-2'>
        <label for='inputPassword4' class='form-label'>TELÉFONO MÓVIL/FIJO</label>
        <div class='form-check'>
        <input class='form-check-input' type='radio' name='ttipo' id='movil' value='movil'>
          <label class='form-check-label' for='flexRadioDefault1'>
            Fijo
          </label>
        </div>
        <div class='form-check'>
          <input class='form-check-input' type='radio' name='ttipo' id='fijo' value='fijo' checked>
          <label class='form-check-label' for='flexRadioDefault2'>
            Movil
          </label>
        </div>
      </div>";
        }
          
      echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>RFC</label>
        <input type='text' class='form-control' id='rfc' name='rfc' value='".$row['rfc']."' maxlength='13'>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Fecha</label>
        <input type='date' class='form-control requerido' id='fechac' name='fechac' value='".$row['fecha']."' required>
      </div>

      <div class='col-md-12 centrar space txt-center'>
        <span class='titulo'>Servicio de Internet Fijo en Casa</span>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Tarifa:</label>";

        switch ($row["tarifa"]) {
            case "1":
        echo "<select class='form-select' aria-label='Default select example' name='tarifa' id='tarifa'>
          <option value='1' selected>Residencial 7 MB/s</option>
          <option value='2'>Residencial 10 MB/s</option>
          <option value='3'>Residencial 15 MB/s</option>
          <option value='4'>Residencial 20 MB/s</option>
          <option value='7'>Residencial 30 MB/s</option>
          <option value='5'>Residencial 40 MB/s</option>
          <option value='6'>Residencial 50 MB/s</option>
            </select>";
            break;
            case "2":
                echo "<select class='form-select' aria-label='Default select example' name='tarifa' id='tarifa'>
          <option value='1'>Residencial 7 MB/s</option>
          <option value='2' selected>Residencial 10 MB/s</option>
          <option value='3'>Residencial 15 MB/s</option>
          <option value='4'>Residencial 20 MB/s</option>
          <option value='7'>Residencial 30 MB/s</option>
          <option value='5'>Residencial 40 MB/s</option>
          <option value='6'>Residencial 50 MB/s</option>
            </select>";
            break;  
            case "3":
                echo "<select class='form-select' aria-label='Default select example' name='tarifa' id='tarifa'>
          <option value='1'>Residencial 7 MB/s</option>
          <option value='2'>Residencial 10 MB/s</option>
          <option value='3' selected>Residencial 15 MB/s</option>
          <option value='4'>Residencial 20 MB/s</option>
          <option value='7'>Residencial 30 MB/s</option>
          <option value='5'>Residencial 40 MB/s</option>
          <option value='6'>Residencial 50 MB/s</option>
            </select>";
            break;
            case "4":
            echo "<select class='form-select' aria-label='Default select example' name='tarifa' id='tarifa'>
          <option value='1'>Residencial 7 MB/s</option>
          <option value='2'>Residencial 10 MB/s</option>
          <option value='3'>Residencial 15 MB/s</option>
          <option value='4' selected>Residencial 20 MB/s</option>
          <option value='7'>Residencial 30 MB/s</option>
          <option value='5'>Residencial 40 MB/s</option>
          <option value='6'>Residencial 50 MB/s</option>
            </select>";
            break;    
            case "5":
            echo "<select class='form-select' aria-label='Default select example' name='tarifa' id='tarifa'>
          <option value='1'>Residencial 7 MB/s</option>
          <option value='2'>Residencial 10 MB/s</option>
          <option value='3'>Residencial 15 MB/s</option>
          <option value='4'>Residencial 20 MB/s</option>
          <option value='7'>Residencial 30 MB/s</option>
          <option value='5' selected>Residencial 40 MB/s</option>
          <option value='6'>Residencial 50 MB/s</option>
                        </select>";
            break;
            case "6":
              echo "<select class='form-select' aria-label='Default select example' name='tarifa' id='tarifa'>
            <option value='1'>Residencial 7 MB/s</option>
            <option value='2'>Residencial 10 MB/s</option>
            <option value='3'>Residencial 15 MB/s</option>
            <option value='4'>Residencial 20 MB/s</option>
            <option value='7'>Residencial 30 MB/s</option>
            <option value='5'>Residencial 40 MB/s</option>
            <option value='6' selected>Residencial 50 MB/s</option>
                          </select>";
              break;
              case "7":
                echo "<select class='form-select' aria-label='Default select example' name='tarifa' id='tarifa'>
              <option value='1'>Residencial 7 MB/s</option>
              <option value='2'>Residencial 10 MB/s</option>
              <option value='3'>Residencial 15 MB/s</option>
              <option value='4'>Residencial 20 MB/s</option>
              <option value='7' selected>Residencial 30 MB/s</option>
              <option value='5'>Residencial 40 MB/s</option>
              <option value='6'>Residencial 50 MB/s</option>
                            </select>";
                break;  
        }
        
      echo "</div>

      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Total Mensualidad:</label>
        <input type='text' class='form-control requerido' id='totalm' name='totalm' placeholder='$' value='".$row['tmensualidad']."'
          required>
      </div>";
      if($row['reconexion']== '1'){
        echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Aplica tarifa por reconexión:</label>
        <select class='form-select' aria-label='Default select example' id='reconexion' name='reconexion'>
          <option value='1' selected>No</option>
          <option value='2'>Si</option>
        </select>
      </div>";
      } else {
        echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Aplica tarifa por reconexión:</label>
        <select class='form-select' aria-label='Default select example' id='reconexion' name='reconexion'>
          <option value='1'>No</option>
          <option value='2' selected>Si</option>
        </select>
      </div>";
      }
      
      echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Monto por Desconexion:</label>
        <input type='text' class='form-control' id='descm' name='descm' disabled value='".$row['mdesconexion']."'>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Plazo mínimo en meses (0-12): <span data-bs-toggle='tooltip'
            data-bs-placement='top'
            data-bs-title='Pagando el costo remanente del equipo sin penalidad por el servicio'><i
              class='bi bi-question-circle'></i></span></label>
        <input type='number' class='form-control requerido' id='pmeses' name='pmeses' min='0' value='".$row['plazo']."' required>
      </div>
      <div class='col-12 aviso'>
        <span>(En el Estado de cuenta y/o factura se podrá visualizar la fecha de corte del servicio y fecha de
          pago.)</span>
      </div>
      <div class='col-md-12 centrar space'>
        <span class='titulo'>Datos del Equipo</span>
      </div>";

      if($row['modeme']== '1'){
        echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Modem Entregado</label>
        <select class='form-select' aria-label='Default select example' id='modemt' name='modemt'>
          <option value='1' selected>Comodato</option>
          <option value='2'>Compraventa</option>
        </select>
      </div>";
      } else {
        echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Modem Entregado</label>
        <select class='form-select' aria-label='Default select example' id='modemt' name='modemt'>
          <option value='1'>Comodato</option>
          <option value='2' selected>Compraventa</option>
        </select>
      </div>";
      }
      
      echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Marca</label>
        <input type='text' class='form-control requerido' id='marca' name='marca' value='".$row['marca']."' required>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Modelo</label>
        <input type='text' class='form-control requerido' id='modelo' name='modelo' value='".$row['modelo']."' required>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Numero de Serie</label>
        <input type='text' class='form-control requerido' id='serie' name='serie' value='".$row['nserie']."' required>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Numero de Equipos</label>
        <input type='number' class='form-control requerido' id='nequipos' name='nequipos' value='".$row['nequipo']."' required maxlength='50' min='1'>
      </div>
      <div class='col-12 aviso text-center'>
        <span>
          Garantía de cumplimiento de obligación<br>
          Pagaré para garantizar la devolución del equipo entregado SOLO en comodato.
          Visible en el anexo de la presente carátula y contrato de adhesión.

        </span>
      </div>";

      switch ($row["pagoum"]) {
        case "1":
            echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Pago Unico/Mes :</label>
        <select class='form-select' aria-label='Default select example' id='tpago' name='tpago'>
          <option value='1' selected>Pago Unico</option>
          <option value='2'>Mes</option>
          <option value='3'>Vacio</option>
        </select>
      </div>";
        break;
        case "2":
            echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Pago Unico/Mes :</label>
        <select class='form-select' aria-label='Default select example' id='tpago' name='tpago'>
          <option value='1'>Pago Unico</option>
          <option value='2' selected>Mes</option>
          <option value='3'>Vacio</option>
        </select>
      </div>";
        break;
         case "3":
            echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Pago Unico/Mes :</label>
        <select class='form-select' aria-label='Default select example' id='tpago' name='tpago'>
          <option value='1'>Pago Unico</option>
          <option value='2'>Mes</option>
          <option value='3' selected>Vacio</option>
        </select>
      </div>";
        break;
      }
     
      echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Cantidad a Pagar por Equipo</label>
        <input type='number' class='form-control' id='cequipos' name='cequipos' value='".$row['pequipo']."'>
      </div>
      <div class='col-md-12 centrar space'>
        <span class='titulo'>Instalacion de Equipo</span>
      </div>
      <div class='col-md-6'>
        <label for='inputPassword4' class='form-label'>Domicilio de la Instalacion</label>
        <input type='text' class='form-control requerido' id='domicilioi' name='domicilioi' value='".$row['domicilioi']."' required>
      </div>
      <div class='col-md-3'>
        <label for='inputPassword4' class='form-label'>Fecha</label>
        <input type='date' class='form-control requerido' id='fechai' name='fechai' value='".$row['fechai']."' required>
      </div>
      <div class='col-md-3'>
        <label for='inputPassword4' class='form-label'>Hora</label>
        <input type='time' class='form-control requerido' id='horai' name='horai' value='".$row['hora']."' required>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Costo</label>
        <input type='text' class='form-control requerido' id='costoi' name='costoi' placeholder='$' value='".$row['costoi']."' required>
      </div>
      <div class='col-12 aviso text-center'>
        <span>
          EL PROVEEDOR' deberá efectuar las instalaciones y empezar a prestar el servicio en un plazo que no exceda de
          10 días hábiles a partir de la firma del contrato

        </span>
      </div>
      <div class='col-md-12 centrar space'>
        <span class='titulo'>Metodo de Pago</span>
      </div>";

      if($row["autorizacion"]== "si"){
        echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Autorizacion por cargo a tarjeta</label>
        <div class='form-check'>
          <input class='form-check-input' type='radio' name='acargo' id='flexRadioDefault1' value='si' checked>
          <label class='form-check-label' for='flexRadioDefault1'>
            Si
          </label>
        </div>
        <div class='form-check'>
          <input class='form-check-input' type='radio' name='acargo' id='flexRadioDefault2' value='no'>
          <label class='form-check-label' for='flexRadioDefault2'>
            No
          </label>
        </div>
      </div>";
      } else {
        echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Autorizacion por cargo a tarjeta</label>
        <div class='form-check'>
          <input class='form-check-input' type='radio' name='acargo' id='flexRadioDefault1' value='si'>
          <label class='form-check-label' for='flexRadioDefault1'>
            Si
          </label>
        </div>
        <div class='form-check'>
          <input class='form-check-input' type='radio' name='acargo' id='flexRadioDefault2' value='no' checked>
          <label class='form-check-label' for='flexRadioDefault2'>
            No
          </label>
        </div>
      </div>";
      }

      switch ($row["mpago"]){
        case "1":
            echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Metodo de Pago</label>
        <select class='form-select' aria-label='Default select example' id='mpago' name='mpago'>
          <option value='1' selected>Efectivo</option>
          <option value='2'>Tarjeta de credito/debito</option>
          <option value='3'>Transferencia Bancaria</option>
          <option value='4'>Deposito a cuenta bancaria</option>
        </select>
      </div>";
            break;
            case "2":
                echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Metodo de Pago</label>
        <select class='form-select' aria-label='Default select example' id='mpago' name='mpago'>
          <option value='1'>Efectivo</option>
          <option value='2' selected>Tarjeta de credito/debito</option>
          <option value='3'>Transferencia Bancaria</option>
          <option value='4'>Deposito a cuenta bancaria</option>
        </select>
      </div>";
                break;
                case "3":
                    echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Metodo de Pago</label>
        <select class='form-select' aria-label='Default select example' id='mpago' name='mpago'>
          <option value='1'>Efectivo</option>
          <option value='2'>Tarjeta de credito/debito</option>
          <option value='3' selected>Transferencia Bancaria</option>
          <option value='4'>Deposito a cuenta bancaria</option>
        </select>
      </div>";
                    break;        
                    case "4":
                        echo "<div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Metodo de Pago</label>
        <select class='form-select' aria-label='Default select example' id='mpago' name='mpago'>
          <option value='1'>Efectivo</option>
          <option value='2'>Tarjeta de credito/debito</option>
          <option value='3'>Transferencia Bancaria</option>
          <option value='4' selected>Deposito a cuenta bancaria</option>
        </select>
      </div>";
                        break;        
         }
      echo "
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Vigencia de Cargos/Mes</label>
        <input type='number' class='form-control' id='cmes' name='cmes'  value='".$row['vigencia']."' min='1'>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Banco</label>
        <input type='text' class='form-control' id='banco' name='banco'  value='".$row['banco']."' disabled value=''>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>No de Tarjeta</label>
        <input type='text' class='form-control' id='ntarjeta' name='ntarjeta' disabled maxlength='16'  value='".$row['notarjeta']."'>
      </div>

      <div class='col-md-12 centrar space'>
        <span class='titulo'>Servicios Adicionales</span>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Servicio Adicional 1</label>
        <input type='text' class='form-control' id='sadicional1' name='sadicional1'  value='".$row['sadicional1']."'>
      </div>
      <div class='col-md-8'>
        <label for='inputPassword4' class='form-label'>Descripcion</label>
        <input type='text' class='form-control' id='sdescripcion1' name='sdescripcion1'  value='".$row['dadicional1']."'>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Costo</label>
        <input type='text' class='form-control' id='scosto1' name='scosto1'  value='".$row['costoa1']."'>
      </div>
      <div class='col-8'></div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Servicio Adicional 2</label>
        <input type='text' class='form-control' id='sadicional2' name='sadicional2'  value='".$row['sadicional2']."'>
      </div>
      <div class='col-md-8'>
        <label for='inputPassword4' class='form-label'>Descripcion</label>
        <input type='text' class='form-control' id='sdescripcion2' name='sdescripcion2'  value='".$row['dadicional2']."'>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Costo</label>
        <input type='text' class='form-control' id='scosto2' name='scosto2'  value='".$row['costoa2']."'>
      </div>
      <div class='col-8'></div>
      <div class='col-md-12 centrar space'>
        <span>Facturables (Ejemplo: Costo por cambio de domicilio, Costos administrativos adicionales)</span>
      </div>

      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Facturable 1</label>
        <input type='text' class='form-control' id='fadicional1' name='fadicional1'  value='".$row['sfacturable1']."'>
      </div>
      <div class='col-md-8'>
        <label for='inputPassword4' class='form-label'>Descripcion</label>
        <input type='text' class='form-control' id='fdescripcion1' name='fdescripcion1'  value='".$row['dfacturable1']."'>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Costo</label>
        <input type='text' class='form-control' id='fcosto1' name='fcosto1'  value='".$row['costof1']."'>
      </div>
      <div class='col-8'></div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Facturable 2</label>
        <input type='text' class='form-control' id='fadicional2' name='fadicional2'  value='".$row['sfacturable2']."'>
      </div>
      <div class='col-md-8'>
        <label for='inputPassword4' class='form-label'>Descripcion</label>
        <input type='text' class='form-control' id='fdescripcion2' name='fdescripcion2'  value='".$row['dfacturable2']."'>
      </div>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Costo</label>
        <input type='text' class='form-control' id='fcosto2' name='fcosto2'  value='".$row['costof2']."'>
      </div>
      <div class='col-8'></div>
      <label class='form-label space'>Recepcion de Documentos</label>
      <div class='col-md-4 space'>";
      
      if($row['ccontrato']== '0'){
            echo "
        <div class='form-check'>
          <input class='form-check-input' type='checkbox' value='' id='ccontrato' name='ccontrato'>
          <label class='form-check-label' for='ccontrato'>
            Copia de contrato y caratula
          </label>
        </div>
        
      </div>";
      } else {
        echo " <div class='form-check'>
          <input class='form-check-input' type='checkbox' value='' id='ccontrato' name='ccontrato' checked>
          <label class='form-check-label' for='ccontrato'>
            Copia de contrato y caratula
          </label>
        </div>";
      }

      if($row['ccontrato']== '0'){
            echo "<div class='form-check'>
          <input class='form-check-input' type='checkbox' value='' id='cdminimos' name='cdminimos'>
          <label class='form-check-label' for='cdminimos'>
            Carta de derechos minimos
          </label>
        </div>";
      } else {
        echo " <div class='form-check'>
          <input class='form-check-input' type='checkbox' value='' id='cdminimos' name='cdminimos' checked>
          <label class='form-check-label' for='cdminimos'>
            Carta de derechos minimos
          </label>
        </div>";
      }

    echo "</div>
    <div class='row'>
      <div class='col-md-4'>
        <label for='inputPassword4' class='form-label'>Ciudad</label>
        <input type='text' class='form-control requerido' id='ciudad' name='ciudad' value='".$row['cciudad']."' required>
      </div>

      <!-- ====== Sección Cancelación (opcional) ====== -->
      <div class='col-md-12 centrar space'>
        <span class='titulo'>Cancelación (opcional)</span>
      </div>

      <div class='col-md-8'>
        <label for='equipos_devueltos' class='form-label'>Equipos devueltos</label>
        <textarea class='form-control' id='equipos_devueltos' name='equipos_devueltos' rows='3'
          placeholder='Ej: ONT Huawei SN12345, Router TP-Link SN67890...'>".$equiposDevueltosVal."</textarea>
        <div class='form-text'>Si el contrato está cancelado, aquí queda el detalle de los equipos recibidos.</div>
      </div>

      <div class='col-md-4'>
        <label for='fecha_cancelacion' class='form-label'>Fecha de cancelación</label>
        <input type='datetime-local' class='form-control' id='fecha_cancelacion' name='fecha_cancelacion' value='".$fechaCancInput."'>
        <div class='form-text'>Déjalo vacío para mantenerla en NULL.</div>
      </div>
      <!-- ====== /Sección Cancelación ====== -->

      <div class='col-6 col-lg-12 space'>
        <button type='button' class='btn btn-primary' onclick='updateContrato()'>Actualizar datos</button>
      </div>
    </div>
  </form>";
        echo "<div id='resultado'></div>";
    }
}
$conexion->close();
?>
