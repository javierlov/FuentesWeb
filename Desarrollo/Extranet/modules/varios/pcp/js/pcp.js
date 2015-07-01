function agregarLugarTrabajo() {
	with (document) {
		if (getElementById('datosDivLugarTrabajo_2').style.display == 'none') {
			if ((getElementById('calle_1').value == '') || (getElementById('codigoPostal_1').value == '') || (getElementById('provincia_1').value == -1) || (getElementById('localidad_1').value == '')) {
				alert('Antes de agregar un lugar de trabajo debe completar los datos del Lugar de Trabajo 1.');
				return;
			}

			getElementById('datosDivLugarTrabajo_2').style.display = 'inline';
			getElementById('lugarTrabajoVisible_2').value = 't';
		}
		else if (getElementById('datosDivLugarTrabajo_3').style.display == 'none') {
			if ((getElementById('calle_2').value == '') || (getElementById('codigoPostal_2').value == '') || (getElementById('provincia_2').value == -1) || (getElementById('localidad_2').value == '')) {
				alert('Antes de agregar un lugar de trabajo debe completar los datos del Lugar de Trabajo 2.');
				return;
			}

			getElementById('datosDivLugarTrabajo_3').style.display = 'inline';
			getElementById('lugarTrabajoVisible_3').value = 't';
		}
		else if (getElementById('datosDivLugarTrabajo_4').style.display == 'none') {
			if ((getElementById('calle_3').value == '') || (getElementById('codigoPostal_3').value == '') || (getElementById('provincia_3').value == -1) || (getElementById('localidad_3').value == '')) {
				alert('Antes de agregar un lugar de trabajo debe completar los datos del Lugar de Trabajo 3.');
				return;
			}

			getElementById('datosDivLugarTrabajo_4').style.display = 'inline';
			getElementById('lugarTrabajoVisible_4').value = 't';
		}
		else if (getElementById('datosDivLugarTrabajo_5').style.display == 'none') {
			if ((getElementById('calle_4').value == '') || (getElementById('codigoPostal_4').value == '') || (getElementById('provincia_4').value == -1) || (getElementById('localidad_4').value == '')) {
				alert('Antes de agregar un lugar de trabajo debe completar los datos del Lugar de Trabajo 4.');
				return;
			}

			getElementById('datosDivLugarTrabajo_5').style.display = 'inline';
			getElementById('lugarTrabajoVisible_5').value = 't';
			getElementById('datosDivAgregarLugarTrabajo').style.display = 'none';
		}
	}
}

function cambiarLocalidad(valor, prefijo) {
	with (document)
		if (valor == -1) {
			getElementById('localidadCombo' + prefijo).style.display = 'none';
			getElementById('localidad' + prefijo).style.display = 'inline';
			getElementById('localidad' + prefijo).value = '';
			getElementById('localidad' + prefijo).focus();
		}
		else {
			getElementById('localidad' + prefijo).value = getElementById('localidadCombo' + prefijo).value;

			if ((getElementById('codigoPostal' + prefijo).value == '') || (getElementById('provincia' + prefijo).value == -1))
				getElementById('iframeProcesando').src = '/modules/varios/pcp/cambiar_combo_localidad.php?prefijo=' + prefijo + '&cp=' + getElementById('codigoPostal' + prefijo).value + '&p=' + getElementById('provincia' + prefijo).value + '&l=' + getElementById('localidad' + prefijo).value + '&c=' + getElementById('calle' + prefijo).value;
		}
}

function cargarComboLocalidad(prefijo) {
	with (document)
		getElementById('iframeProcesando').src = '/modules/varios/pcp/cargar_combo_localidad.php?prefijo=' + prefijo + '&cp=' + getElementById('codigoPostal' + prefijo).value + '&p=' + getElementById('provincia' + prefijo).value + '&l=' + getElementById('localidad' + prefijo).value;
}

function deshabilitarControles(obj) {
	for (i=0; ele = obj.getElementsByTagName('input')[i]; i++)
		if (ele.type != 'submit') {
			ele.disabled = true;
			ele.readonly = true;
			ele.style.backgroundColor = '#ccc';
		}

	for (i=0; ele = obj.getElementsByTagName('select')[i]; i++) {
		ele.disabled = true;
		ele.readonly = true;
		ele.style.backgroundColor = '#ccc';
	}

	for (i=0; ele = obj.getElementsByTagName('textarea')[i]; i++) {
		ele.disabled = true;
		ele.readonly = true;
		ele.style.backgroundColor = '#ccc';
	}

	with (document) {
		getElementById('datosDivAgregarLugarTrabajo').style.display = 'none';
	}
}

function enviarForm() {
	with (document) {
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
	}
}

function setLocalidad(localidad, prefijo) {
	with (document)
		if (localidad == '') {
			getElementById('localidadCombo' + prefijo).style.display = 'inline';
			getElementById('localidad' + prefijo).style.display = 'none';
		}
		else {
			getElementById('localidadCombo' + prefijo).style.display = 'none';
			getElementById('localidad' + prefijo).style.display = 'inline';
		}
}