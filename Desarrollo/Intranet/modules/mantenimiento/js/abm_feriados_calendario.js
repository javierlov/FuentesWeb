function agregar() {
	window.location.href = '/calendario-feriados-abm/0';
}

function cancelar() {
	window.location.href = '/calendario-feriados-abm-busqueda/0';
}

function darBaja(id) {
	if (confirm('¿ Realmente desea dar de baja este feriado ?'))
		iframeProcesando.location.href = '/modules/mantenimiento/abm_feriados_calendario/dar_baja_feriado.php?id=' + id;
}

function guardar() {
	with (document) {
		body.style.cursor = 'wait';
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
		getElementById('formAbmFeriado').submit();
	}
}

function submitFormBusqueda() {
	function submit() {
		with (document) {
			getElementById('divContentGrid').style.display = 'none';
			getElementById('divProcesando').style.display = 'block';
			getElementById('formBuscarFeriado').submit();
		}
	}

	setTimeout(function(){submit()}, 300);
}