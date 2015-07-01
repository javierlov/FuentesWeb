function cambiarDelegacion(delegacion) {
	with (document)
		if (delegacion == DELEGACION_CAPITAL) {
			getElementById('divEdificio').style.display = 'block';
			getElementById('divHorarioAtencion').style.display = 'none';
			getElementById('divPiso').style.display = 'block';
			getElementById('horarioAtencion').value = '';
		}
		else {
			getElementById('divEdificio').style.display = 'none';
			getElementById('divHorarioAtencion').style.display = 'block';
			getElementById('divPiso').style.display = 'none';
			getElementById('edificio').value = -1;
			getElementById('piso').value = '';
		}
}

function cancelar() {
	window.location.href = '/usuarios-abm-busqueda/0';
}

function guardar() {
	with (document) {
		body.style.cursor = 'wait';
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
		getElementById('formAbmUsuario').submit();
	}
}

function setFoto(imgFotoImg) {
	with (document) {
		getElementById('fileFoto').value = imgFotoImg;
		iframeProcesando.location.href = '/modules/mantenimiento/abm_usuarios/mostrar_imagen.php?img=' + imgFotoImg;
	}
}