function ajustarAnchoCombos() {
	var w = 0;
	with (window.parent.document.getElementById('formEvaluacion')) {
		for (i=0; i<elements.length; i++)
			if (elements[i].type == 'select-one')
				if (elements[i].id.substr(0, 6) == 'combo_')
					if (elements[i].offsetWidth > w)
						w = elements[i].offsetWidth;

		for (i=0; i<elements.length; i++)
			if (elements[i].type == 'select-one')
				if (elements[i].id.substr(0, 6) == 'combo_')
					elements[i].style.width = w + 'px';
	}
}

function cambiarIdentidad() {
	var height = 288;
	var width = 248;
	var left = ((screen.width - width) / 2) + 52;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/evaluacion_desempeno/cambiar_identidad.php', 'Cambiar Identidad');
	divWin.show();
}

function cambiarUsuarioAEvaluar(idEvaluacion, ano) {
	document.getElementById('iframeEvaluacion').src = '/modules/evaluacion_desempeno/cambiar_usuario.php?idevaluacion=' + idEvaluacion + '&ano=' + ano + '&rnd=' + Math.random();
}

function contarCaracteresComentarios(obj, objCantCar) {
	with (document) {
		totalCaracteresObservaciones = obj.value.length; 

		if (totalCaracteresObservaciones > 2000)
			obj.value = obj.value.substr(0, 2000);
		else
			getElementById(objCantCar).innerHTML = 2000 - totalCaracteresObservaciones;

		if (totalCaracteresObservaciones > 42)
			getElementById(objCantCar).style.color = '#855353';
		if (totalCaracteresObservaciones > 84)
			getElementById(objCantCar).style.color = '#a33f3f';
		if (totalCaracteresObservaciones > 126)
			getElementById(objCantCar).style.color = '#c13535';
		if (totalCaracteresObservaciones > 168)
			getElementById(objCantCar).style.color = '#df2121';
		if (totalCaracteresObservaciones > 210)
			getElementById(objCantCar).style.color = '#f00';
	}
}

function contarCaracteresObservaciones(id) {
	with (document) {
		totalCaracteresObservaciones = getElementById('observaciones_' + id).value.length; 

		if (totalCaracteresObservaciones > 2000)
			getElementById('observaciones_' + id).value = getElementById('observaciones_' + id).value.substr(0, 2000);
		else
			getElementById('caracteresRestantes_' + id).innerHTML = 2000 - totalCaracteresObservaciones;

		if (totalCaracteresObservaciones > 42)
			getElementById('caracteresRestantes_' + id).style.color = '#855353';
		if (totalCaracteresObservaciones > 84)
			getElementById('caracteresRestantes_' + id).style.color = '#a33f3f';
		if (totalCaracteresObservaciones > 126)
			getElementById('caracteresRestantes_' + id).style.color = '#c13535';
		if (totalCaracteresObservaciones > 168)
			getElementById('caracteresRestantes_' + id).style.color = '#df2121';
		if (totalCaracteresObservaciones > 210)
			getElementById('caracteresRestantes_' + id).style.color = '#f00';
	}
}

function despintarCombo(obj) {
	obj.style.backgroundColor = '';
	obj.style.color = '';
}

function enableAllControls() {
	with (window.parent.document) {
		with (getElementById('formEvaluacion'))
			for (i=0; i<elements.length; i++) {
				if (elements[i].type == 'radio')
					elements[i].disabled = false;
				if (elements[i].type == 'textarea')
					elements[i].readOnly = false;
			}

		getElementById('btnGuardar').style.display = 'inline';
		getElementById('btnEnviarEvaluacion').style.display = 'inline';
		getElementById('btnMeNotifique').style.display = 'inline';

		getElementById('comentariosEvaluado').readOnly = false;
		getElementById('comentariosEvaluador').readOnly = false;
		getElementById('comentariosSupervisor').readOnly = false;
	}
}

function enviarEvaluacion() {
	if (confirm('Una vez enviada la evaluación usted no la podrá modificar.\n\n¿ Confirma el envío ?')) {
		document.getElementById('cerrarEvaluacion').value = true;
		enviarForm();
	}
}

function enviarForm() {
	with (document) {
		getElementById('divDatos').style.display = 'none';
		getElementById('formEvaluacion').submit();
	}
}

function guardarEvaluacion() {
	document.getElementById('cerrarEvaluacion').value = false;
	enviarForm();
}

function imprimirEvaluacion() {
	// Despliego los divs y habilito todos los controles para que salgan bien en la impresión..
  document.getElementById("divCompetencias").style.display = "block";
  enableAllControls();

	// Agrando todos los textarea..
	with (document) {
		resizeTextarea(getElementById('comentariosEvaluado'));
		resizeTextarea(getElementById('comentariosEvaluador'));
		resizeTextarea(getElementById('comentariosSupervisor'));
	}

	window.print();
/*  if ((navigator.appName == "Netscape"))
    window.print();
  else {
    var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
    document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
    WebBrowser1.ExecWB(6, -1);
    WebBrowser1.outerHTML = "";
  }
*/

	// Contraigo los divs..
  document.getElementById("divCompetencias").style.display = "none";

	// Recargo al usuario para que muestre u oculte lo que corresponda..
	cambiarUsuarioAEvaluar(document.getElementById('usuarioAEvaluar').value, document.getElementById('ano').value);
}

function notificarEvaluacion() {
	if (confirm('Una vez enviado su comentario usted no lo podrá modificar.\n\n¿ Confirma el envío ?')) {
		document.getElementById('cerrarEvaluacion').value = true;
		enviarForm();
	}
}

function uncheckRadioControls() {
	with (window.parent.document.getElementById('formEvaluacion'))
		for (i=0; i<elements.length; i++)
			if (elements[i].type == 'radio')
				elements[i].checked = false;
}