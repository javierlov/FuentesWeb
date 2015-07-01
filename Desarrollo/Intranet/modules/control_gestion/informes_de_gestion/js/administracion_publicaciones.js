function editarPublicacion(id) {
	document.getElementById('iframePublicaciones').src = '/modules/control_gestion/informes_de_gestion/procesar_publicacion.php?action=C&id=' + id + '&rnd=' + Math.random();
}

function eliminarPublicacion(id) {
	if (confirm('¿ Realmente desea dar de baja esta publicación ?'))
		document.getElementById('iframePublicaciones').src = '/modules/control_gestion/informes_de_gestion/procesar_publicacion.php?action=E&id=' + id + '&rnd=' + Math.random();
}

function validarFormPublicacion(form) {
	if (!ValidarForm(form))
		return false;

	if ((document.getElementById('Id').value == -1) && (form.Archivo.value == '')) {
		alert('Debe seleccionar un archivo a publicar.');
		form.Archivo.focus();
		return false;
	}

	if ((!form.Activo[0].checked) && (!form.Activo[1].checked)) {
		alert('Debe indicar si la publicación está activa o no.');
		return false;
	}

	return true;
}

function verArchivo() {
	document.getElementById('iframePublicaciones').src = '/modules/control_gestion/informes_de_gestion/procesar_publicacion.php?action=V&id=' + document.getElementById('Id').value + '&rnd=' + Math.random();	
}

function verPublicacion(id, tema, archivo, titulo, activo) {
	document.getElementById('divAbm').style.display = 'block';
	document.getElementById('Id').value = id;
	document.getElementById('Tema').selectedIndex = getItemIndex(document.getElementById('Tema'), tema);

	if (archivo == '') {
		document.getElementById('LinkArchivo').style.color = '#808080';
		document.getElementById('LinkArchivo').style.cursor = '';
	}
	else {
		document.getElementById('LinkArchivo').style.color = '#CC4444';
		document.getElementById('LinkArchivo').style.cursor = 'pointer';
	}

	document.getElementById('Titulo').value = titulo;

	if (activo == 1)
		document.getElementById('formPublicacion').Activo[0].checked = true;
	else if (activo == 0)
		document.getElementById('formPublicacion').Activo[1].checked = true;
	else {
		document.getElementById('formPublicacion').Activo[0].checked = false;
		document.getElementById('formPublicacion').Activo[1].checked = false;
	}

	document.getElementById('Tema').focus();
}