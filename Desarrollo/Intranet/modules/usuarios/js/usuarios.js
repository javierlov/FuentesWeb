function guardarCampoEditable(campo) {
	with (document) {
		getElementById('span' + capitalize(campo) + 'Propio').innerHTML = getElementById(campo).value;
		getElementById(campo).style.display = 'none';
		getElementById('span' + capitalize(campo) + 'Propio').style.display = 'inline';
		getElementById('form' + capitalize(campo)).submit();
	}
}

function modificarCampoEditable(campo, modificar) {
	if (modificar)
		with (document) {
			getElementById(campo).value = getElementById('span' + capitalize(campo) + 'Propio').innerHTML;
			getElementById('span' + capitalize(campo) + 'Propio').style.display = 'none';
			getElementById(campo).style.display = 'inline';
			getElementById(campo).focus();
		}
}

function submitFormBusqueda() {
	with (document) {
		getElementById('divContentGrid').style.display = 'none';
		getElementById('divProcesando').style.display = 'block';
		getElementById('formBuscarUsuario').submit();
	}
}

function teclaPresionadaCampoEditable(campo) {
	var keyCode = event.which;
	if (keyCode == undefined)
		keyCode = event.keyCode;

	if (keyCode == 13)
		guardarCampoEditable(campo);
}