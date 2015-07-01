function abrirVentanaRGRL(id) {
	height = 520;
	width = 640;

	var left = (window.innerWidth / 2) - (width / 2);
//	var top = (window.innerHeight / 2) - (height / 2);
	var top = 40;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxRGRL', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1, scrolling=1');
	divWin.load('iframe', '/modules/usuarios_registrados/clientes/rgrl/rgrl.php?ide=' + id, 'Relevamiento General de Riesgos Laborales');
	divWin.show();
}

function aceptarContratista(obj) {
	var num = obj.id.substr(11);

	with (document) {
		if (!ValidarCampoTexto(getElementById('cuit_' + num)))
			return false;
		if (!ValidarCuit(getElementById('cuit_' + num).value)) {
			alert('Por favor, ingrese una C.U.I.T. válida.');
			getElementById('cuit_' + num).focus();
			return false;
		}


		getElementById('spanCuit_' + num).innerHTML = getElementById('cuit_' + num).value;
	}

	// Oculto una fila y muestro la otra..
	var rowOn = obj.parentNode.parentNode;
	var rowOff = rowOn.previousSibling;

	rowOn.childNodes[0].style.display = 'none';
	rowOn.childNodes[1].style.display = 'none';

	rowOff.childNodes[0].style.display = 'block';
	rowOff.childNodes[1].style.display = 'block';
}

function aceptarDelegadoGremial(obj) {
	var num = obj.id.substr(11);

	with (document) {
		if (!ValidarCampoTexto(getElementById('numeroLegajo_' + num)))
			return false;
		if (!ValidarCampoTexto(getElementById('nombre_' + num)))
			return false;


		getElementById('spanNumeroLegajo_' + num).innerHTML = getElementById('numeroLegajo_' + num).value;
		getElementById('spanNombre_' + num).innerHTML = getElementById('nombre_' + num).value;
	}

	// Oculto una fila y muestro la otra..
	var rowOn = obj.parentNode.parentNode;
	var rowOff = rowOn.previousSibling;

	rowOn.childNodes[0].style.display = 'none';
	rowOn.childNodes[1].style.display = 'none';
	rowOn.childNodes[2].style.display = 'none';

	rowOff.childNodes[0].style.display = 'block';
	rowOff.childNodes[1].style.display = 'block';
	rowOff.childNodes[2].style.display = 'block';
}

function agregarContratista(id, cuit, editable) {
	with (document) {
		// Tomo el último identificador de bloque de datos, para poder asignar el siguiente..
		var elements = getElementsByTagName("input");
		var num = 0;
		for (var i=0; i<elements.length; i++)
			if (elements[i].id.substr(0, 14) == 'idContratista_')
				if (Number(elements[i].id.substr(14)) > num)
					num = Number(elements[i].id.substr(14));
		num++;


		var table = getElementById('tableDatosContratistas');


		// Fila con los datos readonly..
		var row = table.insertRow(table.rows.length);
		row.id = 'trContratistaOff_' + num;

		var cell1 = row.insertCell(0);
		cell1.align = 'center';
		cell1.style.display = (!editable)?'block':'none';


		// Id..
		var elem = document.createElement('input');
		elem.id = 'idContratista_' + num;
		elem.name = 'idContratista_' + num;
		elem.type = 'hidden';
		elem.value = id;
		cell1.appendChild(elem);

		// Baja..
		var elem = document.createElement('input');
		elem.id = 'bajaContratista_' + num;
		elem.name = 'bajaContratista_' + num;
		elem.type = 'hidden';
		elem.value = 'f';
		cell1.appendChild(elem);

		// Botón editar..
		var elem = document.createElement('input');
		elem.className = 'btnEditar btnGrillaFondo';
		elem.id = 'btnEditar_' + num;
		elem.name = 'btnEditar_' + num;
		elem.title = 'Editar';
		elem.type = 'button';
		elem.onclick = function() {editarContratista(this)};
		cell1.appendChild(elem);

		// Botón quitar..
		var elem = document.createElement('input');
		elem.className = 'btnQuitar btnGrillaFondo';
		elem.id = 'btnQuitar_' + num;
		elem.name = 'btnQuitar_' + num;
		elem.title = 'Quitar';
		elem.type = 'button';
		elem.onclick = function() {quitarContratista(this)};
		cell1.appendChild(elem);


		var cell2 = row.insertCell(1);
		cell2.style.display = (!editable)?'block':'none';

		// Número de C.U.I.T...
		var elem = document.createElement('span');
		elem.className = 'spanGrilla';
		elem.id = 'spanCuit_' + num;
		elem.innerText = cuit;
		cell2.appendChild(elem);



		// Fila con los datos editables..
		var row = table.insertRow(table.rows.length);
		row.id = 'trContratistaOn_' + num;

		var cell1 = row.insertCell(0);
		cell1.align = 'center';
		cell1.style.display = (editable)?'block':'none';


		// Botón aceptar..
		var elem = document.createElement('input');
		elem.className = 'btnAceptar btnGrillaFondo';
		elem.id = 'btnAceptar_' + num;
		elem.name = 'btnAceptar_' + num;
		elem.title = 'Aceptar';
		elem.type = 'button';
		elem.onclick = function() {aceptarContratista(this)};
		cell1.appendChild(elem);

		// Botón cancelar..
		var elem = document.createElement('input');
		elem.className = 'btnCancelarChico btnGrillaFondo';
		elem.id = 'btnCancelar_' + num;
		elem.name = 'btnCancelar_' + num;
		elem.title = 'Cancelar';
		elem.type = 'button';
		elem.onclick = function() {cancelarContratista(this)};
		cell1.appendChild(elem);


		var cell2 = row.insertCell(1);
		cell2.style.display = (editable)?'block':'none';

		// Número de C.U.I.T...
		var elem = document.createElement('input');
		elem.className = 'inputGrilla';
		elem.id = 'cuit_' + num;
		elem.maxLength = 13;
		elem.name = 'cuit_' + num;
		elem.title = 'Nº C.U.I.T.';
		elem.type = 'text';
		elem.value = cuit;
		cell2.appendChild(elem);


		if (editable) {
			if (table.rows.length > 6)
				getElementById('btnAgregarContratista').style.display = 'none';
			getElementById('cuit_' + num).focus();
		}
	}
}

function agregarDelegadoGremial(id, numeroLegajo, nombreGremio, editable) {
	with (document) {
		// Tomo el último identificador de bloque de datos, para poder asignar el siguiente..
		var elements = getElementsByTagName("input");
		var num = 0;
		for (var i=0; i<elements.length; i++)
			if (elements[i].id.substr(0, 18) == 'idDelegadoGremial_')
				if (Number(elements[i].id.substr(18)) > num)
					num = Number(elements[i].id.substr(18));
		num++;


		var table = getElementById('tableDatosGremiales');


		// Fila con los datos readonly..
		var row = table.insertRow(table.rows.length);
		row.id = 'trDelegadoGremialOff_' + num;

		var cell1 = row.insertCell(0);
		cell1.align = 'center';
		cell1.style.display = (!editable)?'block':'none';


		// Id..
		var elem = document.createElement('input');
		elem.id = 'idDelegadoGremial_' + num;
		elem.name = 'idDelegadoGremial_' + num;
		elem.type = 'hidden';
		elem.value = id;
		cell1.appendChild(elem);

		// Baja..
		var elem = document.createElement('input');
		elem.id = 'bajaDelegadoGremial_' + num;
		elem.name = 'bajaDelegadoGremial_' + num;
		elem.type = 'hidden';
		elem.value = 'f';
		cell1.appendChild(elem);

		// Botón editar..
		var elem = document.createElement('input');
		elem.className = 'btnEditar btnGrillaFondo';
		elem.id = 'btnEditar_' + num;
		elem.name = 'btnEditar_' + num;
		elem.title = 'Editar';
		elem.type = 'button';
		elem.onclick = function() {editarDelegadoGremial(this)};
		cell1.appendChild(elem);

		// Botón quitar..
		var elem = document.createElement('input');
		elem.className = 'btnQuitar btnGrillaFondo';
		elem.id = 'btnQuitar_' + num;
		elem.name = 'btnQuitar_' + num;
		elem.title = 'Quitar';
		elem.type = 'button';
		elem.onclick = function() {quitarDelegadoGremial(this)};
		cell1.appendChild(elem);


		var cell2 = row.insertCell(1);
		cell2.style.display = (!editable)?'block':'none';

		// Número de legajo..
		var elem = document.createElement('span');
		elem.className = 'spanGrilla';
		elem.id = 'spanNumeroLegajo_' + num;
		elem.innerText = numeroLegajo;
		cell2.appendChild(elem);


		var cell3 = row.insertCell(2);
		cell3.style.display = (!editable)?'block':'none';

		// Nombre gremio..
		var elem = document.createElement('span');
		elem.className = 'spanGrilla';
		elem.id = 'spanNombre_' + num;
		elem.innerText = nombreGremio;
		cell3.appendChild(elem);



		// Fila con los datos editables..
		var row = table.insertRow(table.rows.length);
		row.id = 'trDelegadoGremialOn_' + num;

		var cell1 = row.insertCell(0);
		cell1.align = 'center';
		cell1.style.display = (editable)?'block':'none';


		// Botón aceptar..
		var elem = document.createElement('input');
		elem.className = 'btnAceptar btnGrillaFondo';
		elem.id = 'btnAceptar_' + num;
		elem.name = 'btnAceptar_' + num;
		elem.title = 'Aceptar';
		elem.type = 'button';
		elem.onclick = function() {aceptarDelegadoGremial(this)};
		cell1.appendChild(elem);

		// Botón cancelar..
		var elem = document.createElement('input');
		elem.className = 'btnCancelarChico btnGrillaFondo';
		elem.id = 'btnCancelar_' + num;
		elem.name = 'btnCancelar_' + num;
		elem.title = 'Cancelar';
		elem.type = 'button';
		elem.onclick = function() {cancelarDelegadoGremial(this)};
		cell1.appendChild(elem);


		var cell2 = row.insertCell(1);
		cell2.style.display = (editable)?'block':'none';

		// Número de legajo..
		var elem = document.createElement('input');
		elem.className = 'inputGrilla';
		elem.id = 'numeroLegajo_' + num;
		elem.maxLength = 5;
		elem.name = 'numeroLegajo_' + num;
		elem.title = 'Nº Legajo';
		elem.type = 'text';
		elem.value = numeroLegajo;
		cell2.appendChild(elem);


		var cell3 = row.insertCell(2);
		cell3.style.display = (editable)?'block':'none';

		// Nombre gremio..
		var elem = document.createElement('input');
		elem.className = 'inputGrilla';
		elem.id = 'nombre_' + num;
		elem.maxLength = 255;
		elem.name = 'nombre_' + num;
		elem.title = 'Nombre del Gremio';
		elem.type = 'text';
		elem.value = nombreGremio;
		cell3.appendChild(elem);

		if (editable) {
			if (table.rows.length > 6)
				getElementById('btnAgregarDelegadoGremial').style.display = 'none';
			getElementById('numeroLegajo_' + num).focus();
		}
	}
}

function blur(obj) {
	obj.style.width = '64px';
}

function cancelarContratista(obj) {
	var rowOn = obj.parentNode.parentNode;
	var rowOff = rowOn.previousSibling;

	rowOn.childNodes[0].style.display = 'none';
	rowOn.childNodes[1].style.display = 'none';

	rowOff.childNodes[0].style.display = 'block';
	rowOff.childNodes[1].style.display = 'block';
}

function cancelarDelegadoGremial(obj) {
	var rowOn = obj.parentNode.parentNode;
	var rowOff = rowOn.previousSibling;

	rowOn.childNodes[0].style.display = 'none';
	rowOn.childNodes[1].style.display = 'none';
	rowOn.childNodes[2].style.display = 'none';

	rowOff.childNodes[0].style.display = 'block';
	rowOff.childNodes[1].style.display = 'block';
	rowOff.childNodes[2].style.display = 'block';
}

function clicContratistas(obj) {
	if (document.getElementById('formRGRL').contratistas[0].checked)
		obj.value = 'S';
	else
		obj.value = 'N';

	if (obj.value == 'S')
		valor = 'block';
	else
		valor = 'none';

	obj = obj.parentNode.nextSibling.nextSibling;
	obj.style.display = valor;

	obj = obj.nextSibling.nextSibling;
	obj.style.display = valor;

	obj = obj.nextSibling.nextSibling;
	obj.style.display = valor;
}

function clicDelegadosGremiales(obj) {
	if (document.getElementById('formRGRL').delegadosGremiales[0].checked)
		obj.value = 'S';
	else
		obj.value = 'N';

	if (obj.value == 'S')
		valor = 'block';
	else
		valor = 'none';

	obj = obj.parentNode.nextSibling.nextSibling;
	obj.style.display = valor;

	obj = obj.nextSibling.nextSibling;
	obj.style.display = valor;

	obj = obj.nextSibling.nextSibling;
	obj.style.display = valor;
}

function clicItemPregunta(idEstablecimiento, id, valor, expandir) {
	with (document) {
		if ((valor == 'N') && (getElementById('Hdeshabilitar_fecha_' + id).value == 'n')) {
			getElementById('btnFecha' + id).style.display = 'inline';
			getElementById('fecha_' + id).style.display = 'inline';
			getElementById('btnFechaD' + id).style.display = 'none';
			getElementById('fechaD_' + id).style.display = 'none';
			iframeProcesando.location.href = '/modules/usuarios_registrados/clientes/rgrl/cargar_fecha_regularizacion_por_defecto.php?idEstablecimiento=' + idEstablecimiento + '&id=' + id;
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

function editarContratista(obj) {
	var num = obj.id.substr(10);

	with (document)
		getElementById('cuit_' + num).value = getElementById('spanCuit_' + num).innerHTML;

	// Oculto una fila y muestro la otra..
	var rowOff = obj.parentNode.parentNode;
	var rowOn = rowOff.nextSibling;

	rowOff.childNodes[0].style.display = 'none';
	rowOff.childNodes[1].style.display = 'none';

	rowOn.childNodes[0].style.display = 'block';
	rowOn.childNodes[1].style.display = 'block';

	document.getElementById('cuit_' + num).focus();
}

function editarDelegadoGremial(obj) {
	var num = obj.id.substr(10);

	with (document) {
		getElementById('numeroLegajo_' + num).value = getElementById('spanNumeroLegajo_' + num).innerHTML;
		getElementById('nombre_' + num).value = getElementById('spanNombre_' + num).innerHTML;
	}

	// Oculto una fila y muestro la otra..
	var rowOff = obj.parentNode.parentNode;
	var rowOn = rowOff.nextSibling;

	rowOff.childNodes[0].style.display = 'none';
	rowOff.childNodes[1].style.display = 'none';
	rowOff.childNodes[2].style.display = 'none';

	rowOn.childNodes[0].style.display = 'block';
	rowOn.childNodes[1].style.display = 'block';
	rowOn.childNodes[2].style.display = 'block';

	document.getElementById('numeroLegajo_' + num).focus();
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

function focus(obj) {
	obj.style.width = '120px';
}

function focusCombo(obj) {
	obj.style.width = '';
}

function quitarContratista(obj) {
	if (!confirm('¿ Realmente desea quitar este contratista ?'))
		return;

	with (document) {
		getElementById('bajasContratistas').value+= ',' + getElementById('idContratista_' + obj.id.substr(10)).value;

		var table = getElementById('tableDatosContratistas');
		table.deleteRow(obj.parentNode.parentNode.rowIndex + 1);
		table.deleteRow(obj.parentNode.parentNode.rowIndex);

		getElementById('btnAgregarContratista').style.display = 'block';
	}
}

function quitarDelegadoGremial(obj) {
	if (!confirm('¿ Realmente desea quitar este delegado gremial ?'))
		return;

	with (document) {
 		getElementById('bajasGremiales').value+= ',' + getElementById('idDelegadoGremial_' + obj.id.substr(10)).value;

		var table = getElementById('tableDatosGremiales');
		table.deleteRow(obj.parentNode.parentNode.rowIndex + 1);
		table.deleteRow(obj.parentNode.parentNode.rowIndex);

		getElementById('btnAgregarDelegadoGremial').style.display = 'block';
	}
}

function showHideDiv(img) {
	var obj = img.parentNode.nextSibling.nextSibling;

	if (obj.style.display == 'block') {
		img.src = '/images/add16.png';
		img.title = 'Desplegar';
		obj.style.display = 'none';
	}
	else {
		img.src = '/images/minus16.png';
		img.title = 'Contraer';
		obj.style.display = 'block';
	}
}