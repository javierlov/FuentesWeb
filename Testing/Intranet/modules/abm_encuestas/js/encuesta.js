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
	fieldFocus.className = 'Opcion';
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
	img.alt = 'Eliminar opción';
	img.border = '0';
	img.id = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'BtnBaja';
	img.src = '/images/delete16.png';
	img.style.cursor = 'pointer';
	img.style.marginLeft = '4px';
	img.style.verticalAlign = 'text-bottom';
	img.onclick = new Function('eliminarOpcion(' + numeroPregunta + ', ' + numeroOpcion + ')');
	if (respuestaLibre)
		img.style.visibility = 'hidden';
	else
		img.style.visibility = 'visible';
	p.appendChild(img);

	// Observación..
	var span = document.createElement('span');
	span.id = 'pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'PO';
	span.style.marginLeft = '4px';

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
	field.className = 'PreguntaSiguiente';
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
	if (imagen == '') {
		label.style.color = '#000';
		label.style.cursor = 'default';
	}
	else {
		label.style.color = '#4c4';
		label.style.cursor = 'pointer';
		label.onclick = new Function('verImagen("O", "' + imagen + '")');
	}
	span.appendChild(label);

	var field = document.createElement('input');
	field.className = 'ImagenOpcion';
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

	var label = document.createElement('label');
	label.htmlFor = 'pregunta' + numero;
	label.id = 'pregunta' + numero + 'Label';
	label.innerHTML = 'Pregunta ' + numero;
	p.appendChild(label);

	var fieldFocus = document.createElement('input');
	fieldFocus.className = 'Pregunta';
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
	img.alt = 'Eliminar pregunta';
	img.border = '0';
	img.id = 'pregunta' + numero + 'BtnBaja';
	img.src = '/images/delete16.png';
	img.style.cursor = 'pointer';
	img.style.marginLeft = '4px';
	img.style.marginRight = '4px';
	img.style.verticalAlign = 'text-bottom';
	img.onclick = new Function('eliminarPregunta(' + numero + ')');
	p.appendChild(img);

	var img = document.createElement('img');
	img.alt = 'Agregar opción';
	img.border = '0';
	img.id = 'pregunta' + numero + 'BtnAgregar';
	img.src = '/images/folderopenb16.png';
	img.style.cursor = 'pointer';
	img.style.verticalAlign = 'text-bottom';
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
	p.style.borderBottom = '#9cb5cb dotted thin';
	p.style.marginLeft = '80px';
	p.style.width = '664px';

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
	document.getElementById('grafico').src = '/modules/abm_encuestas/ver_grafico.php?rnd=' + Math.random() + '&preguntaid=' + preguntaid + '&tipografico=' + tipoGrafico;
	document.getElementById('grafico').style.display = 'block';
	document.getElementById('tipoGrafico').style.display = 'block';

	document.getElementById('tipoGraficoBarra').style.background = '#fff';
	document.getElementById('tipoGraficoTorta').style.background = '#fff';
	if (tipoGrafico == 'B')
		document.getElementById('tipoGraficoBarra').style.background = '#c8d2dc';
	if (tipoGrafico == 'T')
		document.getElementById('tipoGraficoTorta').style.background = '#c8d2dc';
}

function checkRespuestaLibre(chk, numeroPregunta) {
	var obj = document.getElementById(chk);

	if (obj.checked) {
		var str = 'Está a punto de marcar esta pregunta como de "Respuesta Libre"';
		str = str + ' lo que hará que se eliminen todas las opciones y sus respectivos votos.';
		str = str + '\n\n¿ Confirma la operación ?';
		if (confirm(str)) {
			document.iframeEncuesta.location.href = '/modules/abm_encuestas/eliminar_opciones.php?eliminar=T&numeropregunta=' + numeroPregunta;
		}
		else
			obj.checked = false;
	}
	else
		document.iframeEncuesta.location.href = '/modules/abm_encuestas/eliminar_opciones.php?numeropregunta=' + numeroPregunta;
}

function darBaja() {
	if (confirm('¿ Realmente desea dar de baja esta encuesta ?')) {
		document.getElementById('tipoOp').value = 'B';
		document.getElementById('formEncuesta').submit();
	}
}

function eliminarOpcion(numeroPregunta, numeroOpcion) {
	idOpcion = document.getElementById('pregunta' + numeroPregunta + 'Opcion' + numeroOpcion + 'Id').value;
	document.iframeEncuesta.location.href = '/modules/abm_encuestas/chequear_opcion_vigente.php?numeropregunta=' + numeroPregunta + '&numeroopcion=' + numeroOpcion + '&opcionid=' + idOpcion;
}

function eliminarPregunta(numeroPregunta) {
	idPregunta = document.getElementById('pregunta' + numeroPregunta + 'Id').value;
	document.iframeEncuesta.location.href = '/modules/abm_encuestas/chequear_pregunta_vigente.php?numeropregunta=' + numeroPregunta + '&preguntaid=' + idPregunta;
}

function seleccionarPregunta(pregid, preg) {
	pregunta = preg;
	preguntaid = pregid;
	cambiarGrafico();
}

function seleccionarTipoGrafico(tipo) {
	tipoGrafico = tipo;
	cambiarGrafico();
}

function seleccionarUsuarios(value) {
	document.iframeEncuesta.location.href = '/modules/abm_encuestas/seleccionar_usuarios.php?idencuesta=' + document.getElementById('id').value + '&tipo=' + value;
}

function validarEnvio(form) {
	if (ValidarForm(form)) {
		if ((document.getElementById('activaAnterior').value == 'T') && (!document.getElementById('activa').checked)) {
			result = confirm('Usted acaba de desactivar la encuesta.\n¿ Confirma la operación ?');
		}
		else if ((document.getElementById('activaAnterior').value == 'F') && (document.getElementById('activa').checked)) {
			result = confirm('Usted acaba de activar la encuesta, eso va a desactivar las otras encuestas.\n¿ Confirma la operación ?');
		}
		else
			result = true;
	}
	else
		result = false;

	if (result)
		// Habilito todos los checkboxs para que se procesen..
		for (i=0; i<form.elements.length; i++)
			if (form.elements[i].type == 'checkbox')
				form.elements[i].disabled = false;

	return result;
}

function verImagen(tipo, img) {
	OpenWindow('modules/abm_encuestas/ver_imagen.php?tipo=' + tipo + '&file=' + img, 'ProvartPopup', 640, 480, 'no', 'no');
}