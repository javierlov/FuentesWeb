var paso = 1;

function administrarEstablecimientos() {
	var height = 416;
	var width = 704;
	var left = ((screen.width - width) / 2) + 80;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin2 = null;
	divWin2 = dhtmlwindow.open('divBoxPrestador', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin2.load('iframe', '/modules/usuarios_registrados/clientes/denuncias_de_siniestros/administrar_establecimientos.php', 'Administrar Establecimientos de Terceros');
	divWin2.show();
}

function buscarCodigoPostal() {
	var height = 416;
	var width = 704;
	var left = ((screen.width - width) / 2) + 80;
	var top = 120;

	divWinCodigoPostal = null;
	divWinCodigoPostal = dhtmlwindow.open('divBoxPrestador', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWinCodigoPostal.load('iframe', '/modules/usuarios_registrados/clientes/denuncias_de_siniestros/buscar_codigo_postal.php', 'Buscar Código Postal');
	divWinCodigoPostal.show();
}

function buscarPrestador() {
	var height = 408;
	var width = 704;
	var left = ((screen.width - width) / 2) + 80;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxPrestador', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/usuarios_registrados/clientes/denuncias_de_siniestros/buscar_prestador.php', 'Buscar Prestador');
	divWin.show();
}

function buscarTrabajador() {
	var height = 400;
	var width = 704;
	var left = ((screen.width - width) / 2) + 80;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxTrabajador', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/usuarios_registrados/clientes/denuncias_de_siniestros/buscar_trabajador.php', 'Buscar Trabajador');
	divWin.show();
}

function cambiaLugarOcurrencia(tieneEstablecimientosTercero, valor) {
	with (document) {
		getElementById('establecimientoTercero').value = -1;
		getElementById('establecimientoPropio').value = -1;

		getElementById('trEstablecimientoTercero').style.visibility = 'hidden';
		getElementById('establecimientoPropio').style.visibility = 'hidden';

		if (valor == -1) {
			getElementById('trEstablecimiento').style.visibility = 'hidden';
			getElementById('trLugarOcurrenciaOtros').style.visibility = 'hidden';
		}
		if (valor == 1) {
			getElementById('trEstablecimiento').style.visibility = 'visible';
			getElementById('establecimientoPropio').style.visibility = 'visible';
			getElementById('trLugarOcurrenciaOtros').style.visibility = 'hidden';
			if (tieneEstablecimientosTercero)
				getElementById('trEstablecimientoTercero').style.visibility = 'visible';
		}
		if (valor == 2) {
			getElementById('trEstablecimiento').style.visibility = 'hidden';
			getElementById('trLugarOcurrenciaOtros').style.visibility = 'hidden';
		}
		if (valor == 3) {
			getElementById('trEstablecimiento').style.visibility = 'hidden';
			getElementById('trLugarOcurrenciaOtros').style.visibility = 'hidden';
		}
		if (valor == 4) {
			getElementById('trEstablecimiento').style.visibility = 'hidden';
			getElementById('trLugarOcurrenciaOtros').style.visibility = 'hidden';
			if (tieneEstablecimientosTercero)
				getElementById('trEstablecimientoTercero').style.visibility = 'visible';
		}
		if (valor == 5) {
			getElementById('trEstablecimiento').style.visibility = 'hidden';
			getElementById('trLugarOcurrenciaOtros').style.visibility = 'visible';
		}
	}

	habilitarDomicilioAccidente(document, true);
}

function cambiarPaso(paso) {
	with (document) {
		for (i=1; i<=5; i++) {
			getElementById('divPaso' + i).style.display = 'none';
			getElementById('numeroPaso' + i).style.color = '';
			getElementById('numeroPaso' + i).style.fontWeight = '';
		}

		getElementById('btnAnterior').style.display = 'none';
		getElementById('btnSiguiente').style.display = 'none';
		getElementById('btnEnviar').style.display = 'none';

		getElementById('divPaso' + paso).style.display = 'block';
		getElementById('numeroPaso' + paso).style.color = '#0f539c';
		getElementById('numeroPaso' + paso).style.fontWeight = 'bold';


		switch (paso) {
			case 1:
				getElementById('titulo').innerHTML = 'Paso 1: Datos del Trabajador';
				getElementById('btnSiguiente').style.display = 'inline';
				break;
			case 2:
				getElementById('titulo').innerHTML = 'Paso 2: Datos del Siniestro';
				getElementById('btnAnterior').style.display = 'inline';
				getElementById('btnSiguiente').style.display = 'inline';
				break;
			case 3:
				getElementById('titulo').innerHTML = 'Paso 3: Descripción y Códigos';
				getElementById('btnAnterior').style.display = 'inline';
				getElementById('btnSiguiente').style.display = 'inline';
				break;
			case 4:
				getElementById('titulo').innerHTML = 'Paso 4: Prestaciones Médicas';
				getElementById('btnAnterior').style.display = 'inline';
				getElementById('btnSiguiente').style.display = 'inline';
				break;
			case 5:
				copiarDatosFaltantes();
				getElementById('titulo').innerHTML = 'Paso 5: Enviar Denuncia';
				getElementById('btnAnterior').style.display = 'inline';
				if (!todoDeshabilitado)
					getElementById('btnEnviar').style.display = 'inline';
				break;
		}
	}
}

function cambiaTipoSiniestro(valor) {
	iframeProcesando.location.href = '/modules/usuarios_registrados/clientes/denuncias_de_siniestros/llenar_combo_lugar_ocurrencia.php?v=' + valor;
}

function copiarDomicilioEstablecimiento(idEstablecimiento, establecimientoPropio) {
	iframeProcesando.location.href = '/modules/usuarios_registrados/clientes/denuncias_de_siniestros/copiar_domicilio_establecimiento.php?id=' + idEstablecimiento + '&ep=' + establecimientoPropio;
}

function copiarDatosFaltantes() {
	with (document) {
		// Domicilio del trabajador..
		getElementById('spanCalle').innerHTML = getElementById('calle').value;
		getElementById('spanCodigoPostal').innerHTML = getElementById('codigoPostal').value;
		getElementById('spanLocalidad').innerHTML = getElementById('localidad').value;
		getElementById('spanNumero').innerHTML = getElementById('numero').value;
		getElementById('spanProvincia').innerHTML = getElementById('provincia').value;

		// Domicilio del accidente..
		getElementById('spanCalleAccidente').innerHTML = getElementById('calleAccidente').value;
		getElementById('spanCodigoPostalAccidente').innerHTML = getElementById('codigoPostalAccidente').value;
		getElementById('spanLocalidadAccidente').innerHTML = getElementById('localidadAccidente').value;
		getElementById('spanProvinciaAccidente').innerHTML = getElementById('provinciaAccidente').value;
	}
}

function copiarLugarOcurrencia() {
	with (document) {
		if (getElementById('lugarOcurrencia').value == -1)
			getElementById('spanLugarOcurrencia').innerHTML = '';

		if (getElementById('lugarOcurrencia').value == 1) {
			if (getElementById('establecimientoPropio').value == -1)
				getElementById('spanLugarOcurrencia').innerHTML = getElementById('lugarOcurrencia').options[getElementById('lugarOcurrencia').selectedIndex].text;
			else
				getElementById('spanLugarOcurrencia').innerHTML = getElementById('establecimientoPropio').options[getElementById('establecimientoPropio').selectedIndex].text;
		}

		if (getElementById('lugarOcurrencia').value == 2)
			getElementById('spanLugarOcurrencia').innerHTML = getElementById('lugarOcurrencia').options[getElementById('lugarOcurrencia').selectedIndex].text;

		if (getElementById('lugarOcurrencia').value == 3)
			getElementById('spanLugarOcurrencia').innerHTML = getElementById('lugarOcurrencia').options[getElementById('lugarOcurrencia').selectedIndex].text;

		if (getElementById('lugarOcurrencia').value == 4)
			getElementById('spanLugarOcurrencia').innerHTML = getElementById('lugarOcurrencia').options[getElementById('lugarOcurrencia').selectedIndex].text;

		if (getElementById('lugarOcurrencia').value == 5) {
			if (getElementById('lugarOcurrenciaOtros').value == '')
				getElementById('spanLugarOcurrencia').innerHTML = getElementById('lugarOcurrencia').options[getElementById('lugarOcurrencia').selectedIndex].text;
			else
				getElementById('spanLugarOcurrencia').innerHTML = getElementById('lugarOcurrenciaOtros').value;
		}
	}
}

function copiarValor(objOrigen, objDestino) {
	objDestino.value = objOrigen.value;
}

function deshabilitarTodo() {
	with (document) {
		getElementById('cuitContratista').readOnly = true;
		getElementById('departamento').readOnly = true;
		getElementById('departamentoAccidente').readOnly = true;
		getElementById('descripcionHecho').readOnly = true;
		getElementById('domicilioPrestador').readOnly = true;
		getElementById('fechaIngreso').readOnly = true;
		getElementById('fechaNacimiento').readOnly = true;
		getElementById('fechaRecaida').readOnly = true;
		getElementById('fechaSiniestro').readOnly = true;
		getElementById('lugarOcurrenciaOtros').readOnly = true;
		getElementById('numero').readOnly = true;
		getElementById('numeroAccidente').readOnly = true;
		getElementById('piso').readOnly = true;
		getElementById('pisoAccidente').readOnly = true;
		getElementById('puesto').readOnly = true;
		getElementById('razonSocialPrestador').readOnly = true;
		getElementById('tareasAccidente').readOnly = true;
		getElementById('telefono').readOnly = true;
		getElementById('telefonoPrestador').readOnly = true;

		getElementById('accidenteTransito').disabled = true;
		getElementById('agenteMaterial').disabled = true;
		getElementById('establecimientoPropio').disabled = true;
		getElementById('establecimientoAccidente').disabled = true;
		getElementById('estadoCivil').disabled = true;
		getElementById('formaAccidente').disabled = true;
		getElementById('gravedadPresunta').disabled = true;
		getElementById('horaAccidente').disabled = true;
		getElementById('horaDesde').disabled = true;
		getElementById('horaHasta').disabled = true;
		getElementById('horaJornadaLaboralDesde').disabled = true;
		getElementById('horaJornadaLaboralHasta').disabled = true;
		getElementById('lugarOcurrencia').disabled = true;
		getElementById('manoHabil').disabled = true;
		getElementById('minutoAccidente').disabled = true;
		getElementById('minutoDesde').disabled = true;
		getElementById('minutoHasta').disabled = true;
		getElementById('minutoJornadaLaboralDesde').disabled = true;
		getElementById('minutoJornadaLaboralHasta').disabled = true;
		getElementById('nacionalidad').disabled = true;
		getElementById('naturalezaLesion').disabled = true;
		getElementById('parteCuerpoLesionada').disabled = true;
		getElementById('sexo').disabled = true;
		getElementById('siniestroMultiple').disabled = true;
		getElementById('tipoSiniestro').disabled = true;

		getElementById('btnBuscarDomicilio').style.display = 'none';
//		getElementById('btnBuscarDomicilio2').style.display = 'none';
		getElementById('btnBuscarPrestador').style.display = 'none';
		getElementById('btnBuscarTrabajador').style.display = 'none';
		getElementById('btnEnviar').style.display = 'none';
		getElementById('btnFechaIngreso').style.display = 'none';
		getElementById('btnFechaNacimiento').style.display = 'none';
		getElementById('btnFechaRecaida').style.display = 'none';
		getElementById('btnFechaSiniestro').style.display = 'none';
		getElementById('btnLimpiarSeleccion').style.display = 'none';
	}
}

function habilitarDomicilioAccidente(doc, habilitar) {
	with (doc)
		if (habilitar) {
			getElementById('departamentoAccidente').style.backgroundColor = '';
			getElementById('numeroAccidente').style.backgroundColor = '';
			getElementById('pisoAccidente').style.backgroundColor = '';

//			getElementById('btnBuscarDomicilio2').style.display = 'block';

			getElementById('departamentoAccidente').readOnly = false;
			getElementById('numeroAccidente').readOnly = false;
			getElementById('pisoAccidente').readOnly = false;
		}
		else {
			getElementById('departamentoAccidente').style.backgroundColor = '#ccc';
			getElementById('numeroAccidente').style.backgroundColor = '#ccc';
			getElementById('pisoAccidente').style.backgroundColor = '#ccc';

//			getElementById('btnBuscarDomicilio2').style.display = 'none';

			getElementById('departamentoAccidente').readOnly = true;
			getElementById('numeroAccidente').readOnly = true;
			getElementById('pisoAccidente').readOnly = true;
		}
}

function quitarPrestador() {
	with (document) {
		getElementById('domicilioPrestador').value = '';
		getElementById('idPrestador').value = -1;
		getElementById('razonSocialPrestador').value = '';
		getElementById('telefonoPrestador').value = '';

		getElementById('domicilioPrestador').readOnly = false;
		getElementById('razonSocialPrestador').readOnly = false;
		getElementById('telefonoPrestador').readOnly = false;

		getElementById('spanDomicilioPrestador').innerHTML		 = '';
		getElementById('spanPrestador').innerHTML					 = '';
		getElementById('spanRazonSocialPrestador').innerHTML	 = '';
		getElementById('spanTelefonoPrestador').innerHTML		 = '';
	}
}