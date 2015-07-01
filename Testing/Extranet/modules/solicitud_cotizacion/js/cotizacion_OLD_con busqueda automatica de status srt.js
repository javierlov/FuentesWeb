function actualizarRC(id) {
	with (document) {
		if (formSolicitudCotizacion.suscribePolizaRC[0].checked)
			sp = formSolicitudCotizacion.suscribePolizaRC[0].value;
		if (formSolicitudCotizacion.suscribePolizaRC[1].checked)
			sp = formSolicitudCotizacion.suscribePolizaRC[1].value;

		getElementById('iframeProcesando').src = '/modules/solicitud_cotizacion/actualizar_rc.php?id=' + id + '&sa=' + getValorSumaAsegurada() + '&p=' + getElementById('polizaRC').value + '&sp=' + sp;
	}
}

function addActividad() {
	if (document.getElementById('trActividad2').style.visibility == 'hidden')
		document.getElementById('trActividad2').style.visibility = 'visible';
	else
		if (document.getElementById('trActividad3').style.visibility == 'hidden') {
			document.getElementById('btnAgregarActividad').style.visibility = 'hidden';
			document.getElementById('trActividad3').style.visibility = 'visible';
		}
}

function anularSolicitud(id, idformulario, usuario, numeroAfiliacion, cuit, razonSocial, afiliacionImpresaYNoPresentada, vigente) {
	estado = '';

	if (idformulario == '') {
		if (vigente == 'F') {
			alert('La solicitud no está vigente.');
			return false;
		}

		estado = '18.0';
		if (!confirm('¿ Realmente desea dar de baja esta solicitud ?'))
			return false;
	}
	else {
		if (afiliacionImpresaYNoPresentada == 'T') {
			if (vigente == 'T') {
				estado = '18.0';
				msg = 'Estimado ' + usuario + ':\n' +
							'Mediante la opción "Aceptar" procederá a dar de baja la solicitud seleccionada:\n' +
							'Nº de Solicitud de Afiliación ' + numeroAfiliacion + '\n' +
							'CUIT ' + cuit + '\n' +
							'RAZÓN SOCIAL ' + razonSocial + '\n' +
							'siendo su responsabilidad la destrucción de los papeles impresos.\n' +
							'Las solicitudes dadas de baja no pueden ser presentadas en Provincia ART,' +
							' quedando bajo su responsabilidad los efectos derivados de la falta de presentación.';
				if (!confirm(msg))
					return false;
			}
			else {
				estado = '18.3';
				if (!confirm('¿ Realmente desea dar de baja esta solicitud ?'))
					return false;
			}
		}
	}

	document.getElementById('iframeProcesando').src = '/modules/solicitud_cotizacion/anular_solicitud.php?id=' + id + '&idformulario=' + idformulario + '&estado=' + estado + '&rnd=' + Math.random();
}

function calcularDatosCompetencia(calcular) {
	if (!calcular)
		return false;

	with (document) {
		if (formSolicitudCotizacion.rDatosCompetencia[1].checked) {
			if (Number(getElementById('totalTrabajadores').value) == 0) {
				alert('Antes de calcular debe ingresar la cantidad de trabajadores.');
				getElementById('totalTrabajadores1').focus();
				return;
			}

			getElementById('resultadoMensualPorTrabajador').value = (Number(getElementById('soloPagoTotalMensual').value) / Number(getElementById('totalTrabajadores').value)).toFixed(2);
			getElementById('calculoSumaFija').value = 0;
			getElementById('calculoVariable').value = 0;
		}

		if (formSolicitudCotizacion.rDatosCompetencia[2].checked) {
			if (Number(getElementById('totalTrabajadores').value) == 0) {
				alert('Antes de calcular debe ingresar la cantidad de trabajadores.');
				getElementById('totalTrabajadores1').focus();
				return;
			}
			if (Number(getElementById('masaSalarial').value) == 0) {
				alert('Antes de calcular debe ingresar la masa salarial.');
				getElementById('masaSalarial1').focus();
				return;
			}

			getElementById('resultadoMensualPorTrabajador').value = ((Number(getElementById('formulario931CostoFijo').value) + Number(getElementById('formulario931CostoVariable').value)) / Number(getElementById('totalTrabajadores').value)).toFixed(2);
			getElementById('calculoSumaFija').value = (Number(getElementById('formulario931CostoFijo').value) / Number(getElementById('totalTrabajadores').value)).toFixed(2);
			getElementById('calculoVariable').value = (Number(getElementById('formulario931CostoVariable').value) / Number(getElementById('masaSalarial').value) * 100).toFixed(4);
		}

		if (formSolicitudCotizacion.rDatosCompetencia[3].checked) {
			if (Number(getElementById('totalTrabajadores').value) == 0) {
				alert('Antes de calcular debe ingresar la cantidad de trabajadores.');
				getElementById('totalTrabajadores1').focus();
				return;
			}

			getElementById('resultadoMensualPorTrabajador').value = (((Number(getElementById('totalTrabajadores').value) * Number(getElementById('alicuotaCompetenciaSumaFija').value)) + (Number(getElementById('masaSalarial').value) * Number(getElementById('alicuotaCompetenciaVariable').value) / 100)) / Number(getElementById('totalTrabajadores').value)).toFixed(2);
			getElementById('calculoSumaFija').value = getElementById('alicuotaCompetenciaSumaFija').value;
			getElementById('calculoVariable').value = getElementById('alicuotaCompetenciaVariable').value;
		}
	}
}

function calcularMasaSalarialSinSac(isAlta) {
	if (!isAlta)
		return false;

	with (document) {
		if ((getElementById('periodo').value.substr(5, 2) == '06') || (getElementById('periodo').value.substr(5, 2) == '12'))
			getElementById('masaSalarialSinSac').value = (Number(getElementById('masaSalarial').value) / 1.5).toFixed(2);
		else
			getElementById('masaSalarialSinSac').value = getElementById('masaSalarial').value;
	}
}

function calcularDescuento(nodoPadre) {
	with (nodoPadre) {
		if (!ValidarFloat(getElementById('descuentoValor').value)) {
			alert('Por favor, ingrese un valor válido.');
			getElementById('descuentoValor').focus();
			return false;
		}

		if (Number(getElementById('descuentoValor').value) < 0) {
			alert('Debe ingresar un valor mayor a 0.');
			getElementById('descuentoValor').focus();
			return false;
		}

		if (Number(getElementById('descuentoValor').value) > Number(getElementById('topeDescuento').value)) {
			alert('El descuento no puede ser mayor al ' + getElementById('topeDescuento').value + '%.');
			getElementById('descuentoValor').focus();
			return false;
		}
	}

	nodoPadre.getElementById('iframeProcesando').src = '/modules/solicitud_cotizacion/calcular_descuento.php?c=' + nodoPadre.getElementById('ciiu1').value + '&ms=' + nodoPadre.getElementById('masaSalarialSinSac').value + '&ct=' + nodoPadre.getElementById('totalTrabajadores').value + '&d=' + nodoPadre.getElementById('descuentoValor').value + '&rnd=' + Math.random();
}

function getActividad(isAlta, destino, codigo) {
	if (isAlta)
		if ((!isNaN(codigo)) && (codigo.length == 6))
			document.getElementById('iframeCiiu').src = '/modules/solicitud_cotizacion/get_actividad.php?target=' + destino + '&codigo=' + codigo;
		else
			document.getElementById(destino).innerHTML = '';
}

function getValorSumaAsegurada() {
	with (document.formSolicitudCotizacion) {
		if (sumaAseguradaRC[0].checked)
			return sumaAseguradaRC[0].value;
		if (sumaAseguradaRC[1].checked)
			return sumaAseguradaRC[1].value;
		if (sumaAseguradaRC[2].checked)
			return sumaAseguradaRC[2].value;
		if (sumaAseguradaRC[3].checked)
			return sumaAseguradaRC[3].value;
	}
}

function getVendedor(codigo) {
	document.getElementById('iframeProcesando').src = '/modules/solicitud_cotizacion/get_vendedor.php?codigo=' + codigo;
}

function guardarSolicitud() {
	with (document) {
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgGuardando').style.display = 'inline';

		formSolicitudCotizacion.submit();
	}
}

function lockControls(lock) {
	if (!lock)
		return;

	with (document) {
		getElementById('actividadReal').readOnly = true;
		getElementById('alicuotaCompetenciaSumaFija').readOnly = true;
		getElementById('alicuotaCompetenciaVariable').readOnly = true;
		getElementById('cantidadEstablecimientos').readOnly = true;
		getElementById('ciiu1').readOnly = true;
		getElementById('ciiu2').readOnly = true;
		getElementById('ciiu3').readOnly = true;

		if (getElementById('codigoVendedor') != null)
			getElementById('codigoVendedor').readOnly = true;

		getElementById('contacto').readOnly = true;
		getElementById('cuit').readOnly = true;
		getElementById('edadPromedio').readOnly = true;
		getElementById('email').readOnly = true;
		getElementById('formulario931CostoFijo').readOnly = true;
		getElementById('formulario931CostoVariable').readOnly = true;
		getElementById('masaSalarial1').readOnly = true;
		getElementById('masaSalarial2').readOnly = true;
		getElementById('masaSalarial3').readOnly = true;
		getElementById('observaciones').readOnly = true;
		getElementById('periodo').readOnly = true;
		getElementById('razonSocial').readOnly = true;
		getElementById('soloPagoTotalMensual').readOnly = true;
		getElementById('telefono').readOnly = true;
		getElementById('totalTrabajadores1').readOnly = true;
		getElementById('totalTrabajadores2').readOnly = true;
		getElementById('totalTrabajadores3').readOnly = true;

		formSolicitudCotizacion.rDatosCompetencia[0].disabled = true;
		formSolicitudCotizacion.rDatosCompetencia[1].disabled = true;
		formSolicitudCotizacion.rDatosCompetencia[2].disabled = true;
		formSolicitudCotizacion.rDatosCompetencia[3].disabled = true;

		getElementById('art').disabled = true;
		getElementById('holding').disabled = true;
		getElementById('sector').disabled = true;
		getElementById('statusBcra').disabled = true;

		getElementById('ciiu1Buscar').style.display = 'none';
		getElementById('ciiu2Buscar').style.display = 'none';
		getElementById('ciiu3Buscar').style.display = 'none';
	}
}

function mostrarBotonGuardar(nodoPadre) {
	with (nodoPadre) {
		getElementById('btnGuardar').style.display = 'inline';
		getElementById('imgGuardando').style.display = 'none';
	}
}

function recalcularRC(id, sumaasegurada) {
	with (document) {
		var descuento = 0;
		if (getElementById('descuentoValor') != null)
			descuento = getElementById('descuentoValor').value;

		getElementById('iframeProcesando').src = '/modules/solicitud_cotizacion/recalcular_rc.php?cuit=' + getElementById('cuit').value + '&capitas=' + getElementById('totalTrabajadores').value + '&masasalarial=' + getElementById('masaSalarialSinSac').value + '&porcentajevariable=' + getElementById('alicuotasMasaSalarial').value + '&costomensual=' + getElementById('alicuotasFijo').value + '&id=' + id + '&valor=' + sumaasegurada + '&descuento=' + descuento + '&zonageografica=' + getElementById('zonaGeografica').value;
	}
}

function sumarMasaSalarial(isAlta) {
	if (!isAlta)
		return;

	with (document) {
		reemplazarPuntoXComa(getElementById('masaSalarial1'));
		reemplazarPuntoXComa(getElementById('masaSalarial2'));
		reemplazarPuntoXComa(getElementById('masaSalarial3'));
		getElementById('masaSalarial').value = Number(getElementById('masaSalarial1').value) + Number(getElementById('masaSalarial2').value) + Number(getElementById('masaSalarial3').value);
	}
	calcularMasaSalarialSinSac(true);
}

function sumarTrabajadores(isAlta) {
	if (!isAlta)
		return;

	with (document)
		getElementById('totalTrabajadores').value = Number(getElementById('totalTrabajadores1').value) + Number(getElementById('totalTrabajadores2').value) + Number(getElementById('totalTrabajadores3').value);
}

function validarDatosCuit(isAlta) {
	document.getElementById('imgCuitLoading').style.visibility = 'hidden';

	if (!isAlta)
		return;

	if (document.getElementById('cuit').value != '')
		if (!ValidarCuit(document.getElementById('cuit').value)) {
			alert('El CUIT no es correcto.');
			document.getElementById('cuit').focus();
			return false;
		}

	document.getElementById('imgCuitLoading').style.visibility = 'visible';

	document.getElementById('iframeProcesando').src = '/modules/solicitud_cotizacion/validar_cuit.php?cuit=' + document.getElementById('cuit').value + '&rnd=' + Math.random();
}