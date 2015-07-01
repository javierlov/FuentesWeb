function agregarTodos(objOrigen, objDestino) {
	showProcesando();

	while (objOrigen.options.length > 0) {
		AddItemToDropDown(objDestino.id, objOrigen.options[0].value, objOrigen.options[0].text);
		objOrigen.remove(0);
	}
	OrdenarCombo(objDestino);
	
	hideProcesando();
}

function agregarUsuarios(objOrigen, objDestino) {
	showProcesando();
	
	var i = 0;
	while (i < objOrigen.options.length)
		if (objOrigen.options[i].selected) {
			AddItemToDropDown(objDestino.id, objOrigen.options[i].value, objOrigen.options[i].text);
			objOrigen.remove(objOrigen.selectedIndex);
		}
		else
			i++;

	OrdenarCombo(objDestino);
	hideProcesando();
}

function copiar() {
	var seleccionado = false;
	var usuariosDestino = '';

	obj = document.getElementById('usuariosDestino[]');
	for (var i=0; i<obj.options.length; i++)
		if (obj.options[i].selected) {
			seleccionado = true;
			usuariosDestino+= ', ' + obj.options[i].text;
//			break;
		}

	if (!seleccionado) {
		alert('Debe seleccionar al menos un usuario de destino.');
		return false;
	}

	var objOrigen = document.getElementById('usuarioOrigen');
	if (!confirm('¿ Realmente desea copiar el perfil de ' + objOrigen.options[objOrigen.selectedIndex].text + ' a los usuarios ' + usuariosDestino + ' ?'))
		return false;

	document.getElementById('formPerfiles').submit();
}

function guardar() {
	obj = document.getElementById('usuariosConPermiso[]');
	for (var i=0; i<obj.options.length; i++)
		obj.options[i].selected = true;

	document.getElementById('formPermisos').submit();
}

function hideProcesando() {
	document.getElementById('divMsgProcesando').style.display = 'none';
}

function ocultarMensajeOk() {
	document.getElementById('divMsgOk').style.display = 'none';
}

function preAgregarTodos(origen, destino) {
	showProcesando();
	setTimeout("agregarTodos(document.getElementById('" + origen + "'), document.getElementById('" + destino + "'))", 50);
}

function preAgregarUsuarios(origen, destino) {
	showProcesando();
	setTimeout("agregarUsuarios(document.getElementById('" + origen + "'), document.getElementById('" + destino + "'))", 50);
}

function showProcesando() {
	document.getElementById('divMsgProcesando').style.display = 'block';
}