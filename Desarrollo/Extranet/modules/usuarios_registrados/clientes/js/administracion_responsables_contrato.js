function advertirAdministradorArt(obj) {
	if (obj.checked)
		if (!confirm('Si tilda esta opción, este usuario va a tener permiso para ver TODOS los contratos activos.\n\n¿ Realmente desea llevar a cabo esta acción ?'))
			obj.checked = false;
}

function buscarEmpresa() {
	var height = 400;
	var width = 600;
	var left = ((screen.width - width) / 2) + 52;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxEmpresa', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/usuarios_registrados/clientes/administracion_responsables_contrato/buscar_empresa.php?a=s', 'Buscar Empresa');
	divWin.show();
}

function eliminarUsuario(id) {
	if (confirm('¿ Realmente desea eliminar este usuario ?'))
		iframeProcesando2.location.href = '/modules/usuarios_registrados/clientes/administracion_responsables_contrato/eliminar_usuario.php?id=' + id;
}

function escribirClave(valor) {
	if (valor != '') {
		document.getElementById('trRepetirContrasena').style.visibility = 'visible';
		document.getElementById('repetirContrasena').focus();
	}
}