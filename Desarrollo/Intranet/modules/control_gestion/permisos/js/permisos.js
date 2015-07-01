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

function cambiarUsuario(usuario) {
	iframePermiso.location.href = '/modules/control_gestion/permisos/cargar_datos.php?usuario=' + usuario;
}

function clicCheck(obj) {
	if ((obj.id == "ejecutiva") && (!obj.checked))
		document.getElementById('nivel').value = '';

	if (obj.checked)
		chk = 'S';
	else
		chk = 'N';

	iframePermiso.location.href = '/modules/control_gestion/permisos/clic_check.php?usr=' + document.getElementById('usuariosConPermiso[]').value + '&obj=' + obj.id + '&chk=' + chk;
}

function exitNivel(nivel) {
	iframePermiso.location.href = '/modules/control_gestion/permisos/exit_nivel.php?usr=' + document.getElementById('usuariosConPermiso[]').value + '&nivel=' + nivel;
}

function guardar() {
	obj = document.getElementById('usuariosConPermiso[]');
	for (var i=0; i<obj.options.length; i++) {
		obj.options[i].selected = true;
	}

	document.getElementById('formPermisos').submit();
}

function hideProcesando() {
	document.getElementById('trProcesando').style.display = 'none';
}

function ocultarMensajeOk() {
	document.getElementById('spanMensaje').style.display = 'none';
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
	document.getElementById('trProcesando').style.display = 'block';
}