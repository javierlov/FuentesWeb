function cambiaTipoMovimiento() {
	with (document) {
		getElementById('SectorDesde').disabled = (getElementById('TipoMovimiento').value != 'M');
		getElementById('SectorHasta').disabled = (getElementById('TipoMovimiento').value == 'B');
		if (getElementById('TipoMovimiento').value == 'A') {
			getElementById('SectorDesde').value = -1;
		}
		if (getElementById('TipoMovimiento').value == 'B') {
			getElementById('SectorDesde').value = -1;
			getElementById('SectorHasta').value = -1;
		}
	}
}

function darBaja() {
	if (confirm('¿ Realmente desea dar de baja este registro ?')) {
		document.getElementById('TipoOp').value = 'B';
		document.getElementById('formNovedad').submit();
	}
}

function guardarNovedad() {
	with (document)
		if (ValidarForm(getElementById('formNovedad'))) {
			getElementById('SectorDesde').disabled = false;
			getElementById('SectorHasta').disabled = false;
			getElementById('formNovedad').submit();
		}
}