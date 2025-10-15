function cargarTabla() {
  const estado = document.getElementById('filtro-estado').value;

  $.ajax({
    url: '../php/cargarTabla.php',
    type: 'POST',
    data: JSON.stringify({ estado }),
    contentType: 'application/json',
    success: function(response) {
      $('#tabla').html(response);
    },
    error: function(jqXHR, textStatus) {
      $('#tabla').html('Error al cargar la tabla: ' + textStatus);
    }
  });
}

function loadImage(url){
	return new Promise(resolve =>{
		const xhr = new XMLHttpRequest();
		xhr.open('GET', url, true);
		xhr.responseType = "blob";
		xhr.onload = function (e){
			const reader = new FileReader();
			reader.onload = function(event){
				const res = event.target.result;
				resolve(res);
			}
			const file = this.response;
			reader.readAsDataURL(file);
		}
		xhr.send();
	});
}
// Cancelar: pide equipos devueltos (obligatorio) y actualiza status/fecha_cancelacion
function confirmarCancelacion(idcontrato) {
  Swal.fire({
    title: 'Cancelar contrato',
    html: `
      <label for="equiposDevueltos" style="display:block; text-align:left; margin-bottom:6px;">
        Ingrese los códigos/números de serie de los equipos devueltos:
      </label>
      <textarea id="equiposDevueltos" class="swal2-textarea" 
        placeholder="Ej: ONT Huawei SN12345, Router TP-Link SN67890" required></textarea>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: 'Sí, cancelar',
    cancelButtonText: 'No, mantener activo',
    preConfirm: () => {
      const equipos = document.getElementById('equiposDevueltos').value.trim();
      if (!equipos) {
        Swal.showValidationMessage('Debes ingresar los equipos devueltos');
        return false;
      }
      if (equipos.length < 5) {
        Swal.showValidationMessage('Agrega un poco más de detalle');
        return false;
      }
      return equipos;
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const equiposDevueltos = result.value;
      $.ajax({
        url: '../php/cancelar_contrato.php',
        type: 'POST',
        data: JSON.stringify({ id: idcontrato, equipos: equiposDevueltos }),
        contentType: 'application/json',
        dataType: 'json',
        success: function (res) {
          if (res.ok) {
            Swal.fire('Cancelado', res.message, 'success');
            cargarTabla(); // Recarga tabla con el filtro actual
          } else {
            Swal.fire('Error', res.message || 'No se pudo cancelar.', 'error');
          }
        },
        error: function () {
          Swal.fire('Error', 'No se pudo cancelar el contrato.', 'error');
        }
      });
    }
  });
}

// Reactivar: quita fecha_cancelacion y pone status=activo
function confirmarReactivacion(idcontrato) {
  Swal.fire({
    title: 'Reactivar contrato',
    text: 'El contrato pasará a estado ACTIVO.',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Sí, reactivar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: '../php/reactivar_contrato.php',
        type: 'POST',
        data: JSON.stringify({ id: idcontrato }),
        contentType: 'application/json',
        dataType: 'json',
        success: function (res) {
          if (res.ok) {
            Swal.fire('Reactivado', res.message, 'success');
            cargarTabla();
          } else {
            Swal.fire('Error', res.message || 'No se pudo reactivar.', 'error');
          }
        },
        error: function () {
          Swal.fire('Error', 'No se pudo reactivar el contrato.', 'error');
        }
      });
    }
  });
}



let signaturePad = null;
let signaturePad2 = null;

async function imprimirContrato(idcontrato,nombre,rlegal,calle,numero,colonia,municipio,cp,estado,telefono,ttelefono,rfc,fecha,tarifa,tmensualidad,reconexion,mdesconexion,plazo,modeme,marca,modelo,nserie,nequipo,pagoum,pequipo,domicilioi,fechai,hora,costoi,autorizacion,mpago,vigencia,banco,notarjeta,sadicional1,dadicional1,costoa1,sadicional2,dadicional2,costoa2,sfacturable1,dfacturable1,costof1,sfacturable2,dfacturable2,costof2,ccontrato,cderechos,cciudad,firma1,firma2){
  
  const image1 = await loadImage("../img/bbs-c-1.jpg");
	const image2 = await loadImage("../img/bbs-c-2.jpg");
	const image3 = await loadImage("../img/bbs-c-3.jpg");
	const image4 = await loadImage("../img/bbs-c-4.jpg");
	const image5 = await loadImage("../img/bbs-c-5.jpg");
	const image6 = await loadImage("../img/bbs-c-6.jpg");
	const image7 = await loadImage("../img/bbs-c-7.jpg");
	const image8 = await loadImage("../img/bbs-c-8.jpg");
	const image9 = await loadImage("../img/bbs-c-9.jpg");
	const image10 = await loadImage("../img/bbs-c-10.jpg");
	const image11 = await loadImage("../img/bbs-c-11.jpg");
	const firma = await loadImage("../img/firma-s.png");
  
  const pdf = new jsPDF('p', 'pt', 'letter');
	pdf.addImage(image1, "jpeg", 0, 0, 565, 792);

  pdf.setFontSize(7);
	pdf.setFontStyle('bold');
	pdf.text(nombre,200,113);
	pdf.text(rlegal,155,126);
	pdf.text(calle,50,157);
	pdf.text(numero,215,157);
	pdf.text(colonia,240,157);
	pdf.text(municipio,305,157);
	pdf.text(cp,377,157);
	pdf.text(estado,420,157);
	pdf.text(rfc,377,183);
	//pdf.text(telefono,70,185);
	if (ttelefono=='movil') {
		pdf.text(telefono,230,181);
		pdf.circle(83,167,3,'F');
	}else{
		pdf.text(telefono,228,182);
		pdf.circle(132,167,3,'F');
	}
	if (parseInt(tarifa)===1) {
		pdf.text("Residencial 7 MBPS",230,240);
	}else if (parseInt(tarifa)===2){
		pdf.text("Residencial 10 MBPS",230,240);
	}else if (parseInt(tarifa)===3){
		pdf.text("Residencial 15 MBPS",230,240);
	}else if (parseInt(tarifa)===7){
		pdf.text("Residencial 30 MBPS",230,240);
	}else if (parseInt(tarifa)===4){
		pdf.text("Empresarial 50 MBPS",230,240);
	}else if (parseInt(tarifa)===8){
		pdf.text("Empresarial 80 MBPS",230,240);
	}
  pdf.text(plazo,425,272);
	pdf.text("1 al 5",455,230);
	pdf.text("cada mes",455,236);
	pdf.text(tmensualidad,270,252);

	if (parseInt(reconexion)===1) {
		pdf.circle(215,303,3,'F');
		pdf.text("0",285,295);
		pdf.circle(357,250,3,'F');
		//pdf.circle(358,254,3,'F');
	}else if (parseInt(reconexion)===2) {
		pdf.circle(193,302,3,'F');
		pdf.text("500",285,295);
	}

	if (parseInt(modeme)===1) {
		pdf.circle(200,349,3,'F');
	}else if (parseInt(modeme)===2) {
		pdf.circle(406,350,3,'F');
	}

	pdf.text(marca,210,365);
	pdf.text(modelo,210,376);
	pdf.text(nserie,210,387);
	pdf.text(nequipo,210,399);
	
	if (parseInt(pagoum)===1) {
		pdf.circle(386,442,3,'F');
	}else if (parseInt(pagoum)===2) {
		pdf.circle(350,442,3,'F');
	}else if (parseInt(pagoum)===3) {
		//pdf.circle(332,450,3,'F');
	}

	pdf.text(pequipo,242,444);
	pdf.text(domicilioi,190,482);
	pdf.text(fechai,180,493);
	pdf.text(hora,350,493);
	pdf.text(costoi,180,506);

	if (autorizacion=='si') {
		pdf.circle(158,645,3,'F');
	}else{
		pdf.circle(199,645,3,'F');
	}

	if (parseInt(mpago)===1) {
		pdf.circle(42,583,3,'F');
	}else if (parseInt(mpago)===2){
		pdf.circle(42,593,3,'F');
	}else if (parseInt(mpago)===3){
		pdf.circle(42,603,3,'F');
	}else if (parseInt(mpago)===4){
		pdf.circle(42,613,3,'F');
	}

	pdf.text(vigencia,455,662);

	//end first page

  //second page
	pdf.addPage();
	pdf.addImage(image2, 0, 0, 565, 792);
	pdf.text(banco,80,91.5);
	pdf.text(notarjeta,330,92);

	pdf.text(sadicional1,120,94);
	pdf.text(dadicional1,50,117);
	pdf.text(costoa1,240,117);

	pdf.text(sadicional2,320,94);
	pdf.text(dadicional2,290,117);
	pdf.text(costoa2,445,117);


	pdf.text(sfacturable1,120,178);
	pdf.text(dfacturable1,50,200);
	pdf.text(costof1,240,200);

	pdf.text(sfacturable2,320,178);
	pdf.text(dfacturable2,290,200);
	pdf.text(costof2,445,200);

	if (ccontrato==true) {
		pdf.circle(448,226,3,'F');
	}else{
		pdf.circle(464,226,3,'F');	
	}

	if (cderechos==true) {
		pdf.circle(448,238,3,'F');
	}else{
		pdf.circle(464,238,3,'F');	
	}

	pdf.text(idcontrato,155,440);
	
  let dayContrato = fecha.substring(8,10);

  let monthContrato = fecha.substring(5,7);

  let yearContrato = fecha.substring(0,4);

		let mes;

		switch(monthContrato){
		case "01":
			mes = "Enero";
			break;
		case "02":
			mes = "Febrero";
			break;
		case "03":
			mes = "Marzo";
			break;
		case "04":
			mes = "Abril";
			break;
		case "05":
			mes = "Mayo";
			break;
		case "06":
			mes = "Junio";
			break;
		case "07":
			mes = "Julio";
			break;
		case "08":
			mes = "Agosto";
			break;
		case "09":
			mes = "Septiembre";
			break;
		case "10":
			mes = "Octubre";
			break;
		case "11":
			mes = "Noviembre";
			break;
		case "12":
			mes = "Diciembre";
			break;

		}

	pdf.text(cciudad,255,496);
	pdf.text(dayContrato,323,496);

	pdf.text(mes,360,496);
	pdf.text(yearContrato,415,496);

	pdf.addImage(firma,'PNG',70,515,200,30);
	pdf.addImage(firma1,'PNG',270,515,200,30);


	//Third Page
	pdf.addPage();
	pdf.addImage(image3, 0, 0, 565, 792);
	

	//Fourth Page
	pdf.addPage();
	pdf.addImage(image4, 0, 0, 565, 792);
	

	//Fifth Page
	pdf.addPage();
	pdf.addImage(image5, 0, 0, 565, 792);
	

	//Sixth Page
	pdf.addPage();
	pdf.addImage(image6, 0, 0, 565, 792);
	

	//Seventh Page
	pdf.addPage();
	pdf.addImage(image7, 0, 0, 565, 792);
	

	//Eight Page
	pdf.addPage();
	pdf.addImage(image8, 0, 0, 565, 792);
	

	//Ninth Page
	pdf.addPage();
	pdf.addImage(image9, 0, 0, 565, 792);
	

	//Tenth Page
	// pdf.addPage();
	// pdf.addImage(image10, 0, 0, 565, 792);
	

	//Eleventh Page
	pdf.addPage();
	pdf.addImage(image11, 0, 0, 565, 792);
	//console.log(modemt.value);
	if (parseInt(modeme)=='1') {
	pdf.text(dayContrato+"/"+monthContrato+"/"+yearContrato,161,252);
	pdf.text(nombre,123,323.5);
	pdf.text(municipio,240,299);
	pdf.text(dayContrato,349.5,299);
	pdf.text(monthContrato,375,299);
	pdf.text(yearContrato.substring(2,4),412,299);
	pdf.text('Av. José María Morelos 147, Loma Linda',126,335);
	pdf.text('38980 Uriangato, Gto.',100,346);
	pdf.addImage(firma2,'PNG',280,335,190,30);


  
}
window.open(pdf.output('bloburl'), '_blank');
}

cargarTabla();
function descargarContrato(id) {
  
  $.ajax({
    url: "../php/imprimirPDF.php", // Archivo PHP que manejará la solicitud
    type: "POST",
    data: { id:id },
    success: function (response) {
      const data = JSON.parse(response);
      console.log(data);
      imprimirContrato(''+data.idcontrato,data.nombre,data.rlegal,data.calle,data.numero,data.colonia,data.municipio, data.cp, data.estado, data.telefono, data.ttelefono, data.rfc, data.fecha, data.tarifa, ''+data.tmensualidad, data.reconexion, data.mdesconexion,''+data.plazo, data.modeme, data.marca, data.modelo, data.nserie, ''+data.nequipo, data.pagoum, ''+data.pequipo, data.domicilioi, data.fechai, data.hora, ''+data.costoi, data.autorizacion, data.mpago, ''+data.vigencia, data.banco, data.notarjeta, data.sadicional1, data.dadicional1, ''+data.costoa1, data.sadicional2, data.dadicional2, ''+data.costoa2, data.sfacturable1, data.dfacturable1, ''+data.costof1, data.sfacturable2, data.dfacturable2, ''+data.costof2, data.ccontrato, data.cderechos, data.cciudad, data.firma1, data.firma2);

    },
    error: function (jqXHR, textStatus, errorThrown) {
      $("#resultado").html("Error al eliminar el contrato: " + textStatus);
    },
  });
 
}
function addContract(id){
    $.ajax({
        url: "../php/agregarCliente.php", // Archivo PHP que manejará la solicitud
        type: "POST",
        data: { id:id },
        success: function (response) {
            $('#modal').html(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#resultado").html("Error al eliminar el contrato: " + textStatus);
        },
      });
}
function validateAndAddUsuario(id) {
  // Obtener valores de los campos del formulario
  var localidad = $('#localidad').val();
  var nodo = $('#nodo').val();
  var ip = $('#ip').val();
  var email = $('#email').val();
  var splitter = $('#splitter').val();

  // Verificar si los campos requeridos están llenos y si las opciones no son las predeterminadas
  if (localidad === "" || nodo === "" || ip === "" || email === "") {
      Swal.fire({
          title: "Campos incompletos",
          text: "Por favor, complete todos los campos obligatorios.",
          icon: "warning",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "OK"
      });
      return; // No continuar si los campos no están completos
  }

  // Verificar que la IP cumpla con el patrón requerido
  var ipPattern = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;
  if (!ipPattern.test(ip)) {
      Swal.fire({
          title: "IP no válida",
          text: "Por favor, ingrese una dirección IP válida (por ejemplo, 192.168.0.1).",
          icon: "error",
          confirmButtonColor: "#3085d6",
          confirmButtonText: "OK"
      });
      return; // No continuar si la IP no es válida
  }

  // Si la validación pasa, llamar a la función para agregar usuario
  addUsuario(id);
}
function addUsuario(id){
    $.ajax({
        url: "../php/insertCliete.php", // Archivo PHP que manejará la solicitud
        type: "GET",
        data: {
            id:id,
            localidad:$('#localidad option:selected').html(),
            nodo:$('#nodo option:selected').html(),
            ip:$('#ip').val(),
            email:$('#email').val(),
			splitter:$('#splitter').val()
        },
        success: function (response) {
            //console.log(response);
            if(response=='insercion exitosa'){
              $('#modalAgregar').modal('hide');
            cargarTabla();
              Swal.fire("¡Creado!", "El usuario se ha creado correctamente .", "success");
            }else{
              Swal.fire({
                title: "No se pudo generar el usuario",
                html: "<div style='text-align:center;font-weight:500;'>El ID ingresado ya existe.</div>",
                icon: "error",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK",
                width: "35rem"
            });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#resultado").html("Error al eliminar el contrato: " + textStatus);
        },
      });
}

function editContract(id){
    $.ajax({
        url: "../php/editarContrato.php", // Archivo PHP que manejará la solicitud
        type: "POST",
        data: {
            id:id
        },
        success: function (response) {
            //console.log(response);
            $('#modal2').html(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#resultado").html("Error al editar el contrato: " + textStatus);
        },
      });
}
function updateContrato(){

  let idcontrato = document.getElementById('ncontrato').value;
		let name = document.getElementById('name').value;
		let rlegal = document.getElementById('rlegal').value;
		let street = document.getElementById('street').value;
		let number = document.getElementById('number').value;
		let colonia = document.getElementById('colonia').value;
		let municipio = document.getElementById('municipio').value;
		let cp = document.getElementById('cp').value;
		let estado = document.getElementById('estado').value;
		let rfc = document.getElementById('rfc').value;
		let telefono = document.getElementById('telefono').value;
		let ttipo = document.querySelector('input[name="ttipo"]:checked').value;
		let tarifa = document.getElementById('tarifa').value;
		let total = document.getElementById('totalm').value;
		let plazo = document.getElementById('pmeses').value;
		let reconexion = document.getElementById('reconexion').value;
		let mdesco = document.getElementById('descm').value
		let modemt = document.getElementById('modemt').value;
		let marca = document.getElementById('marca').value;
		let modelo = document.getElementById('modelo').value;
		let serie = document.getElementById('serie').value;
		let nequipos = document.getElementById('nequipos').value;
		let tpago = document.getElementById('tpago').value;
		let cequipos = document.getElementById('cequipos').value;
		let domicilioi = document.getElementById('domicilioi').value;
		let fechai = document.getElementById('fechai').value;
		let horai = document.getElementById('horai').value;
		let costoi = document.getElementById('costoi').value;
		let acargo = document.querySelector('input[name="acargo"]:checked').value;
		let mpago = document.getElementById('mpago').value;
		let cmes = document.getElementById('cmes').value;
		let banco = document.getElementById('banco').value;
		let ntarjeta = document.getElementById('ntarjeta').value;
		let sadicional1 = document.getElementById('sadicional1').value;
		let sdescripcion1 = document.getElementById('sdescripcion1').value;
		let scosto1 = document.getElementById('scosto1').value;
		let sadicional2 = document.getElementById('sadicional2').value;
		let sdescripcion2 = document.getElementById('sdescripcion2').value;
		let scosto2 = document.getElementById('scosto2').value;
		let fadicional1 = document.getElementById('fadicional1').value;
		let fdescripcion1 = document.getElementById('fdescripcion1').value;
		let fcosto1 = document.getElementById('fcosto1').value;
		let fadicional2 = document.getElementById('fadicional2').value;
		let fdescripcion2 = document.getElementById('fdescripcion2').value;
		let fcosto2 = document.getElementById('fcosto2').value;
		let ccontrato = document.getElementById('ccontrato').checked;
		let cdminimos = document.getElementById('cdminimos').checked;
		let ciudad = document.getElementById('ciudad').value;
		let scontrato = document.getElementById('scontrato').checked;
		let ncontrato = document.getElementById('ncontrato').value;
		let equiposDev = document.getElementById('equipos_devueltos')?.value || '';
		let fechaCancel = document.getElementById('fecha_cancelacion')?.value || '';

  var formData = new FormData();
	formData.append('nombre',name);
	formData.append('idcontrato',idcontrato);
	formData.append('rlegal',rlegal);
	formData.append('calle',street);
	formData.append('numero',number);
	formData.append('colonia',colonia);
	formData.append('municipio',municipio);
	formData.append('cp',cp);
	formData.append('estado',estado);
	formData.append('rfc',rfc);
	formData.append('fechac',fechac.value);
	formData.append('telefono',telefono);
	formData.append('ttipo',ttipo);
	formData.append('tarifa',tarifa);
	formData.append('total',total);
	formData.append('reconexion',reconexion);
	formData.append('mdesco',mdesco);
	formData.append('plazo',plazo);
	formData.append('modemt',modemt);
	formData.append('marca',marca);
	formData.append('modelo',modelo);
	formData.append('serie',serie);
	formData.append('nequipos',nequipos);
	formData.append('tpago',tpago);
	formData.append('cequipos',cequipos);
	formData.append('domicilioi',domicilioi);
	formData.append('fechai',fechai);
	formData.append('horai',horai);
	formData.append('costoi',costoi);
	formData.append('acargo',acargo);
	formData.append('mpago',mpago);
	formData.append('cmes',cmes);
	formData.append('banco',banco);
	formData.append('ntarjeta',ntarjeta);
	formData.append('sadicional1',sadicional1);
	formData.append('sdescripcion1',sdescripcion1);
	formData.append('scosto1',scosto1);
	formData.append('sadicional2',sadicional2);
	formData.append('sdescripcion2',sdescripcion2);
	formData.append('scosto2',scosto2);
	formData.append('fadicional1',fadicional1);
	formData.append('fdescripcion1',fdescripcion1);
	formData.append('fcosto1',fcosto1);
	formData.append('fadicional2',fadicional2);
	formData.append('fdescripcion2',fdescripcion2);
	formData.append('fcosto2',fcosto2);
	formData.append('ccontrato',ccontrato);
	formData.append('cdminimos',cdminimos);
	//formData.append('yearContrato',yearContrato);
	//formData.append('mes',mes);
	//formData.append('dayContrato',dayContrato);
	//formData.append('monthContrato',monthContrato);
	formData.append('scontrato',scontrato);
	formData.append('ciudad',ciudad);
	formData.append('ncontrato',ncontrato);
	formData.append('plazo',plazo);
	formData.append('ex',scontrato);
	formData.append('equipos_devueltos', equiposDev);
	formData.append('fecha_cancelacion', fechaCancel)
  $.ajax('../php/updateContrato.php',
    {
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(data){
        
        console.log(data); // Muestra la respuesta en la consola
    var jsonResponse = JSON.parse(data);
	console.log("Query enviado:", data.query);
    console.log("Error (si aplica):", data.error);
    if (jsonResponse.status === "success") {
        $('#modalEditar').modal('hide');
        cargarTabla();
        Swal.fire('Éxito', jsonResponse.message, 'success');
    } else {
		console.error("Query ejecutado:", jsonResponse.query);
        console.error("Error en la consulta:", jsonResponse.error);
        Swal.fire('Error', jsonResponse.message, 'error');
    }
  
        
  
      },
      error: function(jqXHR, textStatus, errorThrown){
		console.error("Error en la solicitud AJAX:");
		console.error("Estado: ", textStatus);
		console.error("Detalle: ", errorThrown);
		console.error("Respuesta del servidor: ", jqXHR.responseText);

		Swal.fire({
			title: "No se pudo generar el contrato",
			icon: "error",
			confirmButtonColor: "#3085d6",
			confirmButtonText: "OK",
			width: "35rem"
		});
	}
    });
}
$(document).on('change', '#tarifa', function() {
  let tarifa = $(this).val();
  let mensualidad = $('#totalm'); // Asegúrate de que este ID coincida con el campo de mensualidad

  switch(tarifa) {
      case '1':
          mensualidad.val('250');
          break;
      case '2':
          mensualidad.val('350');
          break;
      case '3':
          mensualidad.val('450');
          break;
      case '4':
          mensualidad.val('500');
          break;
      case '5':
          mensualidad.val('600');
          break;
		  case '7':
			mensualidad.val('350');
			break;
			case '8':
			mensualidad.val('800');
			break;		  
      default:
          mensualidad.val(''); // Valor por defecto o vaciar si no coincide
  }

});
$(document).on('change', '#reconexion', function() {
  
  //cambio reconexion	
  let reconexion=document.getElementById('reconexion');
  let mdesconexion = document.getElementById('descm');

reconexion.addEventListener("change", (event) => {
//console.log(reconexion.value);
if (reconexion.value==1) {
  mdesconexion.value='$0';
}else if (reconexion.value==2) {
  mdesconexion.value='$500';
}

});

});


