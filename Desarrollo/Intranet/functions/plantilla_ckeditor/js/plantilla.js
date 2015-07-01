function cancelarPlantilla() {
	with (document) {
		getElementById('divFondo').style.zIndex = '99';
		getElementById('divNombrePlantilla').style.display = 'none';
	}
}

function cargarPlantilla() {
	iframeProcesando.location.href = '/functions/plantilla_ckeditor/cargar_plantilla.php?id=' + document.getElementById('plantilla').value;
}

function guardarPlantilla() {
	with (document) {
		getElementById('idPlantilla').value = getElementById('plantilla').value;
		if (getElementById('plantilla').value == -1) {
			getElementById('divFondo').style.zIndex = '150';
			getElementById('divNombrePlantilla').style.display = 'block';
			getElementById('nombrePlantilla').focus();
		}
		else
			guardarRealmentePlantilla();
	}
}

function guardarRealmentePlantilla() {
	with (document) {
		if (getElementById('plantilla').value == -1) {
			if (!confirm('¿ Realmente desea guardar estos datos como plantilla "' + getElementById('nombrePlantilla').value + '" ?'))
				return false;
		}
		else {
			if (!confirm('¿ Realmente desea guardar estos datos como plantilla "' + getElementById('plantilla').options[getElementById('plantilla').selectedIndex].innerHTML + '" ?'))
				return false;
		}

		getElementById('cuerpoPlantilla').value = CKEDITOR.instances.html.getData();
		getElementById('formGuardarPlantilla').submit();
	}
}