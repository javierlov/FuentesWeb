function agregar() {
	window.location.href = '/novedades-abm/0';
}

function cambiarTipoMovimiento() {
	with (document) {
		getElementById('sectorDesde').disabled = (getElementById('tipoMovimiento').value != 'M');
		getElementById('sectorHasta').disabled = (getElementById('tipoMovimiento').value == 'B');
		if (getElementById('tipoMovimiento').value == 'A') {
			getElementById('sectorDesde').value = -1;
		}
		if (getElementById('tipoMovimiento').value == 'B') {
			getElementById('sectorDesde').value = -1;
			getElementById('sectorHasta').value = -1;
		}
	}
}

function cancelar() {
	window.location.href = '/novedades-abm-busqueda/0';
}

function darBaja(id) {
	if (confirm('¿ Realmente desea dar de baja esta novedad ?'))
		iframeProcesando.location.href = '/modules/mantenimiento/abm_novedades/dar_baja_novedad.php?id=' + id;
}

function guardar() {
	with (document) {
		body.style.cursor = 'wait';
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
		getElementById('formAbmNovedad').submit();
	}
}

function submitFormBusqueda() {
	function submit() {
		with (document) {
			getElementById('divContentGrid').style.display = 'none';
			getElementById('divProcesando').style.display = 'block';
			getElementById('formBuscarNovedad').submit();
		}
	}

	setTimeout(function(){submit()}, 300);
}