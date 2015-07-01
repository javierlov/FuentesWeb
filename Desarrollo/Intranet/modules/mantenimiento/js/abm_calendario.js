function agregar() {
	window.location.href = '/calendario-eventos-abm/0';
}

function cancelar() {
	window.location.href = '/calendario-eventos-abm-busqueda/0';
}

function darBaja(id) {
	if (confirm('¿ Realmente desea dar de baja este evento ?'))
		iframeProcesando.location.href = '/modules/mantenimiento/abm_calendario/dar_baja_evento.php?id=' + id;
}

function guardar() {
	with (document) {
		body.style.cursor = 'wait';
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
		getElementById('formAbmEvento').submit();
	}
}

function submitFormBusqueda() {
	function submit() {
		with (document) {
			getElementById('divContentGrid').style.display = 'none';
			getElementById('divProcesando').style.display = 'block';
			getElementById('formBuscarEvento').submit();
		}
	}

	setTimeout(function(){submit()}, 300);
}