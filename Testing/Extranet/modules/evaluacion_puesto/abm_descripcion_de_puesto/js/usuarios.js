function cambiarEmpresa(empleado, reporta, referenteRrhh) {
	iframeUsuario.location.href = '/modules/evaluacion_puesto/abm_descripcion_de_puesto/cambiar_empresa.php?idempresa=' + document.getElementById('empresa').value + '&idempleado=' + empleado + '&idreporta=' + reporta + '&referenterrhh=' + referenteRrhh;
}

function cambiarReporta() {
	iframeUsuario.location.href = '/modules/evaluacion_puesto/abm_descripcion_de_puesto/cambiar_reporta.php?idreporta=' + document.getElementById('reporta').value;
}

function eliminar(id) {
	if (confirm('¿ Realmente desea dar de baja este usuario ?'))
		iframeUsuario.location.href = '/modules/evaluacion_puesto/abm_descripcion_de_puesto/procesar_usuario.php?id=' + id + '&tipoOp=B';
}

function validarUsuario(formUsuario) {
	if (!ValidarForm(formUsuario))
		return false;

	with (document)
		if (getElementById('estadoAnterior').value != getElementById('estado').value)
			if (!confirm('Está a punto de cambiar el estado. ¿ Confirma la operación ?'))
				return false;

	return true;
}