function editarTema(id) {
	document.getElementById('iframeTemas').src = '/modules/control_gestion/informes_de_gestion/procesar_tema.php?action=C&id=' + id + '&rnd=' + Math.random();
}

function eliminarTema(id, pub) {
	if (pub == 0)
		str = '¿ Realmente desea dar de baja este tema ?';
	else if (pub == 1)
		str = 'Este tema tiene 1 publicación. ¿ Realmente desea darlo de baja ?';
	else
		str = 'Este tema tiene ' + pub + ' publicaciones. ¿ Realmente desea darlo de baja ?';

	if (confirm(str))
		document.getElementById('iframeTemas').src = '/modules/control_gestion/informes_de_gestion/procesar_tema.php?action=E&id=' + id + '&rnd=' + Math.random();
}

function verTema(id, tema) {
	document.getElementById('divAbm').style.display = 'block';
	document.getElementById('Id').value = id;
	document.getElementById('NombreTema').value = tema;
	document.getElementById('NombreTema').focus();
}