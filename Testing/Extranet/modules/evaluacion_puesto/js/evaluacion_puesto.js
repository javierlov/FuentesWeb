function abrirVentanaCombo(subFactorConocimiento, itemSeleccionado, titulo) {
	divWin3 = dhtmlwindow.open('divBox2', 'iframe', '/test.php', 'Aviso', 'width=560px,height=240px,left=80px,top=120px,resize=1,scrolling=1');
	divWin3.load('iframe', '/modules/evaluacion_puesto/descripcion_de_puesto/seleccionar_combo.php?sfc=' + subFactorConocimiento + '&is=' + itemSeleccionado, titulo);
	divWin3.show();
}

function avisoEnvio() {
	if (confirm('¿ Confirma el envío de la Descripción del Puesto ?\n\nUna vez enviado no se podrá volver a modificar.'))
		with (document) {
			getElementById('modo').value = 'E';
			getElementById('formEvaluacion').submit();
		}
}

function cambiarEvaluado(evaluado) {
	window.location.href = 'cambiar_evaluado.php?vld=' + evaluado;
}

function cerrarSesion() {
	if (confirm('Si cierra la sesión se perderan los datos no guardadados. ¿ Desea continuar ?'))
		window.location.href = 'logout.php';
}

function encriptarPassword() {
	objPs = document.getElementById('ps');
	if (objPs != null)
		if (objPs.value != '')
			objPs.value = hex_md5(objPs.value);

	objPsn = document.getElementById('psn');
	if (objPsn != null)
		if (objPsn.value != '')
			objPsn.value = hex_md5(objPsn.value);

	objCnf = document.getElementById('cnf');
	if (objCnf != null)
		if (objCnf.value != '')
			objCnf.value = hex_md5(objCnf.value);
}

function guardarDescripcion() {
	with (document) {
		getElementById('modo').value = 'G';
		getElementById('formEvaluacion').submit();
	}
}

function isMaxLength(obj) {
	var mlength = obj.getAttribute?parseInt(obj.getAttribute("maxlength")):"";
	if (obj.getAttribute && obj.value.length > mlength)
		obj.value = obj.value.substring(0, mlength);
}

function notificar() {
	if (confirm('¿ Confirma la notificación de la Descripción del Puesto ?'))
		with (document) {
			getElementById('modo').value = 'N';
			getElementById('formEvaluacion').submit();
		}
}

function verRecomendacionesSeccion(seccion, left, width) {
	medioancho = left;
	medioalto = 0;
	divWin2 = dhtmlwindow.open('divBox2', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=400px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=1,scrolling=1');
	divWin2.load('iframe', '/modules/evaluacion_puesto/descripcion_de_puesto/recomendaciones_seccion_' + seccion + '.php', 'Recomendaciones y ejemplos - Sección ' + seccion);
	divWin2.show();
}

function volver() {
	var sPath = conten.location.pathname;
	var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);

	if (sPage == 'buscar_usuario.php')
		conten.location.href = '/modules/evaluacion_puesto/conten.php';
	else if (sPage == 'usuario.php')
		conten.location.href = '/modules/evaluacion_puesto/abm_descripcion_de_puesto/buscar_usuario.php';
	else if (sPage == 'index.php')
		conten.location.href = '/modules/evaluacion_puesto/conten.php';
	else if (sPage == 'ver_evaluacion.php')
		conten.location.href = '/modules/evaluacion_puesto/resultados/index.php';
	else
		conten.history.back();
}