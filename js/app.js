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
//window.onload = datoContrato();
let signaturePad = null;
let signaturePad2 = null;
var aceptado;
let valido;
let scheck;

var isChecked = document.getElementById('check-c').checked;

//cambio de precio
ptarifa=document.getElementById('tarifa');
mensualidad = document.getElementById('totalm');
mpago = document.getElementById('mpago');
banco = document.getElementById('banco');
tarjeta = document.getElementById('ntarjeta');

ptarifa.addEventListener("change", (event) => {
	if (ptarifa.value==1) {
		mensualidad.value='250';
	}else if (ptarifa.value==2) {
		mensualidad.value='350';
	}else if (ptarifa.value==3) {
		mensualidad.value='450';
	}else if (ptarifa.value==4) {
		mensualidad.value='500';
	}else if (ptarifa.value==5) {
		mensualidad.value='500';
	}else if (ptarifa.value==6) {
		mensualidad.value='600';
	}else if (ptarifa.value==7) {
		mensualidad.value='350';
	}

});

var contraton = document.getElementById('ncontrato');

function evidencia(){
	var DataFile = new FormData();

	var fileInput = document.getElementById('evidencia');
	var ncontrato = document.getElementById('ncontrato');
	console.log(ncontrato.value);
	var file = fileInput.files[0];

	DataFile.append('archivo',file);
	DataFile.append('numero',ncontrato.value);
	$.ajax('./php/evidencia.php',
	{
		method: 'POST',
		data: DataFile,
		processData: false,
		contentType: false,
		success: function(data){
    		
			$( "#datos" ).html(data);
			Swal.fire("¡Guardado!", "", "success");

		},
		error: function(data){
			console.log(data);
			Swal.fire({
                title: "No se pudo guardar el archivo",
                icon: "error",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "OK",
                width: "35rem"
            });
		}
	});
}
function validarFormulario() {
  let esValido = true;
  const errores = [];

  const campos = document.querySelectorAll(".requerido");

  campos.forEach((campo) => {
    if (campo.disabled) return;

    const valor = campo.value.trim();
    if (!valor) {
      campo.classList.add("border-red-500");
      const label = document.querySelector(`label[for="${campo.id}"]`);
      errores.push(label ? label.innerText : campo.name || "Campo requerido");
      esValido = false;
    } else {
      campo.classList.remove("border-red-500");
    }
  });

  // Validar checkbox
  const checkContrato = document.getElementById("check-c");
  if (!checkContrato.checked) {
    errores.push("Aceptar términos y condiciones");
    checkContrato.classList.add("ring-2", "ring-red-500");
    esValido = false;
  } else {
    checkContrato.classList.remove("ring-2", "ring-red-500");
  }

  // Validar firmas
  if (signaturePad.isEmpty()) {
    errores.push("Firma del contrato");
    esValido = false;
  }

  if (modem.value === "1" && signaturePad2.isEmpty()) {
    errores.push("Firma del pagaré");
    esValido = false;
  }

  if (!esValido) {
    Swal.fire({
      icon: "error",
      title: "Campos incompletos",
      html: `<ul class="text-left">${errores.map(e => `<li>• ${e}</li>`).join('')}</ul>`
    });
  }

  return esValido;
}

			
			
mpago.addEventListener("change", (event) => {
	if (mpago.value==2) {
		banco.disabled=false;
		ntarjeta.disabled=false;
	}else{
		banco.disabled=true;
		ntarjeta.disabled=true;
	}

});

//cambio reconexion	
reconexion=document.getElementById('reconexion');
mdesconexion = document.getElementById('descm');

reconexion.addEventListener("change", (event) => {
	//console.log(reconexion.value);
	if (reconexion.value==1) {
		mdesconexion.value='0';
	}else if (reconexion.value==2) {
		mdesconexion.value='500';
	}

});

//validacion inputs






window.addEventListener('load', async () =>{



	const canvas = document.querySelector("#signature-canvas");
	//const canvas = document.getElementById("signature-canvas");
	canvas.height = canvas.offsetHeight;
	canvas.width = canvas.offsetWidth;

	const canvas2 = document.querySelector("#signature-canvas2");
	//const canvas = document.getElementById("signature-canvas");
	canvas2.height = canvas.offsetHeight;
	canvas2.width = canvas.offsetWidth;

	signaturePad = new SignaturePad(canvas, {
		penColor: "#00bcd4"
	});
	signaturePad2 = new SignaturePad(canvas2, {
		penColor: "#00bcd4"
	});
	const form = document.querySelector("#form");


	form.addEventListener('submit', (e)=>{
		e.preventDefault();
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
		let checkc = document.getElementById('check-c').checked;
		let scontrato = document.getElementById('scontrato').checked;
		let ncontrato = document.getElementById('ncontrato').value;
		//console.log(ncontrato);


		let fechac = document.getElementById('fechac');

		let yearContrato = fechac.value.substring(0,4);
		let mes;
		
		let monthContrato = fechac.value.substring(5,7);
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

		// for (var i = 0; i <= array.length; i++) {
		// 	//console.log(array[i]);
		// 	//console.log(aceptado);
		// 	if (array[i]==contraton.value) {
    	// 			aceptado=false;
    	// 			break;
    	// 		}else{
    	// 			aceptado=true;
    	// 		}
		// }
		let dayContrato = fechac.value.substring(8,10);

		 inputs=document.querySelectorAll(".requerido");
		 j = 0;
		for (var i = 0; i < inputs.length; i++) {
			

			if (inputs[i].value=='' || inputs[i].value==null || inputs[i].value==undefined) {
				//console.log("falta algun dato de ingresar");
				//console.log(inputs[i].value);
			}else{
				//console.log("lleno");
				//console.log(inputs[i].value);
				j++;

			}

		}
		
			if (validarFormulario() && id_valido === true) {
			//document.querySelector("#resultado").innerHTML="Documento creado exitosamente";
			//document.querySelector("#resultado").style.color='green';
			generatePDF(idcontrato,name,rlegal,street,number,colonia,municipio,cp,estado,rfc,telefono,ttipo,tarifa,total,reconexion,mdesco,plazo,modemt,marca,modelo,serie,nequipos,tpago,cequipos,domicilioi,fechai,horai,costoi,acargo,mpago,cmes,banco,ntarjeta,sadicional1,sdescripcion1,scosto1,sadicional2,sdescripcion2,scosto2,fadicional1,fdescripcion1,fcosto1,fadicional2,fdescripcion2,fcosto2,ccontrato,cdminimos,yearContrato,mes,dayContrato,monthContrato,scontrato,ciudad,ncontrato,plazo);

		 }else{
		 	//document.querySelector("#resultado").style.color='red';
		 	if (j<inputs.length && scontrato.checked!=false) {
		 		//document.querySelector("#resultado").innerHTML="Faltan Datos por llenar";
		 		//document.getElementById("euser").classList.add('d-none');

		 	}else{
		 		//document.querySelector("#resultado").innerHTML="el usurio ya existe, si deseas continuar marca la casilla de arriba";
		 		//document.getElementById("euser").classList.remove('d-none');

		 	}
		 	
		 	
		 }
	

	});

	//const image = await loadImage("../img/1.jpg");
	


});

async function generatePDF(idcontrato,name,rlegal,street,number,colonia,municipio,cp,estado,rfc,telefono,ttipo,tarifa,total,reconexion,mdesco,plazo,modemt,marca,modelo,serie,nequipos,tpago,cequipos,domicilioi,fechai,horai,costoi,acargo,mpago,cmes,banco,ntarjeta,sadicional1,sdescripcion1,scosto1,sadicional2,sdescripcion2,scosto2,fadicional1,fdescripcion1,fcosto1,fadicional2,fdescripcion2,fcosto2,ccontrato,cdminimos,yearContrato,mes,dayContrato,monthContrato,scontrato,ciudad,ncontrato,plazo){
	const image1 = await loadImage("./img/bbs-c-1.jpg");
	const image2 = await loadImage("./img/bbs-c-2.jpg");
	const image3 = await loadImage("./img/bbs-c-3.jpg");
	const image4 = await loadImage("./img/bbs-c-4.jpg");
	const image5 = await loadImage("./img/bbs-c-5.jpg");
	const image6 = await loadImage("./img/bbs-c-6.jpg");
	const image7 = await loadImage("./img/bbs-c-7.jpg");
	const image8 = await loadImage("./img/bbs-c-8.jpg");
	const image9 = await loadImage("./img/bbs-c-9.jpg");
	const image10 = await loadImage("./img/bbs-c-10.jpg");
	const image11 = await loadImage("./img/bbs-c-11.jpg");
	const firma = await loadImage("./img/firma-s.png");

	const signatureImage = signaturePad.toDataURL();
	const signatureImage2  = signaturePad2.toDataURL();

	

	const pdf = new jsPDF('p', 'pt', 'letter');
	pdf.addImage(image1, "jpeg", 0, 0, 565, 792);
	// pdf2.addImage(image2, "jpeg", 0, 0, 565, 792);
	// pdf.addImage(signatureImage,'PNG',200,715,300,50);

	pdf.setFontSize(7);
	pdf.setFontStyle('bold');
	pdf.text(name,200,113);
	pdf.text(rlegal,155,126);
	pdf.text(street,50,157);
	pdf.text(number,215,157);
	pdf.text(colonia,240,157);
	pdf.text(municipio,305,157);
	pdf.text(cp,377,157);
	pdf.text(estado,420,157);
	pdf.text(rfc,377,183);
	//pdf.text(telefono,70,185);
	if (ttipo=='movil') {
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
	}else if (parseInt(tarifa)===4){
		pdf.text("Residencial 20 MBPS",230,240);
	}else if (parseInt(tarifa)===5){
		pdf.text("Residencial 40 MBPS",230,240);
	}else if (parseInt(tarifa)===6){
		pdf.text("Residencial 50 MBPS",230,240);
	}else if (parseInt(tarifa)===7){
		pdf.text("Residencial 30 MBPS",230,240);
	}
	pdf.text(plazo,425,272);
	pdf.text("1 al 5",455,230);
	pdf.text("cada mes",455,236);
	pdf.text(total,270,252);

	if (parseInt(reconexion)===1) {
		pdf.circle(215,303,3,'F');
		pdf.text("0",285,295);
		pdf.circle(357,250,3,'F');
		//pdf.circle(358,254,3,'F');
	}else if (parseInt(reconexion)===2) {
		pdf.circle(193,302,3,'F');
		pdf.text("500",285,295);
	}

	if (parseInt(modemt)===1) {
		pdf.circle(200,349,3,'F');
	}else if (parseInt(modemt)===2) {
		pdf.circle(406,350,3,'F');
	}

	pdf.text(marca,210,365);
	pdf.text(modelo,210,376);
	pdf.text(serie,210,387);
	pdf.text(nequipos,210,399);
	
	if (parseInt(tpago)===1) {
		pdf.circle(386,442,3,'F');
	}else if (parseInt(tpago)===2) {
		pdf.circle(350,442,3,'F');
	}else if (parseInt(tpago)===3) {
		//pdf.circle(332,450,3,'F');
	}

	pdf.text(cequipos,242,444);
	pdf.text(domicilioi,190,482);
	pdf.text(fechai,180,493);
	pdf.text(horai,350,493);
	pdf.text(costoi,180,506);

	if (acargo=='si') {
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

	pdf.text(cmes,455,662);

	//end first page

	//second page
	pdf.addPage();
	pdf.addImage(image2, 0, 0, 565, 792);
	pdf.text(banco,80,91.5);
	pdf.text(ntarjeta,330,92);

	pdf.text(sadicional1,120,94);
	pdf.text(sdescripcion1,50,117);
	pdf.text(scosto1,240,117);

	pdf.text(sadicional2,320,94);
	pdf.text(sdescripcion2,290,117);
	pdf.text(scosto2,445,117);


	pdf.text(fadicional1,120,178);
	pdf.text(fdescripcion1,50,200);
	pdf.text(fcosto1,240,200);

	pdf.text(fadicional2,320,178);
	pdf.text(fdescripcion2,290,200);
	pdf.text(fcosto2,445,200);

	if (ccontrato==true) {
		pdf.circle(448,226,3,'F');
	}else{
		pdf.circle(464,226,3,'F');	
	}

	if (cdminimos==true) {
		pdf.circle(448,238,3,'F');
	}else{
		pdf.circle(464,238,3,'F');	
	}

	pdf.text(ncontrato,155,440);
	
	pdf.text(ciudad,255,496);
	pdf.text(dayContrato,323,496);

	pdf.text(mes,360,496);
	pdf.text(yearContrato,415,496);

	pdf.addImage(firma,'PNG',70,515,200,30);
	pdf.addImage(signatureImage,'PNG',270,515,200,30);


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
	if (parseInt(modemt)==1) {
	pdf.text(dayContrato+"/"+monthContrato+"/"+yearContrato,161,252);
	pdf.text(name,123,323.5);
	pdf.text(municipio,240,299);
	pdf.text(dayContrato,349.5,299);
	pdf.text(monthContrato,375,299);
	pdf.text(yearContrato.substring(2,4),412,299);
	pdf.text('Av. José María Morelos 147, Loma Linda',126,335);
	pdf.text('38980 Uriangato, Gto.',100,346);
	pdf.addImage(signatureImage2,'PNG',280,335,190,30);
	}else{
		//console.log("no entro");
	}
	

	
	// var a = {
	// 	ex : scontrato.checked
	// }
	//insert on folder//
	//console.log(scontrato.checked);
	var blob = pdf.output('blob');
	var formData = new FormData();
	formData.append('pdf', blob);
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
	formData.append('yearContrato',yearContrato);
	formData.append('mes',mes);
	formData.append('dayContrato',dayContrato);
	formData.append('monthContrato',monthContrato);
	formData.append('scontrato',scontrato);
	formData.append('ciudad',ciudad);
	formData.append('ncontrato',ncontrato);
	formData.append('plazo',plazo);
	formData.append('firma1',signatureImage);
	formData.append('firma2',signatureImage2);
	formData.append('ex',scontrato)
	$.ajax('./php/upload.php', {
    method: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(data){
    let res = data;
    try {
        if (typeof data === "string") {
            res = JSON.parse(data);
        }

        if (res.ok) {
            window.open(pdf.output('bloburl'), '_blank');
            Swal.fire("¡Creado!", "El contrato se ha creado correctamente.", "success");

            const fileInput = document.getElementById('evidencia');
            if (fileInput && fileInput.files.length > 0) {
                evidencia();
            }
        } else {
            Swal.fire("Error", res.message || "Error desconocido.", "error");
        }

    } catch (e) {
        console.error("Error al procesar respuesta:", e);
        Swal.fire("Error", "Respuesta inválida del servidor.", "error");
    }
}
,
    error: function(err){
        Swal.fire("Error", "No se pudo generar el contrato.", "error");
    }
});

	

}