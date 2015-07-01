function cambiarUsuario(usuario) {
	iframeObjetivosIndividuales.location.href = '/modules/programa_incentivos/cambiar_usuario.php?u=' + usuario;
}

function cargarCursosObligatorios(fecha) {
	iframeCursosObligatorios.location.href = '/modules/programa_incentivos/cargar_cursos_obligatorios.php?f=' + fecha;
}

function cargarEvaluacionDesempeño(fecha) {
	iframeEvaluacionDesempeño.location.href = '/modules/programa_incentivos/cargar_evaluacion_desempeno.php?f=' + fecha;
}

function cargarObjetivoIndividual(fecha) {
	iframeObjetivosIndividuales.location.href = '/modules/programa_incentivos/cargar_objetivo_individual.php?f=' + fecha;
}

function cargarPresentismo(fecha) {
	iframePresentismo.location.href = '/modules/programa_incentivos/cargar_presentismo.php?f=' + fecha;
}

function guardarObjetivosIndividuales() {
	with (document) {
		getElementById('imgGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
		formObjetivosIndividuales.submit();
	}
}