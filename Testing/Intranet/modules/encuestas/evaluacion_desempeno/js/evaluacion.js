function agregarCompromisoMejora(document, id, value, validar) {
	if ((validar) && (value == '')) {
		alert('Debe ingresar un texto.');
		return false;
	}

	// Obtengo el siguiente número de item a agregar..
	numCompromiso = parseInt(document.getElementById('tableCompromisosMejora').totItems) + 1;

	// Clono el nodo..
	node = document.getElementById('trCompromisosMejora3').cloneNode(true);

	// Seteo el valor de los atributos del nuevo nodo..
	node.id = 'trCompromisosMejora' + numCompromiso;
	node.name = node.id;
	node.childNodes[0].firstChild.innerHTML = numCompromiso + '.';

	// Seteo el valor del input del id..
	node.childNodes[1].firstChild.id = 'CompromisoMejoraId' + numCompromiso;
	node.childNodes[1].firstChild.name = node.childNodes[1].firstChild.id;
	node.childNodes[1].firstChild.value = id;

	// Seteo el valor del input del texto..
	node2 = node.childNodes[1].firstChild.nextSibling;
	node2.id = 'CompromisoMejora' + numCompromiso;
	node2.name = node2.id;
	node2.value = value;

	// Agrego el nuevo nodo..
	document.getElementById('tableCompromisosMejora').appendChild(node);
	document.getElementById('tableCompromisosMejora').totItems++;

	return true;
}

function agregarEvento(document, tipoEvento, fecha, evento) {
	// Clono el nodo..
	node = document.getElementById('trEvento' + tipoEvento + 'Ejemplo').cloneNode(true);

	// Seteo el valor de los atributos del nuevo nodo..
	node.style.display = 'block';
	node.childNodes[0].firstChild.innerHTML = evento;
	node.childNodes[1].firstChild.innerHTML = fecha;

	// Agrego el nuevo nodo..
	document.getElementById('tableEventos' + tipoEvento).appendChild(node);
}

function cambiarIdentidad() {
	var height = 288;
	var width = 248;
	var left = ((screen.width - width) / 2) + 52;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/encuestas/evaluacion_desempeno/cambiar_identidad.php', 'Cambiar Identidad');
	divWin.show();
}

function cambiarUsuarioAEvaluar(usuarioAEvaluar, ano) {
	document.getElementById('iframeEvaluacion').src = 'cambiar_usuario.php?user=' + usuarioAEvaluar + '&ano=' + ano + '&rnd=' + Math.random();
}

function enableAllControls() {
	with (window.parent.document) {
		/* COMIENZO COMPETENCIAS */
		for (i=0;i<=4;i++) {
			if (i <= 2)
				getElementById('formEvaluacion').Competencias[i].disabled = false;
			getElementById('formEvaluacion').Orientacion[i].disabled = false;
			getElementById('formEvaluacion').Adaptabilidad[i].disabled = false;
			getElementById('formEvaluacion').TrabajoEnEquipo[i].disabled = false;
			getElementById('formEvaluacion').OrientacionAlCliente[i].disabled = false;
			getElementById('formEvaluacion').Liderazgo[i].disabled = false;
			getElementById('formEvaluacion').CapacidadPlanificacion[i].disabled = false;
			getElementById('formEvaluacion').PensamientoAnalitico[i].disabled = false;

			getElementById('formEvaluacion').OrientacionEsp[i].disabled = false;
			getElementById('formEvaluacion').AdaptabilidadEsp[i].disabled = false;
			getElementById('formEvaluacion').TrabajoEnEquipoEsp[i].disabled = false;
			getElementById('formEvaluacion').OrientacionAlClienteEsp[i].disabled = false;
			getElementById('formEvaluacion').LiderazgoEsp[i].disabled = false;
			getElementById('formEvaluacion').CapacidadPlanificacionEsp[i].disabled = false;
			getElementById('formEvaluacion').PensamientoAnaliticoEsp[i].disabled = false;

			getElementById('formEvaluacion').OrientacionFuturo[i].disabled = false;
			getElementById('formEvaluacion').AdaptabilidadFuturo[i].disabled = false;
			getElementById('formEvaluacion').TrabajoEnEquipoFuturo[i].disabled = false;
			getElementById('formEvaluacion').OrientacionAlClienteFuturo[i].disabled = false;
			getElementById('formEvaluacion').LiderazgoFuturo[i].disabled = false;
			getElementById('formEvaluacion').CapacidadPlanificacionFuturo[i].disabled = false;
			getElementById('formEvaluacion').PensamientoAnaliticoFuturo[i].disabled = false;
		}

		getElementById('trOrientacionEsp').style.display = 'block';
		getElementById('trAdaptabilidadEsp').style.display = 'block';
		getElementById('trTrabajoEnEquipoEsp').style.display = 'block';
		getElementById('trOrientacionAlClienteEsp').style.display = 'block';
		getElementById('trLiderazgoEsp').style.display = 'block';
		getElementById('trCapacidadPlanificacionEsp').style.display = 'block';
		getElementById('trPensamientoAnaliticoEsp').style.display = 'block';

		getElementById('OrientacionObservaciones').readOnly = false;
		getElementById('AdaptabilidadObservaciones').readOnly = false;
		getElementById('TrabajoEnEquipoObservaciones').readOnly = false;
		getElementById('OrientacionAlClienteObservaciones').readOnly = false;
		getElementById('LiderazgoObservaciones').readOnly = false;
		getElementById('CapacidadPlanificacionObservaciones').readOnly = false;
		getElementById('PensamientoAnaliticoObservaciones').readOnly = false;

		getElementById('trOrientacionFuturo').style.display = 'block';
		getElementById('trAdaptabilidadFuturo').style.display = 'block';
		getElementById('trTrabajoEnEquipoFuturo').style.display = 'block';
		getElementById('trOrientacionAlClienteFuturo').style.display = 'block';
		getElementById('trLiderazgoFuturo').style.display = 'block';
		getElementById('trCapacidadPlanificacionFuturo').style.display = 'block';
		getElementById('trPensamientoAnaliticoFuturo').style.display = 'block';
		/* FIN COMPETENCIAS */
		
		/* COMIENZO OBJETIVOS */
		getElementById('Objetivo1DescripcionFuturo').readOnly = false;
		getElementById('Objetivo1ResultadoAObtenerFuturo').readOnly = false;
		getElementById('Objetivo1IndicadorFuturo').readOnly = false;
		getElementById('Objetivo1PlazoEjecucionFuturo').readOnly = false;
		getElementById('Objetivo2DescripcionFuturo').readOnly = false;
		getElementById('Objetivo2ResultadoAObtenerFuturo').readOnly = false;
		getElementById('Objetivo2IndicadorFuturo').readOnly = false;
		getElementById('Objetivo2PlazoEjecucionFuturo').readOnly = false;

		getElementById('btnModificarObjetivo1').style.display = 'block';
		getElementById('btnGuardarObjetivo1').style.display = 'block';
		getElementById('btnModificarObjetivo2').style.display = 'block';
		getElementById('btnGuardarObjetivo2').style.display = 'block';
		/* FIN OBJETIVOS */

		/* COMIENZO COMPROMISOS */
		for (i=1;i<=getElementById('tableCompromisosMejora').totItems;i++)
			getElementById('CompromisoMejora' + i).readOnly = false;
		getElementById('btnAgregarActividad').style.display = 'block';
		getElementById('CompromisoMejoraNuevoItem').style.display = 'block';
		/* FIN COMPROMISOS */

		/* COMIENZO SEGUIMIENTO */
		getElementById('divSeguimientoTitulo').mostrar = 'si';
		getElementById('divSeguimientoTitulo').style.display = 'block';
		getElementById('btnInsertarEvento').style.display = 'block';
		/* FIN SEGUIMIENTO */

		getElementById('btnGuardar').style.display = 'block';
		getElementById('btnEnviarEvaluacion').style.display = 'block';
		getElementById('btnMeNotifique').style.display = 'block';

		getElementById('ComentariosEvaluado').readOnly = false;
		getElementById('ComentariosEvaluador').readOnly = false;
		getElementById('ComentariosSupervisor').readOnly = false;
	}
}

function enviarEvaluacion() {
	if (validarFormEvaluacion(formEvaluacion))
		if (confirm('Una vez enviada la evaluación usted no la podrá modificar.\n\n¿ Confirma el envío ?')) {
			document.getElementById('CerrarEvaluacion').value = true;
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
	if (!validarFormEvaluacion(formEvaluacion))
		return false;

	obj = document.getElementById('promedioEvaluacionIntegradora');
	if (!ValidarEntero(obj.value)) {
		alert('Por favor, ingrese un valor válido!');
		obj.focus();
		return false;
	}

	enviarForm();
}

function guardarObjetivo(num) {
	if (isNaN(parseInt(document.getElementById('Objetivo' + num + 'Id').value))) {
		alert('Antes de modificar un objetivo debe guardar la evaluación.');
		return false;
	}

	obj = document.getElementById('porcentajeCumplimiento' + num);
	if (!ValidarEntero(obj.value)) {
		alert('Por favor, ingrese un valor válido!');
		obj.focus();
		return false;
	}

	obj = document.getElementById('formEvaluacion')['Objetivo' + num + 'Estado'];
	if (!ValidarRadioButton(obj))
		return false;

	estado = '';
	for (var i=0; i<obj.length; i++)
		if (obj[i].checked) {
			estado = obj[i].value;
			break;
		}

	document.getElementById('iframeEvaluacion').src = 'guardar_objetivo.php?formularioid=' + document.getElementById('FormularioId').value + '&num=' + num + '&porcentaje=' + document.getElementById('porcentajeCumplimiento' + num).value + '&estado=' + estado;
}

function imprimirEvaluacion() {
	// Despliego los divs y habilito todos los controles para que salgan bien en la impresión..
  document.getElementById("divCompetencias").style.display = "block";
  document.getElementById("divObjetivos").style.display = "block";
  document.getElementById("divCompromisosMejora").style.display = "block";
  if (document.getElementById('divSeguimientoTitulo').mostrar != 'no')
  	document.getElementById("divSeguimiento").style.display = "block";
  enableAllControls();

	// Agrando todos los textarea..
	with (document) {
		resizeTextarea(getElementById('OrientacionObservaciones'));
		resizeTextarea(getElementById('AdaptabilidadObservaciones'));
		resizeTextarea(getElementById('TrabajoEnEquipoObservaciones'));
		resizeTextarea(getElementById('OrientacionAlClienteObservaciones'));
		resizeTextarea(getElementById('LiderazgoObservaciones'));
		resizeTextarea(getElementById('CapacidadPlanificacionObservaciones'));
		resizeTextarea(getElementById('PensamientoAnaliticoObservaciones'));
		resizeTextarea(getElementById('Objetivo1Descripcion'));
		resizeTextarea(getElementById('Objetivo1ResultadoAObtener'));
		resizeTextarea(getElementById('Objetivo1Indicador'));
		resizeTextarea(getElementById('Objetivo1PlazoEjecucion'));
		resizeTextarea(getElementById('Objetivo2Descripcion'));
		resizeTextarea(getElementById('Objetivo2ResultadoAObtener'));
		resizeTextarea(getElementById('Objetivo2Indicador'));
		resizeTextarea(getElementById('Objetivo2PlazoEjecucion'));
		resizeTextarea(getElementById('Objetivo1DescripcionFuturo'));
		resizeTextarea(getElementById('Objetivo1ResultadoAObtenerFuturo'));
		resizeTextarea(getElementById('Objetivo1IndicadorFuturo'));
		resizeTextarea(getElementById('Objetivo1PlazoEjecucionFuturo'));
		resizeTextarea(getElementById('Objetivo2DescripcionFuturo'));
		resizeTextarea(getElementById('Objetivo2ResultadoAObtenerFuturo'));
		resizeTextarea(getElementById('Objetivo2IndicadorFuturo'));
		resizeTextarea(getElementById('Objetivo2PlazoEjecucionFuturo'));
		resizeTextarea(getElementById('ComentariosEvaluado'));
		resizeTextarea(getElementById('ComentariosEvaluador'));
		resizeTextarea(getElementById('ComentariosSupervisor'));
	}

  if ((navigator.appName == "Netscape"))
    window.print();
  else {
    var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
    document.body.insertAdjacentHTML('beforeEnd', WebBrowser);
    WebBrowser1.ExecWB(6, -1);
    WebBrowser1.outerHTML = "";
  }

	// Contraigo los divs..
  document.getElementById("divCompetencias").style.display = "none";
  document.getElementById("divObjetivos").style.display = "none";
  document.getElementById("divCompromisosMejora").style.display = "none";
  document.getElementById("divSeguimiento").style.display = "none";

	// Recargo al usuario para que muestre u oculte lo que corresponda..
	cambiarUsuarioAEvaluar(document.getElementById('UsuarioAEvaluar').value, document.getElementById('Ano').value);
}

function insertarEvento() {
	OpenWindow('agregar_evento.php?formularioid=' + document.getElementById('FormularioId').value, 'ProvartPopup', 616, 232, 'no', 'no');
}

function modificarObjetivo(num) {
	if (isNaN(parseInt(document.getElementById('Objetivo' + num + 'Id').value))) {
		alert('Antes de modificar un objetivo debe guardar la evaluación.');
		return;
	}

	if ((document.getElementById('Objetivo' + num + 'Descripcion').value == '') && 
			(document.getElementById('Objetivo' + num + 'ResultadoAObtener').value == '') && 
			(document.getElementById('Objetivo' + num + 'Indicador').value == '') && 
			(document.getElementById('Objetivo' + num + 'PlazoEjecucion').value == '')) {
		alert('Antes de modificar objetivos, debe cargar los objetivos originales.');
		return;
	}

	OpenWindow('modificar_objetivo.php?formularioid=' + document.getElementById('FormularioId').value + '&id=' + document.getElementById('Objetivo' + num + 'Id').value + '&ano=' + document.getElementById('Ano').value + '&num=' + num, 'ProvartPopup', 616, 500, 'no', 'no');
}

function mostrarSeccion(seccion) {
	mostrar = (document.getElementById(seccion).style.display == 'none');
	ocultarSecciones();
	if (mostrar)
		document.getElementById(seccion).style.display = 'block';
	else
		document.getElementById(seccion).style.display = 'none';
}

function notificarEvaluacion() {
	if (confirm('Una vez enviado su comentario usted no lo podrá modificar.\n\n¿ Confirma el envío ?')) {
		document.getElementById('CerrarEvaluacion').value = true;
		enviarForm();
	}
}

function ocultarSecciones() {
	document.getElementById('divCompetencias').style.display = 'none';
	document.getElementById('divObjetivos').style.display = 'none';
	document.getElementById('divCompromisosMejora').style.display = 'none';
	document.getElementById('divSeguimiento').style.display = 'none';
}

function removerCompromisosMejora(document) {
	node = document.getElementById('tableCompromisosMejora').lastChild;
	while (node.id != 'trCompromisosMejora3') {
		document.getElementById('tableCompromisosMejora').removeChild(node);
		node = document.getElementById('tableCompromisosMejora').lastChild;
	}
	document.getElementById('tableCompromisosMejora').totItems = 3;
}

function removerEventos(document, tipoEvento) {
	table = document.getElementById('tableEventos' + tipoEvento);
	while (table.childNodes.length > 1) {
		node = table.lastChild;
		table.removeChild(node);
	}
}

function uncheckRadioControls() {
	with (window.parent.document) {
		for (i=0;i<=4;i++) {
			if (i <= 2)
				getElementById('formEvaluacion').Competencias[i].checked = false;
			getElementById('formEvaluacion').Orientacion[i].checked = false;
			getElementById('formEvaluacion').Adaptabilidad[i].checked = false;
			getElementById('formEvaluacion').TrabajoEnEquipo[i].checked = false;
			getElementById('formEvaluacion').OrientacionAlCliente[i].checked = false;
			getElementById('formEvaluacion').Liderazgo[i].checked = false;
			getElementById('formEvaluacion').CapacidadPlanificacion[i].checked = false;
			getElementById('formEvaluacion').PensamientoAnalitico[i].checked = false;

			getElementById('formEvaluacion').OrientacionEsp[i].checked = false;
			getElementById('formEvaluacion').AdaptabilidadEsp[i].checked = false;
			getElementById('formEvaluacion').TrabajoEnEquipoEsp[i].checked = false;
			getElementById('formEvaluacion').OrientacionAlClienteEsp[i].checked = false;
			getElementById('formEvaluacion').LiderazgoEsp[i].checked = false;
			getElementById('formEvaluacion').CapacidadPlanificacionEsp[i].checked = false;
			getElementById('formEvaluacion').PensamientoAnaliticoEsp[i].checked = false;

			getElementById('formEvaluacion').OrientacionFuturo[i].checked = false;
			getElementById('formEvaluacion').AdaptabilidadFuturo[i].checked = false;
			getElementById('formEvaluacion').TrabajoEnEquipoFuturo[i].checked = false;
			getElementById('formEvaluacion').OrientacionAlClienteFuturo[i].checked = false;
			getElementById('formEvaluacion').LiderazgoFuturo[i].checked = false;
			getElementById('formEvaluacion').CapacidadPlanificacionFuturo[i].checked = false;
			getElementById('formEvaluacion').PensamientoAnaliticoFuturo[i].checked = false;
		}

		for (i=0;i<=3;i++) {
			getElementById('formEvaluacion').Objetivo1Estado[i].checked = false;
			getElementById('formEvaluacion').Objetivo2Estado[i].checked = false;
		}
	}
}

function validarFormEvaluacion(form) {
	if (!ValidarForm(form))
		return false;

	if ((form.Orientacion[0].checked) && (document.getElementById('OrientacionObservaciones').value == '')) {
		alert('Por favor ingrese un ejemplo sobre Orientación a los resultados.');
		document.getElementById('divCompetencias').style.display = 'block';
    document.getElementById('OrientacionObservaciones').focus();
    return false;
  }

	if ((form.Adaptabilidad[0].checked) && (document.getElementById('AdaptabilidadObservaciones').value == '')) {
		alert('Por favor ingrese un ejemplo sobre Adaptabilidad al cambio.');
		document.getElementById('divCompetencias').style.display = 'block';
    document.getElementById('AdaptabilidadObservaciones').focus();
    return false;
  }

	if ((form.TrabajoEnEquipo[0].checked) && (document.getElementById('TrabajoEnEquipoObservaciones').value == '')) {
		alert('Por favor ingrese un ejemplo sobre Trabajo en equipo.');
		document.getElementById('divCompetencias').style.display = 'block';
    document.getElementById('TrabajoEnEquipoObservaciones').focus();
    return false;
  }

	if ((form.OrientacionAlCliente[0].checked) && (document.getElementById('OrientacionAlClienteObservaciones').value == '')) {
		alert('Por favor ingrese un ejemplo sobre Orientación al cliente interno y externo.');
		document.getElementById('divCompetencias').style.display = 'block';
    document.getElementById('OrientacionAlClienteObservaciones').focus();
    return false;
  }

	if (document.getElementById('ValidarCompetenciasConduccion').value == 'true') {
		if ((form.Liderazgo[0].checked) && (document.getElementById('LiderazgoObservaciones').value == '')) {
			alert('Por favor ingrese un ejemplo sobre Liderazgo.');
			document.getElementById('divCompetencias').style.display = 'block';
    	document.getElementById('LiderazgoObservaciones').focus();
    	return false;
    }

		if ((form.CapacidadPlanificacion[0].checked) && (document.getElementById('CapacidadPlanificacionObservaciones').value == '')) {
			alert('Por favor ingrese un ejemplo sobre Capacidad de Planificación y organización.');
			document.getElementById('divCompetencias').style.display = 'block';
    	document.getElementById('CapacidadPlanificacionObservaciones').focus();
    	return false;
    }

		if ((form.PensamientoAnalitico[0].checked) && (document.getElementById('PensamientoAnaliticoObservaciones').value == '')) {
			alert('Por favor ingrese un ejemplo sobre Capacidad de Pensamiento analítico.');
			document.getElementById('divCompetencias').style.display = 'block';
    	document.getElementById('PensamientoAnaliticoObservaciones').focus();
    	return false;
    }
  }

	if ((document.getElementById('CompromisoMejora1').value == '') &&
			(document.getElementById('CompromisoMejora2').value == '') &&
			(document.getElementById('CompromisoMejora3').value == '')) {
		alert('Debe completar algún compromiso de mejora.');
		document.getElementById('divCompromisosMejora').style.display = 'block';
    document.getElementById('CompromisoMejora1').focus();
    return false;
   }

	// Guardo el estado del objetivo 1 temporalmente..
	obj = document.getElementById('formEvaluacion')['Objetivo1Estado'];
	var estado = '';
	for (var i=0; i<obj.length; i++)
		if (obj[i].checked) {
			estado = obj[i].value;
			break;
		}
	document.getElementById('estadoObjetivo1Tmp').value = estado;

	// Guardo el estado del objetivo 2 temporalmente..
	obj = document.getElementById('formEvaluacion')['Objetivo2Estado'];
	estado = '';
	for (var i=0; i<obj.length; i++)
		if (obj[i].checked) {
			estado = obj[i].value;
			break;
		}
	document.getElementById('estadoObjetivo2Tmp').value = estado;

	return true;
}

function verHistorico(num) {
	OpenWindow('historico.php?formularioid=' + document.getElementById('FormularioId').value + '&num=' + num, 'ProvartPopup', 632, 400, 'no', 'no');
}