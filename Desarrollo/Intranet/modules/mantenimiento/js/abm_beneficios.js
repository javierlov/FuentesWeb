function agregar() {
	window.location.href = '/beneficios-abm/0';
}

function cancelar() {
	window.location.href = '/beneficios-abm-busqueda/0';
}

function cerrarVentanaHtml() {
	with (document) {
		getElementById('divHtml').style.display = 'none';
		getElementById('divFondo').style.display = 'none';
	}
}

function darBaja(id) {
	if (confirm('¿ Realmente desea dar de baja este beneficio ?'))
		iframeProcesando.location.href = '/modules/mantenimiento/abm_beneficios/dar_baja_beneficio.php?id=' + id;
}

function editarHtml(url) {
	with (document) {
		getElementById('imagen').value = '';
		CKEDITOR.instances.html.setData(getElementById('htmlVisible').value);
		CKEDITOR.on('dialogDefinition', function(ev) {
			var dialogName = ev.data.name;
			var dialogDefinition = ev.data.definition;

			if (dialogName == 'image') {
				var infoTab = dialogDefinition.getContents('info');
				var urlField = infoTab.get('txtUrl');
				urlField['default'] = url;
			}
		});

		getElementById('divFondo').style.display = 'block';
		getElementById('btnGuardar').style.display = 'inline';
		getElementById('divHtml').style.display = 'block';

		// Ajusto el alto del campo editable..
		getElementById('cke_1_contents').style.height = (window.innerHeight - getElementById('divHtmlImagenes').offsetHeight - getElementById('cke_1_top').offsetHeight - getElementById('cke_1_bottom').offsetHeight - getElementById('divCuerpoPlantilla').offsetHeight - 88) + 'px';
	}
}

function guardar() {
	with (document) {
		var continuar = true;

		if (getElementById('baja').value == 't')
			if (!confirm('Este beneficio está dado de baja. ¿ Realmente desea reactivarlo ?'))
				continuar = false;

		if (continuar) {
			body.style.cursor = 'wait';
			getElementById('btnGuardar').style.display = 'none';
			getElementById('imgProcesando').style.display = 'inline';
			getElementById('formAbmBeneficio').submit();
		}
	}
}