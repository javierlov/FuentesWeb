function ajustarTamanoIframe(iFrame) {
	var code = iFrame.contentWindow.document.body.innerHTML;
	var cant = 0;

	while (code.indexOf('gridFondoOnMouseOver') != -1) {
		cant++;
		code = code.substr(code.indexOf('gridFondoOnMouseOver') + 2);
	}

	if (cant == 0)
		iFrame.height = 64;
	else
		iFrame.height = 64 + (cant * 39);
}

function buscarEmpresa() {
	var height = 400;
	var width = 688;
	var left = ((screen.width - width) / 2) + 52;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxEmpresa', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/usuarios_registrados/clientes/administracion_responsables_contrato/buscar_empresa.php?a=n', 'Buscar Empresa');
	divWin.show();
}

function eliminarUsuario(id) {
	if (confirm('¿ Realmente desea eliminar este usuario ?'))
		iframeProcesando2.location.href = '/modules/usuarios_registrados/clientes/administracion_usuarios/eliminar_usuario.php?id=' + id;
}