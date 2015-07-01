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

function calcularAumento(nodoPadre) {
	with (nodoPadre) {
		if (!ValidarFloat(getElementById('aumentoValor').value)) {
			alert('Por favor, ingrese un valor válido.');
			getElementById('aumentoValor').focus();
			return false;
		}

		if (Number(getElementById('aumentoValor').value) < 0) {
			alert('Debe ingresar un valor mayor a 0.');
			getElementById('aumentoValor').focus();
			return false;
		}

		if (Number(getElementById('aumentoValor').value) > Number(getElementById('topeAumento').value)) {
			alert('El aumento no puede ser mayor al ' + getElementById('topeAumento').value + '%.');
			getElementById('aumentoValor').focus();
			return false;
		}
	}

	nodoPadre.getElementById('iframeProcesando2').src = '/modules/solicitud_cotizacion/calcular_aumento.php?c=' + nodoPadre.getElementById('ciiu1').value + '&ms=' + nodoPadre.getElementById('masaSalarialSinSac').value + '&ct=' + nodoPadre.getElementById('totalTrabajadores').value + '&a=' + nodoPadre.getElementById('aumentoValor').value + '&rnd=' + Math.random();
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

			if (isMesConSAC(getElementById('periodo').value.substr(5, 2)))
				getElementById('resultadoMensualPorTrabajador').value = (Number(getElementById('soloPagoTotalMensual').value) / 1.5 / Number(getElementById('totalTrabajadores').value)).toFixed(2);
			else
				getElementById('resultadoMensualPorTrabajador').value = (Number(getElementById('soloPagoTotalMensual').value) / Number(getElementById('totalTrabajadores').value)).toFixed(2);

			getElementById('calculoSumaFija').value = 0.60;
			getElementById('calculoVariable').value = ((Number(getElementById('soloPagoTotalMensual').value) - (Number(getElementById('totalTrabajadores').value) * 0.6)) / Number(getElementById('masaSalarialSinSac').value) * 100).toFixed(3);
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

			if (isMesConSAC(getElementById('periodo').value.substr(5, 2)))
				getElementById('resultadoMensualPorTrabajador').value = (Number(getElementById('formulario931CostoFijo').value) + (Number(getElementById('formulario931CostoVariable').value) / 1.5)) / Number(getElementById('totalTrabajadores').value);
			else
				getElementById('resultadoMensualPorTrabajador').value = (Number(getElementById('formulario931CostoFijo').value) + Number(getElementById('formulario931CostoVariable').value)) / Number(getElementById('totalTrabajadores').value);

			getElementById('calculoSumaFija').value = (Number(getElementById('formulario931CostoFijo').value) / Number(getElementById('totalTrabajadores').value)).toFixed(2);

			if (isMesConSAC(getElementById('periodo').value.substr(5, 2)))
				getElementById('calculoVariable').value = (Number(getElementById('formulario931CostoVariable').value) / 1.5 / Number(getElementById('masaSalarialSinSac').value) * 100).toFixed(3);
			else
				getElementById('calculoVariable').value = (Number(getElementById('formulario931CostoVariable').value) / Number(getElementById('masaSalarialSinSac').value) * 100).toFixed(3);
		}

		if (formSolicitudCotizacion.rDatosCompetencia[3].checked) {
			if (Number(getElementById('totalTrabajadores').value) == 0) {
				alert('Antes de calcular debe ingresar la cantidad de trabajadores.');
				getElementById('totalTrabajadores1').focus();
				return;
			}

			getElementById('resultadoMensualPorTrabajador').value = (((Number(getElementById('totalTrabajadores').value) * Number(getElementById('alicuotaCompetenciaSumaFija').value)) + (Number(getElementById('masaSalarialSinSac').value) * Number(getElementById('alicuotaCompetenciaVariable').value) / 100)) / Number(getElementById('totalTrabajadores').value)).toFixed(2);
			getElementById('calculoSumaFija').value = getElementById('alicuotaCompetenciaSumaFija').value;
			getElementById('calculoVariable').value = getElementById('alicuotaCompetenciaVariable').value;
		}
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

function calcularMasaSalarialSinSac(isAlta) {
	if (!isAlta)
		return false;

	with (document) {
		if (isMesConSAC(getElementById('periodo').value.substr(5, 2)))
			getElementById('masaSalarialSinSac').value = (Number(getElementById('masaSalarial').value) / 1.5).toFixed(2);
		else
			getElementById('masaSalarialSinSac').value = getElementById('masaSalarial').value;
	}
}

function getActividad(isAlta, destino, codigo) {
	if (isAlta)
		if ((!isNaN(codigo)) && (codigo.length == 6))
			document.getElementById('iframeCiiu').src = '/modules/solicitud_cotizacion/get_actividad.php?target=' + destino + '&codigo=' + codigo;
		else
			document.getElementById(destino).innerHTML = '';
}

function getTopeMaximoF931() {
	with (window.parent.document) {
		var resultado = Number(getElementById('porcVarTarifario').value);

		if (Number(getElementById('porcVarF931').value) > resultado)
			resultado = Number(getElementById('porcVarF931').value);

		if ((Number(getElementById('descuentoTopeF931').value) > 0) && (Number(getElementById('porcVarDescuento').value) > resultado))
			resultado = Number(getElementById('porcVarDescuento').value);

		if ((Number(getElementById('aumentoTopeF931').value) > 0) && (Number(getElementById('porcVarAumento').value) > resultado))
			resultado = Number(getElementById('porcVarAumento').value);

		if (resultado > Number(getElementById('porcVarAumento').value))		// La alícuota máxima es el tope..
			resultado = Number(getElementById('porcVarAumento').value);
	}

	return Number(resultado).toFixed(3);
}

function getTopeMinimoF931() {
	with (window.parent.document) {
		var resultado = Number(getElementById('porcVarTarifario').value);

		if (Number(getElementById('porcVarF931').value) < resultado)
			resultado = Number(getElementById('porcVarF931').value);

		if ((Number(getElementById('descuentoTopeF931').value) > 0) && (Number(getElementById('porcVarDescuento').value) < resultado))
			resultado = Number(getElementById('porcVarDescuento').value);

		if ((Number(getElementById('aumentoTopeF931').value) > 0) && (Number(getElementById('porcVarAumento').value) < resultado))
			resultado = Number(getElementById('porcVarAumento').value);
	}

	return Number(resultado).toFixed(3);
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
		if (getElementById('campanaF931').value == 'N') {
			if (!ValidarFloat(getElementById('aumentoValor').value)) {
				alert('El campo Alícuota FINAL debe ser un valor numérico válido.');
				getElementById('alicuotaFinalF931').focus();
				return false;
			}

			if ((Number(getElementById('alicuotaFinalF931').value) < getTopeMinimoF931()) || (Number(getElementById('alicuotaFinalF931').value) > getTopeMaximoF931())) {
				alert('El campo Alícuota FINAL debe estar entre ' + getTopeMinimoF931() + '% y ' + getTopeMaximoF931() + '%.');
				getElementById('alicuotaFinalF931').focus();
				return false;
			}
		}

		lockControls(false, getElementById('statusSrt').disabled);

		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgGuardando').style.display = 'inline';

		formSolicitudCotizacion.submit();
	}
}

function isMesConSAC(mes) {
	return ((mes == 6) || (mes == 12));
}

function lockControls(lock, lockStatusSrt) {
//	if (!lock)
//		return;

	with (document) {
		getElementById('actividadReal').readOnly = lock;
		getElementById('alicuotaCompetenciaSumaFija').readOnly = lock;
		getElementById('alicuotaCompetenciaVariable').readOnly = lock;
		getElementById('cantidadEstablecimientos').readOnly = lock;
		getElementById('ciiu1').readOnly = lock;
		getElementById('ciiu2').readOnly = lock;
		getElementById('ciiu3').readOnly = lock;

		if (getElementById('codigoVendedor') != null)
			getElementById('codigoVendedor').readOnly = lock;

		getElementById('contacto').readOnly = lock;
		getElementById('cuit').readOnly = lock;
		getElementById('edadPromedio').readOnly = lock;
		getElementById('email').readOnly = lock;
		getElementById('formulario931CostoFijo').readOnly = lock;
		getElementById('formulario931CostoVariable').readOnly = lock;
		getElementById('masaSalarial1').readOnly = lock;
		getElementById('masaSalarial2').readOnly = lock;
		getElementById('masaSalarial3').readOnly = lock;
		getElementById('observaciones').readOnly = lock;
		getElementById('periodo').readOnly = lock;
		getElementById('razonSocial').readOnly = lock;
		getElementById('soloPagoTotalMensual').readOnly = lock;
		getElementById('telefono').readOnly = lock;
		getElementById('totalTrabajadores1').readOnly = lock;
		getElementById('totalTrabajadores2').readOnly = lock;
		getElementById('totalTrabajadores3').readOnly = lock;

		formSolicitudCotizacion.rDatosCompetencia[0].disabled = lock;
		formSolicitudCotizacion.rDatosCompetencia[1].disabled = lock;
		formSolicitudCotizacion.rDatosCompetencia[2].disabled = lock;
		formSolicitudCotizacion.rDatosCompetencia[3].disabled = lock;

		getElementById('art').disabled = lockStatusSrt;
		getElementById('holding').disabled = lock;		
		getElementById('sector').disabled = lock;
		getElementById('statusBcra').disabled = lock;
		getElementById('statusSrt').disabled = lockStatusSrt;
		getElementById('zonaGeografica').disabled = lock;

		if (getElementById('prestacionesEspeciales') != null)
			getElementById('prestacionesEspeciales').disabled = lock;

		if (lock) {
			getElementById('ciiu1Buscar').style.display = 'none';
			getElementById('ciiu2Buscar').style.display = 'none';
			getElementById('ciiu3Buscar').style.display = 'none';
		}
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

function showBuscarCiiuWin(destino) {
	divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=600px,height=400px,left=280px,top=160px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/solicitud_cotizacion/buscar_ciiu.php?trgt=' + destino, 'Buscar Actividad');
	divWin.show();
}

function showBuscarHoldingWin() {
	divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=600px,height=400px,left=280px,top=160px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/solicitud_cotizacion/buscar_holding.php', 'Buscar Holding');
	divWin.show();
}

function showEstablecimientoWindow(idsolicitud, id) {
	divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=600px,height=216px,left=280px,top=160px,resize=1,scrolling=1');

	if (id == -1)
		divWin.load('iframe', '/modules/solicitud_cotizacion/establecimiento.php?idsolicitud=' + idsolicitud + '&id=' + id, 'Agregar Establecimiento');
	else
		divWin.load('iframe', '/modules/solicitud_cotizacion/establecimiento.php?idsolicitud=' + idsolicitud + '&id=' + id, 'Modificar Establecimiento');
	divWin.show();
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
			alert('La C.U.I.T. no es correcta.');
			document.getElementById('cuit').focus();
			return false;
		}

	document.getElementById('imgCuitLoading').style.visibility = 'visible';
	document.getElementById('iframeProcesando').src = '/modules/solicitud_cotizacion/validar_cuit.php?cuit=' + document.getElementById('cuit').value + '&rnd=' + Math.random();
}