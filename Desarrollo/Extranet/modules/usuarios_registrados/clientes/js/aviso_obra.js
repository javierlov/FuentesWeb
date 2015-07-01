function cargarResponsableHYS(numeroDocumento) {
	iframeAvisoObra.location.href = '/modules/usuarios_registrados/clientes/aviso_obra/cargar_datos_responsable_hys.php?d=' + numeroDocumento;
}

function checkComitente(checked) {
	with (document) {
		getElementById('cuitComitente').readOnly = !checked;
		getElementById('razonSocialComitente').readOnly = !checked;
		}
}

function checkContratistaPrincipal(checked) {
	with (document) {
		getElementById('cuitContratistaPrincipal').readOnly = !checked;
		getElementById('razonSocialContratistaPrincipal').readOnly = !checked;
		}
}

function checkDemolicion(checked) {
	with (document) {
		getElementById('fechaDesdeDemolicion').readOnly = !checked;
		getElementById('btnFechaDesdeDemolicion').disabled = !checked;
		getElementById('btnFechaDesdeDemolicion').className = (checked)?'botonFecha':'botonFechaDeshabilitado';

		getElementById('fechaHastaDemolicion').readOnly = !checked;
		getElementById('btnFechaHastaDemolicion').disabled = !checked;
		getElementById('btnFechaHastaDemolicion').className = (checked)?'botonFecha':'botonFechaDeshabilitado';

		if (checked) {
			getElementById('total').setAttribute("onClick", "");
			getElementById('parcial').setAttribute("onClick", "");
		}
		else {
			getElementById('total').setAttribute("onClick", "return false;");
			getElementById('parcial').setAttribute("onClick", "return false;");
		}
	}
}

function checkExcavacion(checked) {
	with (document) {
		getElementById('fechaDesdeExcavacion').readOnly = !checked;
		getElementById('btnFechaDesdeExcavacion').disabled = !checked;
		getElementById('btnFechaDesdeExcavacion').className = (checked)?'botonFecha':'botonFechaDeshabilitado';

		getElementById('fechaHastaExcavacion').readOnly = !checked;
		getElementById('btnFechaHastaExcavacion').disabled = !checked;
		getElementById('btnFechaHastaExcavacion').className = (checked)?'botonFecha':'botonFechaDeshabilitado';

		if (checked) {
			getElementById('submuraciones').setAttribute("onClick", "");
			getElementById('subsuelos').setAttribute("onClick", "");
		}
		else {
			getElementById('submuraciones').setAttribute("onClick", "return false;");
			getElementById('subsuelos').setAttribute("onClick", "return false;");
		}
	}
}

function checkExcavacion503(checked) {
	with (document) {
		getElementById('fechaDesdeExcavacion503').readOnly = !checked;
		getElementById('btnFechaDesdeExcavacion503').disabled = !checked;
		getElementById('btnFechaDesdeExcavacion503').className = (checked)?'botonFecha':'botonFechaDeshabilitado';

		getElementById('fechaHastaExcavacion503').readOnly = !checked;
		getElementById('btnFechaHastaExcavacion503').disabled = !checked;
		getElementById('btnFechaHastaExcavacion503').className = (checked)?'botonFecha':'botonFechaDeshabilitado';

		getElementById('detallarExcavacion503').readOnly = !checked;
	}
}

function checkSubcontratista(checked) {
	with (document) {
		getElementById('cuitSubcontratista').readOnly = !checked;
		getElementById('razonSocialSubcontratista').readOnly = !checked;
	}
}

function darBaja(id) {
	if (confirm('¿ Realmente desea dar de baja este Aviso de Obra ?'))
		iframeAvisoObra.location.href = '/modules/usuarios_registrados/clientes/aviso_obra/dar_baja.php?id=' + id;
}

function enviarForm() {
	// Calculo la cantidad de teléfonos cargados..
	var tmp = document.getElementById('iframeTelefonos').contentWindow.document.documentElement.innerHTML.toUpperCase().split('<TR CLASS="');
	document.getElementById('telefonosCargados').value = ((tmp.length - 1) > 0)?'t':'f';

	with (document) {
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
	}
	
}

function seleccionarCaracteristicasObrador(tipo) {
	with (document) {
		getElementById('caracteristicasObrador').value = tipo;

		if (tipo == 'S') {
			getElementById('spanObradorSi').style.backgroundColor = '#34E079';
			getElementById('spanObradorNo').style.backgroundColor = '';

			getElementById('tipoForm').value = 1;

			getElementById('divDatosGeneralesSuperficie').style.display = 'block';
			getElementById('divIngenieriaCivilMain').style.display = 'block';
			getElementById('divArquitecturaMain').style.display = 'block';
			getElementById('divMontajeIndustrialMain').style.display = 'block';
			getElementById('divRedesMain').style.display = 'block';
			getElementById('divOtrasConstruccionesMain').style.display = 'block';
			getElementById('divActividadMain').style.display = 'block';
			getElementById('divComitenteMain').style.display = 'block';
			getElementById('divResponsableHysMain').style.display = 'block';
			getElementById('divResponsableDatosMain').style.display = 'block';
		}

		if (tipo == 'N') {
			getElementById('spanObradorSi').style.backgroundColor = '';
			getElementById('spanObradorNo').style.backgroundColor = '#34E079';

			getElementById('tipoForm').value = 0;

			getElementById('divDatosGeneralesSuperficie').style.display = 'none';
			getElementById('divIngenieriaCivilMain').style.display = 'none';
			getElementById('divArquitecturaMain').style.display = 'none';
			getElementById('divMontajeIndustrialMain').style.display = 'none';
			getElementById('divRedesMain').style.display = 'none';
			getElementById('divOtrasConstruccionesMain').style.display = 'none';
			getElementById('divActividadMain').style.display = 'none';
			getElementById('divComitenteMain').style.display = 'none';
			getElementById('divResponsableHysMain').style.display = 'none';
			getElementById('divResponsableDatosMain').style.display = 'none';
		}
	}
}

function seleccionarObrador() {
	var height = 440;
	var width = 800;
	var left = ((screen.width - width) / 2) + 52;
	var top = 80;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxObrador', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/usuarios_registrados/clientes/aviso_obra/seleccionar_obrador.php', 'Seleccionar Obrador');
	divWin.show();
}

function showHideDiv(obj) {
	if (obj.nextSibling.style == undefined) {
		var img = obj.childNodes[3];
		obj = obj.nextSibling.nextSibling;
	}
	else {
		var img = obj.childNodes[2];
		obj = obj.nextSibling;
	}

	if (obj.style.display == 'none') {
		img.src = '/images/minus16.png';
		img.title = 'Contraer';
		obj.style.display = 'inline';
	}
	else {
		img.src = '/images/add16.png';
		img.title = 'Desplegar';
		obj.style.display = 'none';
	}
}