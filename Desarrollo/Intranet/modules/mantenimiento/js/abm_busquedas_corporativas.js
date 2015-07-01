function agregar() {
	window.location.href = '/busquedas-corporativas-abm/0';
}

function cancelar() {
	window.location.href = '/busquedas-corporativas-abm-busqueda/0';
}

function darBaja(id) {
	if (confirm('¿ Realmente desea dar de baja esta búsqueda corporativa ?'))
		iframeProcesando.location.href = '/modules/mantenimiento/abm_busquedas_corporativas/dar_baja_busqueda_corporativa.php?id=' + id;
}

function guardar() {
	with (document) {
		body.style.cursor = 'wait';
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
		getElementById('formAbmBusquedaCorporativa').submit();
	}
}

function submitFormBusqueda() {
	function submit() {
		with (document) {
			getElementById('divContentGrid').style.display = 'none';
			getElementById('divProcesando').style.display = 'block';
			getElementById('formBuscarBusquedaCorporativa').submit();
		}
	}

	setTimeout(function(){submit()}, 300);
}