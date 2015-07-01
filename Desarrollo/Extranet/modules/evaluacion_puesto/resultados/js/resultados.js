function cambiarEmpresa() {
	iframeProcesando.location.href = '/modules/evaluacion_puesto/resultados/cambiar_empresa.php?idempresa=' + document.getElementById('empresa').value;
}