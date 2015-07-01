function contar(obj) {
	document.getElementById('numero').innerText = 1024 - obj.value.length;

	if (obj.value.length > 1024)
		obj.value = obj.value.substr(0, 1024);
}

	function showHideObservacion(respuestaid) {
	var obj = document.getElementById('formEncuesta')['opcion'];
	for (i=0; i<obj.length; i++) {
		if (document.getElementById('pObservacion' + obj[i].value) != null)
			if (obj[i].value == respuestaid)
				document.getElementById('pObservacion' + obj[i].value).style.display = 'block';
			else {
				document.getElementById('pObservacion' + obj[i].value).style.display = 'none';
				document.getElementById('observacion' + obj[i].value).value = '';
			}
	}
}

function validarChecks(form) {
	var check = false;

	for (i=0; i<form.elements.length; i++)
		if (form.elements[i].type == 'checkbox')
			if (form.elements[i].checked) {
				check = true;
				break;
			}

	return check;
}

function validarFormEncuesta() {
	if (document.getElementById('multiOpcion').value == 'T') {
		if (document.getElementById('validarCheck').value == 'F') {
			if (!validarObservaciones(document.getElementById('formEncuesta'))) {
				alert('Debe completar todos los cuadros de texto.');
				return false;
			}
		}
		else {
			if (!validarChecks(document.getElementById('formEncuesta'))) {
				alert('Debe seleccionar al menos una opción.');
				return false;
			}
		}
	}
	else {
		if (!ValidarRadioButton(document.getElementById('formEncuesta')['opcion']))
			return false;
	}

	return ValidarForm(document.getElementById('formEncuesta'));
}


function validarObservaciones(form) {
	var vacio = false;

	for (i=0; i<form.elements.length; i++)
		if (form.elements[i].type == 'text')
			if (form.elements[i].value == '') {
				vacio = true;
				break;
			}

	return (!vacio);
}