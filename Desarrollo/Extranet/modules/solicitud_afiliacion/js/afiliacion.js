function abrirVentanaEstablecimiento(idModulo, idSolicitud, id) {
	if (id < 1)
		caption = 'Alta Establecimiento';
	else
		caption = 'Modificación Establecimiento';


	height = 560;
	width = 688;

	var left = ((screen.width - width) / 2) + 52;
//	var top = ((screen.height - height) / 2) - window.screenTop;
	var top = 40;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxEstablecimiento', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1, scrolling=1');
	divWin.load('iframe', '/modules/solicitud_afiliacion/establecimiento.php?idModulo=' + idModulo + '&idSolicitud=' + idSolicitud + '&id=' + id, caption);
	divWin.show();
}

function abrirVentanaEstablecimientoPCP(idModulo, idSolicitud, id) {
	if (id < 1)
		caption = 'Alta Lugar de Trabajo';
	else
		caption = 'Modificación Lugar de Trabajo';


	height = 280;
	width = 688;

	var left = ((screen.width - width) / 2) + 52;
	var top = 88;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxEstablecimiento', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1, scrolling=1');
	divWin.load('iframe', '/modules/solicitud_afiliacion/establecimiento_pcp.php?idModulo=' + idModulo + '&idSolicitud=' + idSolicitud + '&id=' + id, caption);
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

function buscarEstablecimientos(cuit, idsolicitud) {
	document.getElementById('iframeProcesando').src = '/modules/solicitud_afiliacion/buscar_establecimientos_srt.php?rnd=' + Math.random() + '&c=' + cuit + '&idsolicitud=' + idsolicitud;
}

function cambiaEstablecimiento(valor) {
	document.getElementById('tituloFechaFinObra').innerHTML = (valor == 'O')?'F. Finalización de la Obra (*)':'F. Finalización de la Obra';
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

function cambiarLocalidad(valor) {
	with (document)
		if (valor == -1) {
			getElementById('localidadCombo').style.display = 'none';
			getElementById('localidad').style.display = 'inline';
			getElementById('localidad').value = '';
			getElementById('localidad').focus();
		}
		else {
			getElementById('localidad').value = getElementById('localidadCombo').value;

			if ((getElementById('codigoPostal').value == '') || (getElementById('provincia').value == -1))
				getElementById('iframeProcesando').src = '/modules/solicitud_afiliacion/cambiar_combo_localidad.php?cp=' + getElementById('codigoPostal').value + '&p=' + getElementById('provincia').value + '&l=' + getElementById('localidad').value + '&c=' + getElementById('calle').value;
		}
}

function cargarComboLocalidad() {
	with (document)
		getElementById('iframeProcesando').src = '/modules/solicitud_afiliacion/cargar_combo_localidad.php?cp=' + getElementById('codigoPostal').value + '&p=' + getElementById('provincia').value + '&l=' + getElementById('localidad').value;
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

function clickExtintorOtro() {
	with (document) {
		getElementById("extintor1").checked = false;
		getElementById("extintor2").checked = false;
		getElementById("extintor3").checked = false;
	}
}

function clickIncendioN() {
	document.getElementById("extintor1").checked = false;
	document.getElementById("extintor2").checked = false;
	document.getElementById("extintor3").checked = false;
	clickOptionCleanText('extintorCual');
}

function clickOptionCleanText(idcontrol) {
	document.getElementById(idcontrol).value = '';
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

		// INICIO - Harcodeo por pedido vía oral de JBalestrini el 11.4.2014..
		if (fecha == '11/04/2014') {
			dia = '01';
			mes = '05';
			ano = '2014';
			getElementById('fechaVigenciaDesde').value = '01/05/2014';
		}
		// FIN - Harcodeo por pedido vía oral de JBalestrini el 11.4.2014..

    // EJV (21/10/2014) No importa que Fecha de Sucripcion sea la Vigencia de PCP no puede ser menor al 03/11/2014
    // Inicio solo para PCP
  	if (document.getElementById('soloPCP').value == 'S') {
    		if (fecha.substr(6, 4)+'/'+fecha.substr(3, 2)+'/'+fecha.substr(0, 2) < '2014/11/03') {
    			dia = '03';
    			mes = '11';
    			ano = '2014';
    			getElementById('fechaVigenciaDesde').value = '03/11/2014';
    		}
    }    

		// Fecha vigencia hasta..
		dFecha = new Date(ano, (mes - 1), dia);
		dFecha.setFullYear((dFecha.getFullYear() + 1));		// Le sumo un año..
		dFecha.setTime(dFecha.getTime() - (1000 * 60 * 60 * 24));		// Le resto un día..

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

function eliminarEstablecimientoPCP(id) {
	if (confirm('¿ Realmente desea dar de baja este lugar de trabajo ?'))
		iframeProcesando.location.href = '/modules/solicitud_afiliacion/eliminar_establecimiento_pcp.php?id=' + id;
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
	document.getElementById('iframeProcesando').src = '/modules/solicitud_afiliacion/validar_total_rgrl.php?id=' + id + '&idSolicitudAfiliacion=' + idSolicitudAfiliacion + '&soloPCP=' + document.getElementById('soloPCP').value;
}

function Inicializar() {
	if (document.getElementById('soloPCP').value == 'S') {
		document.getElementById('incendioN').setAttribute("onclick", "javascript:clickIncendioN()");

		document.getElementById('extintor1').setAttribute("onclick", "javascript:clickOptionCleanText('extintorCual')");
		document.getElementById('extintor2').setAttribute("onclick", "javascript:clickOptionCleanText('extintorCual')");
		document.getElementById('extintor3').setAttribute("onclick", "javascript:clickOptionCleanText('extintorCual')");

		if (isCheckedControl('extintor1') || isCheckedControl('extintor2') || isCheckedControl('extintor3'))
			clickOptionCleanText('extintorCual');

		document.getElementById('extintorCual').setAttribute("onclick", "javascript:clickExtintorOtro()");
		document.getElementById('extintorCual').setAttribute("onchange", "javascript:clickExtintorOtro()");
	}
}

function isCheckedControl(idcontrol) {
	return document.getElementById(idcontrol).checked;
}

function mostrarBuscarCiiuWin(destino) {
	divWin = dhtmlwindow.open('divBoxCiiu', 'iframe', '/test.php', 'Aviso', 'width=600px,height=360px,left=4px,top=24px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/solicitud_cotizacion/buscar_ciiu.php?trgt=' + destino, 'Buscar Actividad');
	divWin.show();
}

function mostrarSoloPCP(mostrar) {
	var displayAlReves = (mostrar)?'block':'none';
	var displayBlock = (mostrar)?'none':'block';
	var displayInline = (mostrar)?'none':'inline';

	var elems1_1 = ['divBonificacionesEspeciales', 'divBonificacionesEspecialesTitulo', 'divCargoSexoResponsable', 'divNivel','divResponsableArtTitulo', 'divSuscribeClausulas',
									'divTelefonosResponsable'];
	var elems1_2 = ['actividadPrincipal', 'condicionAnteAfip', 'divAlicuotaDefault', 'divCantidadEstablecimientos', 'divEmailResponsable', 'divEntregaRgrl', 'divEstablecimientosRgrlImpreso',
									'divFormulario883', 'divNombreApellidoResponsable', 'formaJuridica', 'labelActividadPrincipal', 'labelCondicionAnteAfip', 'labelFormaJuridica', 'observaciones',
									'spanObservaciones'];
	var elems2 = ['divAlicuotaPCP', 'divTareasRiesgosLaborales', 'divTareasRiesgosLaboralesTitulo', 'spanTextoVigencia'];

	with (document) {
		for	(i=0; i<elems1_1.length; i++)
			getElementById(elems1_1[i]).style.display = displayBlock;

		for	(i=0; i<elems1_2.length; i++)
			getElementById(elems1_2[i]).style.display = displayInline;

		for	(i=0; i<elems2.length; i++)
			getElementById(elems2[i]).style.display = displayAlReves;

		if (mostrar) {
			getElementById('divActividadPrincipal').style.position = 'relative';
			getElementById('divActividadPrincipal').style.marginLeft = '280px';
			getElementById('divActividadPrincipal').style.marginTop = '-30px';
			getElementById('divAlicuotaTitulo').innerHTML+= ' - SEGÚN RANGO DE HORAS TRABAJADAS SEMANALMENTE CONFORME FORMULARIO AFIP 102/8';
			getElementById('divEstablecimientosTitulo').innerHTML = '4. DETALLE DE LUGARES DE TRABAJO';
			getElementById('divLugarFechaSuscripcion').innerHTML = '8. LUGAR Y FECHA DE SUSCRIPCIÓN';

			document.getElementById('divPrincipalSolicitudAfiliacion').insertBefore(getElementById('divEstablecimientosTitulo'), getElementById('divClausulaPenaltitulo'));
			document.getElementById('divPrincipalSolicitudAfiliacion').insertBefore(getElementById('divEstablecimientos'), getElementById('divClausulaPenaltitulo'));
		}
	}
	
	soloPCPcontrols();
}

function soloPCPcontrols(){
	var elementsMail = ['emailResponsable'];
	var elementsValidar = [];

	for	(i=0; i<elementsMail.length; i++)
		document.getElementById(elementsMail[i]).setAttribute("validarEmail", "False");

	for	(i=0; i<elementsValidar.length; i++)
		document.getElementById(elementsValidar[i]).setAttribute("validar", "False");
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

		if (getElementById('nivel' + nivel) != null) {
			getElementById('nivel' + nivel).style.backgroundColor = '#0087c4';
			getElementById('nivel' + nivel).style.color = '#fff';
		}
	}
}

function setLocalidad(localidad) {
	with (document)
		if (localidad == '') {
			getElementById('localidadCombo').style.display = 'inline';
			getElementById('localidad').style.display = 'none';
		}
		else {
			getElementById('localidadCombo').style.display = 'none';
			getElementById('localidad').style.display = 'inline';
		}
}

function validarInconsistenciasPCP() {
	if (isCheckedControl('incendioN')) {
		clickExtintorOtro();

		if (document.getElementById('extintorCual').value == '')
			document.getElementById('extintorCual').value = '.';
	}
}

function validarSolicitud() {
	var isSoloPCP = false;
	if (document.getElementById('soloPCP').value == 'S')
		isSoloPCP = true;

	with (document) {
		if (isSoloPCP)		// Valida estos controles solo si es PCP
			validarInconsistenciasPCP();

		document.getElementById('imgGuardando').style.display = 'inline';
		document.getElementById('btnGrabar').style.display = 'none';
	}

	return true;
}

function verMapa() {
	var direccion = '';

	with (document) {
		direccion+= getElementById('calle').value;
		direccion+= ' ' + getElementById('numero').value;
		direccion+= ', ' + getElementById('localidad').value;
		direccion+= ', ' + getElementById('provincia').value;
		direccion+= ', Argentina';
	}

	height = window.parent.divWin.offsetHeight - 180;
	width = window.parent.divWin.offsetWidth - 16;

	var left = 0;
	var top = 0;

	divWinMapa = null;
	divWinMapa = dhtmlwindow.open('divMapa', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1, scrolling=1');
	divWinMapa.load('iframe', '/modules/solicitud_afiliacion/ver_mapa.php?d=' + direccion + '&la=' + document.getElementById('latitud').value + '&lo=' + document.getElementById('longitud').value, 'Buscar Dirección en Mapa');
	divWinMapa.show();
}