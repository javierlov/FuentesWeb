function agregarCiuo() {
	var height = 400;
	var width = 600;
	var left = ((screen.width - width) / 2) + 52;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxCiuo', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/usuarios_registrados/clientes/nomina_de_trabajadores/buscar_ciuo.php', 'Buscar CIUO');
	divWin.show();
}

function bajaTrabajador() {
	with (document)
		if (ValidarForm(getElementById('formTrabajador')))
			getElementById('formTrabajador').submit();
}

function buscarEstablecimiento(relacionLaboral) {
	var height = 400;
	var width = 688;
	var left = ((screen.width - width) / 2) + 52;
	var top = ((screen.height - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxEstablecimiento', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/usuarios_registrados/clientes/nomina_de_trabajadores/agregar_establecimiento.php?rl=' + relacionLaboral, 'Agregar Establecimiento');
	divWin.show();
}

function cambiaNacionalidad(valor, padre) {
	if (valor == 7)		// Si elige otros..
		padre.getElementById('spanOtraNacionalidad').style.visibility = 'visible';
	else {
		padre.getElementById('spanOtraNacionalidad').style.visibility = 'hidden';
		padre.getElementById('otraNacionalidad').value = '';
	}
}

function guardarTrabajador() {
	with (document) {
		if (ValidarForm(getElementById('formTrabajador'))) {
			if ((getElementById('nacionalidad').value == 7) && (getElementById('otraNacionalidad').value == '')) {
				alert('Debe especificar la nacionalidad.');
				getElementById('otraNacionalidad').focus();
				return false;
			}

			body.style.cursor = 'wait';
			getElementById('btnGuardar').style.display = 'none';
			getElementById('imgProcesando').style.display = 'inline';

			getElementById('formTrabajador').submit();
		}
	}
}

function quitarCiuo() {
	with (document) {
		getElementById('ciuo').innerHTML = 'Utilice el buscador para seleccionar el CIUO';
		getElementById('idCiuo').value = '-1';
		getElementById('imgQuitarCiuo').style.visibility = 'hidden';
	}
}

function recuperarDatosTrabajador(cuil) {
	iframeTrabajador.location.href = '/modules/usuarios_registrados/clientes/nomina_de_trabajadores/recuperar_trabajador.php?cl=' + cuil;
}