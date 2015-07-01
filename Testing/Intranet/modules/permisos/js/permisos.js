function AgregarTodos(objOrigen, objDestino) {
	ShowProcesando();

	while (objOrigen.options.length > 0) {
		AddItemToDropDown(objDestino.id, objOrigen.options[0].value, objOrigen.options[0].text);
		objOrigen.remove(0);
	}
	OrdenarCombo(objDestino);
	
	HideProcesando();
}

function AgregarUsuarios(objOrigen, objDestino) {
	ShowProcesando();
	
	var i = 0;
	while (i < objOrigen.options.length)
		if (objOrigen.options[i].selected) {
			AddItemToDropDown(objDestino.id, objOrigen.options[i].value, objOrigen.options[i].text);
			objOrigen.remove(objOrigen.selectedIndex);
		}
		else
			i++;
	OrdenarCombo(objDestino);
	
	HideProcesando();
}

function Guardar() {
	obj = document.getElementById('UsuariosConPermiso[]');
	for (var i=0; i<obj.options.length; i++) {
		obj.options[i].selected = true;
	}

	document.getElementById('formPermisos').submit();
}

function HideProcesando() {
	document.getElementById('trProcesando').style.display = 'none';
}

function OcultarMensajeOk() {
	document.getElementById('spanMensaje').style.display = 'none';
}

function PreAgregarTodos(origen, destino) {
	ShowProcesando();
	setTimeout("AgregarTodos(document.getElementById('" + origen + "'), document.getElementById('" + destino + "'))", 50);
}

function PreAgregarUsuarios(origen, destino) {
	ShowProcesando();
	setTimeout("AgregarUsuarios(document.getElementById('" + origen + "'), document.getElementById('" + destino + "'))", 50);
}

function ShowProcesando() {
	document.getElementById('trProcesando').style.display = 'block';
}
