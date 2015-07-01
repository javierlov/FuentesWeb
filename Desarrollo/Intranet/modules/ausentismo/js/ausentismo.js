function cuenta(obj) {
	document.getElementById('caracteres2').value = obj.value.length;
} 

function mostrarEnviarMedico() {
	with (document)
		if (getElementById('motivoAusencia').value == 1)
			getElementById('trEnviarMedico').style.visibility = 'visible';
		else {
			getElementById('trEnviarMedico').style.visibility = 'hidden';
			getElementById('enviarMedico').value = -1;
			getElementById('trJustifique').style.visibility = 'hidden';
		}
}

function mostrarJustificacion() {
	with (document)
		if (getElementById('enviarMedico').value == 'F')
			getElementById('trJustifique').style.visibility = 'visible';
		else {
			getElementById('trJustifique').style.visibility = 'hidden';
			getElementById('justifique').value = '';
		}
}

function ocultarMensajeOk() {
	document.getElementById('spanMensaje').style.display = 'none';
}

function valida_longitud(obj) {
	num_caracteres2 = obj.value.length;

	if (num_caracteres2 > num_caracteres2_permitidos)
		obj.value = contenido_textarea;
	else
		contenido_textarea = obj.value;

	if (num_caracteres2 >= num_caracteres2_permitidos)
		obj.style.color = "#f00";
	else
		obj.style.color = "#000";

	cuenta(obj);
}

function validarFormulario(formu) {
	if (!ValidarForm(formu))
		return false;

	if (document.getElementById('trEnviarMedico').style.visibility == 'visible') {
		field = document.getElementById('enviarMedico');
		if (field.value == -1) {
			alert('Por favor indique si hay que enviar médico o no.');
			field.focus();
			return false;
		}
	}

	if (document.getElementById('trJustifique').style.visibility == 'visible') {
		field = document.getElementById('justifique');
		if (field.value == '') {
			alert('Por favor indique porque no quiere enviar médico.');
			field.focus();
			return false;
		}
	}

	document.getElementById('btnEnviar').style.display = 'none';
	document.getElementById('imgProcesando').style.display = 'inline';

	return true;
}


var contenido_textarea = "";
var num_caracteres2_permitidos = 255;