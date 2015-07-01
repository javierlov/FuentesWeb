function bajarNominaCompleta(idEmpresa) {
	iframeProcesando.location.href = '/modules/usuarios_registrados/clientes/carga_masiva_trabajadores/bajar_nomina_completa.php?idempresa=' + idEmpresa;
}

function importar() {
	document.getElementById('btnImportar').style.display = 'none';
	document.getElementById('btnVolver').style.display = 'none';
	document.getElementById('spanMsgEspera').style.display = 'block';
	iframeProcesando.location.href = '/modules/usuarios_registrados/clientes/carga_masiva_trabajadores/procesar_importacion.php';
}

function subirNomina() {
	if (ValidarForm(formArchivo)) {
		document.getElementById('divGridEspera').style.display = 'block';
		document.getElementById('divGridEsperaTexto').style.display = 'block';
		formArchivo.submit();
	}
}