function cancelar() {
	window.location.href = '/mantenimiento-intranet';
}

function guardar() {
	with (document) {
		body.style.cursor = 'wait';
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
		getElementById('formUsuariosAviso').submit();
	}
}