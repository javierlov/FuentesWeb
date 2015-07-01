var totalCaracteresObservaciones = 0;

function cambiarSeleccionNomina(valor) {
	if (valor == 'sn')
		document.getElementById('tipoNomina').disabled = true;
	else
		document.getElementById('tipoNomina').disabled = false;
}

function cancelarProcesoArchivo() {
	with (document) {
		getElementById('divErrores').style.display = 'none';
		getElementById('divCargaOk').style.display = 'none';
		getElementById('divSinRegistros').style.display = 'none';
		getElementById('archivo').readOnly = false;
		getElementById('btnCargar').style.display = 'inline';
		getElementById('btnVerEjemplo').style.display = 'inline';
	}
}

function cargarDatosEmpresaComitente() {
	var height = 320;
	var width = 400;
	var left = ((screen.width - width) / 2) + 52;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/usuarios_registrados/clientes/certificado_de_cobertura//empresas_comitentes.php', 'Buscar Empresa Comitente');
	divWin.show();
}

function checkGridTrabajadores() {
	document.getElementById('iframePaso2').src = '/modules/usuarios_registrados/clientes/certificado_de_cobertura/check_grid_trabajadores.php';
}

function colocarValidacion() {
	with (document) {
		if ((getElementById('calle').value.length > 0) || (getElementById('numero').value.length > 0) || (getElementById('localidad').value.length > 0)) {
			getElementById('asteriscoCalle').innerText = '*';
			getElementById('asteriscoNumero').innerText = '*';
			getElementById('asteriscoLocalidad').innerText = '*';
		}
		else {
			getElementById('asteriscoCalle').innerText = ' ';
			getElementById('asteriscoNumero').innerText = ' ';
			getElementById('asteriscoLocalidad').innerText = ' ';
		}
	}
}

function contarCaracteresObservaciones() {
	with (document) {
		totalCaracteresObservaciones = getElementById('observaciones').value.length; 

		if (totalCaracteresObservaciones > 255)
			getElementById('observaciones').value = getElementById('observaciones').value.substr(0, 255);
		else
			getElementById('caracteresRestantes').innerHTML = 255 - totalCaracteresObservaciones;

		if (totalCaracteresObservaciones > 42)
			getElementById('caracteresRestantes').style.color = '#855353';
		if (totalCaracteresObservaciones > 84)
			getElementById('caracteresRestantes').style.color = '#a33f3f';
		if (totalCaracteresObservaciones > 126)
			getElementById('caracteresRestantes').style.color = '#c13535';
		if (totalCaracteresObservaciones > 168)
			getElementById('caracteresRestantes').style.color = '#df2121';
		if (totalCaracteresObservaciones > 210)
			getElementById('caracteresRestantes').style.color = '#f00';
	}
}

function descargarNomina() {
	with (document) {
		body.style.cursor = 'wait';
		getElementById('imgProcesando').style.visibility = 'visible';
		getElementById('imgDescargarNomina').style.display = 'none';
		getElementById('iframePdf').src = '/modules/usuarios_registrados/clientes/certificado_de_cobertura/descargar_nomina.php';
	}
}

function limpiarForm(tipo) {
	document.getElementById('formTipoCertificado').reset();
	if (tipo == 'cccr')
		document.getElementById('formTipoCertificado').tipoCertificado[1].checked = true;
}

function limpiarTrabajadoresSeleccionados() {
	document.getElementById('iframePaso2').src = '/modules/usuarios_registrados/clientes/certificado_de_cobertura/limpiar_trabajadores_seleccionados.php';
}

function seleccionarCertificado(valor) {
	document.getElementById('iframeProcesando').src = '/modules/usuarios_registrados/clientes/certificado_de_cobertura/seleccionar_tipo_certificado.php?valor=' + valor;
}

function subirArchivo() {
	with (document)
		if (ValidarForm(getElementById('formArchivo')))	{
			getElementById('archivo').readOnly = true;
			getElementById('btnCargar').style.display = 'none';
			getElementById('btnVerEjemplo').style.display = 'none';
			getElementById('divCargaOk').style.display = 'none';
			getElementById('divSinRegistros').style.display = 'none';
			getElementById('imgProcesando').style.display = 'inline';
			getElementById('formArchivo').submit();
		}
}

function validarPrimerPaso() {
	with (document) {
		if (!ValidarRadioButton(getElementById('formTipoCertificado').tipoCertificado))
			return false;

		if (getElementById('formTipoCertificado').tipoCertificado[1].checked) {		//Certificado de cobertura con claúsula de no repetición..
			if (getElementById('tieneDeuda').value == 't') {
				getElementById('trDeuda2').style.display = 'block';
				return false;
			}
		}
	}

	document.getElementById('formTipoCertificado').submit();
}

function validarSegundoPaso(esCertificadoExterior) {
	if (document.getElementById('cantidadTrabajadoresSeleccionados').innerHTML == '0') {
		alert('Debe elegir al menos un (1) trabajador de la nómina.');
		return false;
	}

	if (esCertificadoExterior)
		if (parseInt(document.getElementById('cantidadTrabajadoresSeleccionados').innerHTML) > 20) {
			alert('Solo puede seleccionar hasta veinte (20) trabajadores por certificado de viaje al exterior.');
			return false;
		}

	window.location.href = '/certificados-cobertura/paso-3';
}

function verCertificado() {
	with (document) {
		getElementById('divTituloCertificado').innerHTML = 'Certificado de Cobertura';
		getElementById('iframePdf').style.display = 'block';
		getElementById('iframePdfNomina').style.display = 'none';
		getElementById('btnVerCertificado').style.display = 'none';
		getElementById('btnVerNomina').style.display = 'block';
	}
}

function verEjemplo() {
	var height = 496;
	var width = 688;
	var left = ((screen.width - width) / 2) + 80;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin2 = null;
	divWin2 = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin2.load('iframe', '/modules/usuarios_registrados/clientes/certificado_de_cobertura/ver_ejemplo.php', 'Carga automática de CUILES desde un archivo');
	divWin2.show();
}

function verNomina() {
	with (document) {
		getElementById('divTituloCertificado').innerHTML = 'Certificado de Cobertura - Nómina de Trabajadores';
		getElementById('iframePdf').style.display = 'none';
		getElementById('iframePdfNomina').style.display = 'block';
		getElementById('btnVerCertificado').style.display = 'block';
		getElementById('btnVerNomina').style.display = 'none';
	}
}