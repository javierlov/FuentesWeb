function agregar() {
	window.location.href = '/encuestas-abm/0';
}

function agregarOpcion(numeroPregunta, numeroOpcion, id, opcion, observacion, preguntaSiguiente, respuestaLibre, imagen, getFocus) {
	if (numeroOpcion == -1) {
  	for (i=1; i<100; i++)		// No creo que haya mas de 100 opciones..
  		if (document.getElementById('pregunta' + numeroPregunta + 'Opcion' + i) == null) {
  			numeroOpcion = i;
  			break;
  		}
	}


	// Si no hay pregunta siguiente asignada y no hay preguntas posteriores..
	if (preguntaSiguiente == '') {
		if (document.getElementById('divPregunta' + (numeroPregunta + 1)) == null)
			preguntaSiguiente = numeroPregunta + 1;
	}
	else if (preguntaSiguiente == '-1')
		preguntaSiguiente = '';


	// Div general..
	var div = document.createElement('div');
	div.id = 'divPregunta' + numeroPregunta + 'Opcion' + numeroOpcion;

	var p = document.createElement('p');
	p.id = 'separadores';
	p.style.marginLeft = '-120px';

	// Número de opción..
	var label = document.createElement('label');
	label.htmlFor = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	label.id = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'Label';
	label.innerHTML = 'Opción ' + numeroOpcion;
	label.style.marginRight = '8px';
	p.appendChild(label);

	var fieldFocus = document.createElement('input');
	fieldFocus.className = 'opcion';
	fieldFocus.id = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	fieldFocus.maxLength = '256';
	fieldFocus.name = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	fieldFocus.type = 'text';
	fieldFocus.value = opcion;
	p.appendChild(fieldFocus);

	var field = document.createElement('input');
	field.id = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'Id';
	field.name = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'Id';
	field.type = 'hidden';
	field.value = id;
	p.appendChild(field);

	var field = document.createElement('input');
	field.id = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'Baja';
	field.name = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'Baja';
	field.type = 'hidden';
	field.value = 'F';
	p.appendChild(field);

	// Botón eliminar opción..
	var img = document.createElement('img');
	img.border = '0';
	img.id = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'BtnBaja';
	img.src = '/modules/mantenimiento/images/eliminar_grande.png';
	img.style.cursor = 'pointer';
	img.style.height = '16px';
	img.style.marginLeft = '4px';
	img.style.verticalAlign = 'text-bottom';
	img.title = 'Eliminar opción';
	img.onclick = new Function('eliminarOpcion(' + numeroPregunta + ', ' + numeroOpcion + ')');
	if (respuestaLibre)
		img.style.visibility = 'hidden';
	else
		img.style.visibility = 'visible';
	p.appendChild(img);

	// Observación..
	var span = document.createElement('span');
	span.id = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'PO';
	span.style.marginLeft = '8px';

	var label = document.createElement('label');
	label.htmlFor = 'poPregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	label.innerHTML = 'Observación';
	span.appendChild(label);

	var field = document.createElement('input');
	field.defaultChecked = observacion;
	field.disabled = respuestaLibre;
	field.id = 'poPregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	field.name = 'poPregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	field.style.verticalAlign = 'middle';
	field.type = 'checkbox';
	field.value = 'T';
	span.appendChild(field);
	p.appendChild(span);

	// Pregunta siguiente..
	var span = document.createElement('span');
	span.id = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'PS';
	span.style.marginLeft = '8px';

	var label = document.createElement('label');
	label.htmlFor = 'psPregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	label.innerHTML = 'Pregunta siguiente';
	span.appendChild(label);

	var field = document.createElement('input');
	field.className = 'preguntaSiguiente';
	field.id = 'psPregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	field.maxLength = '2';
	field.name = 'psPregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	field.title = 'Pregunta Siguiente';
	field.type = 'text';
	field.value = preguntaSiguiente;
	field.setAttribute('validarEntero', 'true');
	span.appendChild(field);
	p.appendChild(span);

	// Imagen..
	var span = document.createElement('span');
	span.id = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'PI';
	span.style.marginLeft = '4px';

	var label = document.createElement('label');
	label.htmlFor = 'piPregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	label.innerHTML = 'Imagen';
	label.style.marginLeft = '8px';
	if (imagen == '') {
		label.style.color = '#000';
		label.style.cursor = 'pointer';
	}
	else {
		label.style.color = '#4c4';
		label.style.cursor = 'pointer';
		label.onclick = new Function('verImagen("O", "' + imagen + '")');
	}
	span.appendChild(label);

	var field = document.createElement('input');
	field.className = 'imagenOpcion';
	field.disabled = respuestaLibre;
	field.id = 'piPregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	field.name = 'piPregunta' + numeroPregunta + 'Opcion' + numeroOpcion;
	field.title = 'Imagen';
	field.type = 'file';
	field.setAttribute('validarImagen', 'true');
	span.appendChild(field);
	p.appendChild(span);


	div.appendChild(p);
	document.getElementById('divPreguntas' + numeroPregunta).appendChild(div);

	if (getFocus)
		fieldFocus.focus();
}

function agregarPregunta(numero, id, pregunta, multiopcion, respuestalibre, validarCheck, getFocus) {
	if (numero == -1) {
  	for (i=1; i<100; i++)		// No creo que haya mas de 100 preguntas..
  		if (document.getElementById('divPregunta' + i) == null) {
  			numero = i;
  			break;
  		}
	}


	// Div general..
	var div = document.createElement('div');
	div.id = 'divPregunta' + numero;

	// P Nº 1..
	var p = document.createElement('p');
	p.id = 'separadores';
	p.style.marginTop = '12px';

	var label = document.createElement('label');
	label.htmlFor = 'pregunta' + numero;
	label.id = 'pregunta' + numero + 'Label';
	label.innerHTML = 'Pregunta ' + numero;
	label.style.marginLeft = '8px';
	p.appendChild(label);

	var fieldFocus = document.createElement('input');
	fieldFocus.className = 'pregunta';
	fieldFocus.id = 'pregunta' + numero;
	fieldFocus.maxLength = '256';
	fieldFocus.name = 'pregunta' + numero;
	fieldFocus.type = 'text';
	fieldFocus.value = pregunta;
	p.appendChild(fieldFocus);

	var field = document.createElement('input');
	field.id = 'pregunta' + numero + 'Id';
	field.name = 'pregunta' + numero + 'Id';
	field.type = 'hidden';
	field.value = id;
	p.appendChild(field);

	var field = document.createElement('input');
	field.id = 'pregunta' + numero + 'Baja';
	field.name = 'pregunta' + numero + 'Baja';
	field.type = 'hidden';
	field.value = 'F';
	p.appendChild(field);

	var img = document.createElement('img');
	img.border = '0';
	img.id = 'pregunta' + numero + 'BtnBaja';
	img.src = '/modules/mantenimiento/images/eliminar_grande.png';
	img.style.cursor = 'pointer';
	img.style.height = '16px';
	img.style.marginLeft = '4px';
	img.style.marginRight = '4px';
	img.style.verticalAlign = 'text-bottom';
	img.title = 'Eliminar pregunta';
	img.onclick = new Function('eliminarPregunta(' + numero + ')');
	p.appendChild(img);

	var img = document.createElement('img');
	img.border = '0';
	img.id = 'pregunta' + numero + 'BtnAgregar';
	img.src = '/modules/mantenimiento/images/flecha_abajo.png';
	img.style.cursor = 'pointer';
	img.style.height = '16px';
	img.style.verticalAlign = 'text-bottom';
	img.title = 'Agregar opción';
	img.onclick = new Function('agregarOpcion(' + numero + ', -1, -1, "", false, "", false, "", true)');
	if (respuestalibre)
		img.style.visibility = 'hidden';
	else
		img.style.visibility = 'visible';
	p.appendChild(img);
	div.appendChild(p);


	// P Nº 2..
	var p = document.createElement('p');
	p.id = 'separadores';
	p.style.borderBottom = '#000 solid thin';
	p.style.marginLeft = '64px';
	p.style.marginTop = '8px';
	p.style.paddingBottom = '4px';
	p.style.width = '88%';

	var label = document.createElement('label');
	label.htmlFor = 'pregunta' + numero + 'Multi';
	label.id = 'pregunta' + numero + 'MultiLabel';
	label.innerHTML = 'Multi Opción';
	label.style.marginLeft = '48px';
	p.appendChild(label);

	var field = document.createElement('input');
	field.defaultChecked = multiopcion;
	field.id = 'pregunta' + numero + 'Multi';
	field.name = 'pregunta' + numero + 'Multi';
	field.style.verticalAlign = 'middle';
	field.type = 'checkbox';
	field.value = 'T';
	if (respuestalibre)
		field.disabled = true;
	else
		field.disabled = false;
	p.appendChild(field);

	var label = document.createElement('label');
	label.htmlFor = 'pregunta' + numero + 'Libre';
	label.id = 'pregunta' + numero + 'LibreLabel';
	label.innerHTML = 'Respuesta Libre';
	label.style.marginLeft = '24px';
	p.appendChild(label);

	var field = document.createElement('input');
	field.defaultChecked = respuestalibre;
	field.id = 'pregunta' + numero + 'Libre';
	field.name = 'pregunta' + numero + 'Libre';
	field.style.verticalAlign = 'middle';
	field.type = 'checkbox';
	field.value = 'T';
	field.onclick = new Function('checkRespuestaLibre("' + field.id + '", ' + numero + ')');
	p.appendChild(field);

	var label = document.createElement('label');
	label.htmlFor = 'pregunta' + numero + 'ValidarCheck';
	label.id = 'pregunta' + numero + 'ValidarCheckLabel';
	label.innerHTML = 'Validar Check';
	label.style.marginLeft = '48px';
	p.appendChild(label);

	var field = document.createElement('input');
	field.defaultChecked = validarCheck;
	field.id = 'pregunta' + numero + 'ValidarCheck';
	field.name = 'pregunta' + numero + 'ValidarCheck';
	field.style.verticalAlign = 'middle';
	field.type = 'checkbox';
	field.value = 'T';
	if (respuestalibre)
		field.disabled = true;
	else
		field.disabled = false;
	p.appendChild(field);
	div.appendChild(p);


	// P Nº 3..
	var p = document.createElement('p');
	p.id = 'separadores';


	var div2 = document.createElement('div');
	div2.id = 'divPreguntas' + numero;
	div2.style.marginLeft = '132px';
	p.appendChild(div2);
	div.appendChild(p);


	// P Nº 4..
	var p = document.createElement('p');
	p.id = 'separadores';
	p.style.marginLeft = '0';

	var hr = document.createElement('hr');
	hr.id = 'linea';
	p.appendChild(hr);
	div.appendChild(p);


	document.getElementById('preguntas').appendChild(div);

	if (getFocus)
		fieldFocus.focus();
}

function cambiarGrafico() {
	with (document) {
		getElementById('grafico').src = '/modules/mantenimiento/abm_encuestas/ver_grafico.php?rnd=' + Math.random() + '&preguntaid=' + estadisticasPreguntaId + '&tipografico=' + estadisticasTipoGrafico;
		getElementById('grafico').style.display = 'block';
		getElementById('tipoGrafico').style.display = 'block';

		getElementById('tipoGraficoBarra').style.background = '#fff';
		getElementById('tipoGraficoTorta').style.background = '#fff';
		if (estadisticasTipoGrafico == 'B')
			getElementById('tipoGraficoBarra').style.background = '#c8d2dc';
		if (estadisticasTipoGrafico == 'T')
			getElementById('tipoGraficoTorta').style.background = '#c8d2dc';
	}
}

function cancelar() {
	window.location.href = '/encuestas-abm-busqueda/0';
}

function checkRespuestaLibre(chk, numeroPregunta) {
	var obj = document.getElementById(chk);

	if (obj.checked) {
		var str = 'Está a punto de marcar esta pregunta como de "Respuesta Libre", lo que hará que se eliminen todas las opciones y sus respectivos votos.';
		str = str + '\n\n¿ Confirma la operación ?';
		if (confirm(str)) {
			iframeProcesando.location.href = '/modules/mantenimiento/abm_encuestas/eliminar_opciones.php?eliminar=T&numeropregunta=' + numeroPregunta;
		}
		else
			obj.checked = false;
	}
	else
		iframeProcesando.location.href = '/modules/mantenimiento/abm_encuestas/eliminar_opciones.php?numeropregunta=' + numeroPregunta;
}

function contarUsuariosSeleccionados(obj) {
	var options = obj.options, count = 0;
	for (var i=0; i < options.length; i++)
		if (options[i].selected)
			count++;
	document.getElementById('usuariosTitulo').innerHTML = 'Usuarios Autorizados (' + count + ')';
}

function darBaja(id) {
	if (confirm('¿ Realmente desea dar de baja esta encuesta ?'))
		iframeProcesando.location.href = '/modules/mantenimiento/abm_encuestas/dar_baja_encuesta.php?id=' + id;
}

function eliminarOpcion(numeroPregunta, numeroOpcion) {
	idOpcion = document.getElementById('pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'Id').value;
	iframeProcesando.location.href = '/modules/mantenimiento/abm_encuestas/chequear_opcion_vigente.php?numeropregunta=' + numeroPregunta + '&numeroopcion=' + numeroOpcion + '&opcionid=' + idOpcion;
}

function eliminarPregunta(numeroPregunta) {
	idPregunta = document.getElementById('pregunta' + numeroPregunta + 'Id').value;
	iframeProcesando.location.href = '/modules/mantenimiento/abm_encuestas/chequear_pregunta_vigente.php?numeropregunta=' + numeroPregunta + '&preguntaid=' + idPregunta;
}

function guardar() {
	with (document) {
		var continuar = true;

		if (getElementById('baja').value == 't')
			continuar = confirm('Esta encuesta está dada de baja. ¿ Realmente desea reactivarla ?');

		if (continuar)
			if ((getElementById('activaAnterior').value == 'T') && (!getElementById('activa').checked))
				continuar = confirm('Usted acaba de desactivar la encuesta.\n\n¿ Confirma la operación ?');

		if (continuar)
			if ((getElementById('activaAnterior').value == 'F') && (getElementById('activa').checked))
				continuar = confirm('Usted acaba de activar la encuesta, eso va a desactivar las otras encuestas.\n\n¿ Confirma la operación ?');

		if (continuar)
			// Habilito todos los checkboxs para que se procesen..
			for (i=0; i<getElementById('formAbmEncuesta').elements.length; i++)
				if (getElementById('formAbmEncuesta').elements[i].type == 'checkbox')
					getElementById('formAbmEncuesta').elements[i].disabled = false;


		if (continuar) {
			body.style.cursor = 'wait';
			getElementById('btnGuardar').style.display = 'none';
			getElementById('imgProcesando').style.display = 'inline';
			getElementById('formAbmEncuesta').submit();
		}
	}
}

function mostrarObservaciones(idpregunta) {
	with (document.getElementById('tableObservaciones_' + idpregunta)) {
		if (style.display == 'inline-table')
			style.display = 'none';
		else
			style.display = 'inline-table';
	}
}

function seleccionarPregunta(pregid, preg) {
	estadisticasPregunta = preg;
	estadisticasPreguntaId = pregid;
	cambiarGrafico();
}

function seleccionarTipoGrafico(tipo) {
	estadisticasTipoGrafico = tipo;
	cambiarGrafico();
}

function seleccionarUsuarios(tipo) {
	iframeProcesando.location.href = '/modules/mantenimiento/abm_encuestas/seleccionar_usuarios.php?idencuesta=' + document.getElementById('id').value + '&tipo=' + tipo;
}

function setImagenCabecera(imgName) {
	with (document) {
		getElementById('fileImgCabecera').value = imgName;
		iframeProcesando.location.href = '/modules/mantenimiento/abm_encuestas/mostrar_imagen.php?img=' + imgName;
	}
}

function verImagen(tipo, img) {
	OpenWindow('/modules/mantenimiento/abm_encuestas/ver_imagen.php?tipo=' + tipo + '&file=' + img, 'ProvartPopup', 640, 480, 'no', 'no');
}

function verRespuestasXUsuarios(id) {
	iframeExcel.location.href = '/modules/mantenimiento/abm_encuestas/respuestas_por_usuarios.php?id=' + id + '&rnd=' + Math.random();
}

function vistaPrevia(id) {
	window.open('/encuestas/' + id + '/T');
}


var estadisticasPregunta = '';
var estadisticasPreguntaId = 0;
var estadisticasTipoGrafico = 'T';
var totalAutorizados = 0;