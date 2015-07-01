function cambiarSolapa(solapa) {
	with (document) {
		if (getElementById('solapa').value == solapa)
			return;

		getElementById('formContacto').reset();

		getElementById('aEmpresa').src = '/modules/contacto/images/empresas.bmp';
		getElementById('aTrabajador').src = '/modules/contacto/images/trabajador.bmp';
		getElementById('aPrestador').src = '/modules/contacto/images/prestador_proveedor.bmp';
		getElementById('aOtros').src = '/modules/contacto/images/otros.bmp';

		getElementById('solapa').value = solapa;

		getElementById('divEmpresa').style.display = 'none';
		getElementById('divTrabajador').style.display = 'none';
		getElementById('divPrestador').style.display = 'none';
		getElementById('divOtros').style.display = 'none';

		if (solapa == 'e') {
			getElementById('divEmpresa').style.display = 'inline';
			getElementById('aEmpresa').src = '/modules/contacto/images/empresas_a.bmp';
			getElementById('eRazonSocial').focus();
		}
		else if (solapa == 't') {
			getElementById('divTrabajador').style.display = 'inline';
			getElementById('aTrabajador').src = '/modules/contacto/images/trabajador_a.bmp';
			getElementById('tNombreApellido').focus();
		}
		else if (solapa == 'p') {
			getElementById('divPrestador').style.display = 'inline';
			getElementById('aPrestador').src = '/modules/contacto/images/prestador_proveedor_a.bmp';
			getElementById('pRazonSocial').focus();
		}
		else if (solapa == 'o') {
			getElementById('divOtros').style.display = 'inline';
			getElementById('aOtros').src = '/modules/contacto/images/otros_a.bmp';
			getElementById('oRazonSocial').focus();
		}
	}
}

function mouseOver(obj) {
	if (obj.src.indexOf('_a') == -1)
		obj.src = obj.src.substring(0, obj.src.lastIndexOf('.')) + '_a.bmp';
}

function mouseOut(obj) {
	obj.src = obj.src.replace('_a', '');

	with (document) {
		if (((getElementById('solapa').value == 'e') && (obj.id == 'aEmpresa')) ||
				((getElementById('solapa').value == 't') && (obj.id == 'aTrabajador')) ||
				((getElementById('solapa').value == 'p') && (obj.id == 'aPrestador')) ||
				((getElementById('solapa').value == 'o') && (obj.id == 'aOtros')))
			obj.src = obj.src.substring(0, obj.src.lastIndexOf('.')) + '_a.bmp';
	}
}

function recargarCaptcha() {
	document.getElementById('imgCaptcha').src = '/functions/captcha.php?rnd=' + Math.random();
}