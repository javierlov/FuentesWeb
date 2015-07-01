function aceptar(objNombre, objId) {
	if (document.getElementById('usuarios').value == -1)
		window.parent.document.getElementById(objNombre).value = '';
	else
		window.parent.document.getElementById(objNombre).value = document.getElementById('usuarios').options[document.getElementById('usuarios').selectedIndex].text;
	window.parent.document.getElementById(objId).value = document.getElementById('usuarios').value;
	window.parent.divWin.close();
}

function cancelar() {
	window.parent.divWin.close();
}

function darBaja() {
	if (!confirm('¿ Realmente desea dar de baja a este usuario para este año ?'))
		return;

	with (document) {
		getElementById('tipoOp').value = 'B';
		getElementById('formUsuario').submit();
	}
}

function llenarComboUsuarios() {
	document.getElementById('iframeUsuario').src = '/modules/encuestas/evaluacion_desempeno/abm_usuarios/llenar_combo_usuarios.php';
}