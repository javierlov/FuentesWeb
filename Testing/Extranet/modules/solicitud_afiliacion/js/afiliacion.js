function abrirVentanaEstablecimiento(idModulo, idSolicitud, id) {
	if (id < 1)
		caption = 'Alta Establecimiento';
	else
		caption = 'Modificación Establecimiento';


	height = 560;
	width = 688;

	var left = ((screen.width - width) / 2) + 52;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxEstablecimiento', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1, scrolling=1');
	divWin.load('iframe', '/modules/solicitud_afiliacion/establecimiento.php?idModulo=' + idModulo + '&idSolicitud=' + idSolicitud + '&id=' + id, caption);
	divWin.show();
}

function abrirVentanaRGRL(idModulo, id) {
	height = 520;
	width = 632;

	var left = ((screen.width - width) / 2) + 52;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxRGRL', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1, scrolling=1');
	divWin.load('iframe', '/modules/solicitud_afiliacion/rgrl.php?idModulo=' + idModulo + '&idEstablecimiento=' + id, 'Relevamiento General de Riesgos Laborales');
	divWin.show();
}

function cambiaEstablecimiento(valor) {
	with (document)
		if (valor == 'O') {
			getElementById('tituloFechaFinObra').innerHTML = 'F. Finalización de la Obra (*)';
			getElementById('fechaFinObra').style.marginLeft = '0px';
		}
		else {
			getElementById('tituloFechaFinObra').innerHTML = 'F. Finalización de la Obra';
			getElementById('fechaFinObra').style.marginLeft = '21px';
		}
}

function cambiaFormaPago(valor) {
	with (document)
		switch (valor) {
			case 'B':
				getElementById('cbu').readOnly = true;
				getElementById('cbu').style.backgroundColor = '#ccc';
				getElementById('cbu').value = '';
				getElementById('tarjetaCredito').style.display = 'none';
				getElementById('tarjetaCredito').value = -1;
				getElementById('tarjetaCreditoFalso').style.display = 'inline';
				break;
			case 'DA':
				getElementById('cbu').readOnly = false;
				getElementById('cbu').style.backgroundColor = '';
				getElementById('cbu').value = '';
				getElementById('labelCbu').innerHTML = 'C.B.U.';
				getElementById('tarjetaCredito').style.display = 'none';
				getElementById('tarjetaCredito').value = -1;
				getElementById('tarjetaCreditoFalso').style.display = 'inline';
				break;
			case 'TC':
				getElementById('cbu').readOnly = false;
				getElementById('cbu').style.backgroundColor = '';
				getElementById('cbu').value = '';
				getElementById('labelCbu').innerHTML = 'Nº Tarjeta';
				getElementById('tarjetaCredito').style.display = 'inline';
				getElementById('tarjetaCreditoFalso').style.display = 'none';
				break;
		}
}

function clicItemPregunta(idEstablecimiento, id, valor, expandir) {
	with (document) {
		if (valor == 'N') {
			getElementById('btnFecha' + id).style.display = 'inline';
			getElementById('fecha_' + id).style.display = 'inline';
			getElementById('btnFechaD' + id).style.display = 'none';
			getElementById('fechaD_' + id).style.display = 'none';
			iframeProcesando.location.href = '/modules/solicitud_afiliacion/cargar_fecha_regularizacion_por_defecto.php?idEstablecimiento=' + idEstablecimiento + '&id=' + id;
		}
		else {
			getElementById('btnFecha' + id).style.display = 'none';
			getElementById('fecha_' + id).style.display = 'none';
			getElementById('btnFechaD' + id).style.display = 'inline';
			getElementById('fechaD_' + id).style.display = 'inline';
		}

		if (getElementById('btnExpandir_' + id) != null) {
			if (valor == 'S') {
				getElementById('btnExpandir_' + id).style.display = 'block';
				getElementById('divPlanilla_' + id).style.display = 'none';
			}
			else {
				getElementById('btnExpandir_' + id).style.display = 'none';
				getElementById('divPlanilla_' + id).style.display = 'block';
			}

			if (expandir)
				expandirPlanilla(id);
		}
	}
}

function clicPregunta(id, respuesta) {
	with (document)
		if (respuesta == 'si') {
			getElementById('btnExpandir_' + id).style.display = 'block';
			getElementById('divPlanilla_' + id).style.display = 'none';
		}
		else {
			getElementById('btnExpandir_' + id).style.display = 'none';
			getElementById('divPlanilla_' + id).style.display = 'block';
		}
	expandirPlanilla(id);
}

function clicSinPersonal() {
	if (document.getElementById('sinPersonal').checked)
		document.getElementById('cantidadEmpleados').value = '0';
}

function copiarFechaSuscripcion(statusSrt, fecha) {
	with (document) {
		if ((isNaN(fecha.substr(0, 2))) || (isNaN(fecha.substr(3, 2))) || (isNaN(fecha.substr(6, 4))))
			return;

		var bGetUltimoDiaMes = true;
		var dFecha = new Date(Number(fecha.substr(6, 4)), (Number(fecha.substr(3, 2)) - 1), Number(fecha.substr(0, 2)));

		// Fecha vigencia desde..
		if (statusSrt == 2) {		// Si es afiliación vigente..
			dia = '01';

			if ((dFecha.getDate() <= 10) || ((dFecha.getDate() == 11) && (dFecha.getDay() == 1)) || ((dFecha.getDate() == 12) && (dFecha.getDay() == 1))) {
				mes = dFecha.getMonth() + 2;
				ano = dFecha.getFullYear();
				if (mes > 12) {
					mes = mes - 12;
					ano++;
				}
				if (mes < 10)
					mes = '0' + mes;
			}
			else {
				mes = dFecha.getMonth() + 3;
				ano = dFecha.getFullYear();
				if (mes > 12) {
					mes = mes - 12;
					ano++;
				}
				if (mes < 10)
					mes = '0' + mes;
			}
			getElementById('fechaVigenciaDesde').value = '01/' + mes + '/' + ano;
		}
		else {
			dFecha.setTime(dFecha.getTime() + (1000*60*60*24));		// Le sumo un día..
			dia = dFecha.getDate();
			bGetUltimoDiaMes = (dia > 1);
			if (dia < 10)
				dia = '0' + dia;
			mes = dFecha.getMonth() + 1;
			if (mes < 10)
				mes = '0' + mes;
			ano = dFecha.getFullYear();
			getElementById('fechaVigenciaDesde').value = dia + '/' + mes + '/' + ano;
		}

		// Fecha vigencia hasta..
		dFecha = new Date(ano, (mes - 1), dia);
		dFecha.setFullYear((dFecha.getFullYear() + 1));		// Le sumo un año..
		dFecha.setTime(dFecha.getTime() - (1000*60*60*24));		// Le resto un día..

		mes = dFecha.getMonth() + 1;
		if (mes < 10)
			mes = '0' + mes;

		dia = dFecha.getDate();
		if (bGetUltimoDiaMes)
			dia = daysInMonth(mes, dFecha.getFullYear());

		getElementById('fechaVigenciaHasta').value = '';
		if (dia < 10)
			getElementById('fechaVigenciaHasta').value = '0';
		getElementById('fechaVigenciaHasta').value+= dia + '/' + mes + '/' + dFecha.getFullYear();

		// Fecha suscripción..
		var dFecha = new Date(Number(fecha.substr(6, 4)), (Number(fecha.substr(3, 2)) - 1), Number(fecha.substr(0, 2)));
		getElementById('diaSuscripcion').value = dFecha.getDate();
		getElementById('mesSuscripcion').value = getMonthName(dFecha.getMonth() + 1);
		getElementById('anoSuscripcion').value = dFecha.getFullYear();
	}
}

function escribirEmpleados() {
	with (document)
		getElementById('sinPersonal').checked = ((getElementById('cantidadEmpleados').value.length == 0) || (getElementById('cantidadEmpleados').value == '0'));
}

function eliminarEstablecimiento(id) {
	if (confirm('¿ Realmente desea dar de baja este establecimiento ?'))
		iframeProcesando.location.href = '/modules/solicitud_afiliacion/eliminar_establecimiento.php?id=' + id;
}

function expandirPlanilla(id) {
	with (document)
		if (getElementById('divPlanilla_' + id).style.display == 'none') {
			getElementById('btnExpandir_' + id).innerHTML = 'Colapsar';
			getElementById('btnExpandir_' + id).style.backgroundColor = '#f73132';
			getElementById('divPlanilla_' + id).style.display = 'block';
		}
		else {
			getElementById('btnExpandir_' + id).innerHTML = 'Expandir';
			getElementById('btnExpandir_' + id).style.backgroundColor = '#c2d560';
			getElementById('divPlanilla_' + id).style.display = 'none';
		}
}

function getActividad(destino, codigo) {
	if ((!isNaN(codigo)) && (codigo.length == 6))
		document.getElementById('iframeProcesando').src = '/modules/solicitud_cotizacion/get_actividad.php?target=' + destino + '&codigo=' + codigo;
	else
		document.getElementById(destino).innerHTML = '';
}

function getValorSumaAsegurada() {
	with (document.formSolicitudAfiliacion) {
		if (sumaAseguradaRC[0].checked)
			return sumaAseguradaRC[0].value;
		else if (sumaAseguradaRC[1].checked)
			return sumaAseguradaRC[1].value;
		else if (sumaAseguradaRC[2].checked)
			return sumaAseguradaRC[2].value;
		else if (sumaAseguradaRC[3].checked)
			return sumaAseguradaRC[3].value;
		else
			return 0;
	}
}

function imprimir(id, idSolicitudAfiliacion) {
	document.getElementById('iframeProcesando').src = '/modules/solicitud_afiliacion/validar_total_rgrl.php?id=' + id + '&idSolicitudAfiliacion=' + idSolicitudAfiliacion;
}

function mostrarBuscarCiiuWin(destino) {
	divWin = dhtmlwindow.open('divBoxCiiu', 'iframe', '/test.php', 'Aviso', 'width=600px,height=360px,left=4px,top=24px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/solicitud_cotizacion/buscar_ciiu.php?trgt=' + destino, 'Buscar Actividad');
	divWin.show();
}

function recalcularRC(id) {
	with (document)
		getElementById('iframeProcesando').src = '/modules/solicitud_afiliacion/recalcular_rc.php?id=' + id + '&valor=' + getValorSumaAsegurada();
}

function selectNivel(nivel, validar) {
	if (isNaN(nivel))
		return;

	if ((validar) && (nivel == 1)) {
		alert('El nivel 1 no está disponible para ser seleccionado.');
		return;
	}

	with (document) {
		getElementById('nivel').value = nivel;

		getElementById('nivel1').style.backgroundColor = '#fff';
		getElementById('nivel2').style.backgroundColor = '#fff';
		getElementById('nivel3').style.backgroundColor = '#fff';
		getElementById('nivel4').style.backgroundColor = '#fff';

		getElementById('nivel1').style.color = '#808080';
		getElementById('nivel2').style.color = '#808080';
		getElementById('nivel3').style.color = '#808080';
		getElementById('nivel4').style.color = '#808080';

		getElementById('nivel' + nivel).style.backgroundColor = '#0087c4';
		getElementById('nivel' + nivel).style.color = '#fff';
	}
}

function validarSolicitud() {
	with (document) {
		if (!ValidarForm(getElementById('formSolicitudAfiliacion')))
			return false;

		if (getElementById('email').value != '')
			if (getElementById('email').value.indexOf(';') == -1) {
				if (!ValidarEmail(getElementById('email').value)) {
					alert('Por favor, ingrese una dirección de e-mail válida!');
					getElementById('email').focus();
					return false;
				}
			}
			else {
				var aDirs = getElementById('email').value.split(';');
				for (i = 0; i < aDirs.length; i++)
					if (!ValidarEmail(aDirs[i])) {
						alert('Por favor, ingrese una dirección de e-mail válida!');
						getElementById('email').focus();
						return false;
					}
			}

		if ((!formSolicitudAfiliacion.entregaRgrl[0].checked) && (!formSolicitudAfiliacion.entregaRgrl[1].checked)) {
			alert('Debe indicar si entrega RGRL.');
			return false;
		}

		if ((!formSolicitudAfiliacion.suscribeClausulas[0].checked) && (!formSolicitudAfiliacion.suscribeClausulas[1].checked)) {
			alert('Debe indicar si suscribe claúsulas adicionales.');
			return false;
		}

		document.getElementById('imgGuardando').style.display = 'inline';
		document.getElementById('btnGrabar').style.display = 'none';
	}

	return true;
}