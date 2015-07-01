function agregar() {
	window.location.href = '/nacimientos-abm/0';
}

function cancelar() {
	window.location.href = '/nacimientos-abm-busqueda/0';
}

function darBaja(id) {
	if (confirm('¿ Realmente desea dar de baja este nacimiento ?'))
		iframeProcesando.location.href = '/modules/mantenimiento/abm_nacimientos/dar_baja_nacimiento.php?id=' + id;
}

function guardar() {
	with (document) {
		var continuar = true;

		if (getElementById('baja').value == 't')
			if (!confirm('Este nacimiento está dado de baja. ¿ Realmente desea reactivarlo ?'))
				continuar = false;

		if (continuar) {
			body.style.cursor = 'wait';
			getElementById('btnGuardar').style.display = 'none';
			getElementById('imgProcesando').style.display = 'inline';
			getElementById('formAbmNacimiento').submit();
		}
	}
}

function setImagen(imgName) {
	with (document) {
		getElementById('fileImg').value = imgName;
		iframeProcesando.location.href = '/modules/mantenimiento/abm_nacimientos/mostrar_imagen.php?img=' + imgName;
	}
}

function validarLongitud(obj) {
	num_caracteres2 = obj.value.length;

	if (num_caracteres2 > num_caracteres2_permitidos)
		obj.value = contenido_textarea;
	else
		contenido_textarea = obj.value;

	if (num_caracteres2 >= num_caracteres2_permitidos)
		obj.style.color = "#f00";
	else
		obj.style.color = "#000";

	cuenta(obj);
}


var contenido_textarea = "";
var num_caracteres2_permitidos = 512;