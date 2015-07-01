function agregarImagen(id, x, y, doc) {
	obj = doc.getElementById(id);
	if (obj == null) {
		var img = document.createElement("img");
		img.setAttribute('border', 0);
		img.setAttribute('id', id);
		img.setAttribute('src', '/modules/regiones_sanitarias/imagenes/coordenada.png');
		img.style.cursor = 'hand';
		img.style.left = x;
		img.style.position = 'absolute';
		img.title = 'C.P. ' + id.substr(3);
		img.style.top = y;

		if (doc.getElementById('modoEdicion').value == 't') {
//			img.ondblclick = new Function('dblClickImg(' + x ', ' + y + ')');
			img.onmouseover = new Function('mostrarMenuBaja(this, ' + id.substr(3) + ')');
			img.onmouseout = new Function('ocultarMenuBaja()');
		}
		else
			img.onclick = new Function('parent.showPrestadores("' + doc.getElementById('tipoPrestador').value + '", ' + id.substr(3) + ')');

		doc.getElementById('divMapa').appendChild(img);
	}
	else {
		obj.style.display = 'block';
		obj.style.left = x;
		obj.style.top = y;
	}
}

function cargarIconos(id, codigo, tipo) {
	if (document.getElementById('modoEdicion').value == 'f')
		iframeTitle.location.href = '/modules/regiones_sanitarias/cargar_iconos.php?id=' + id + '&c=' + codigo + '&t=' + tipo;
}

function clicCP(cp) {
	var rows = document.getElementById('tabla').rows;

	for (var x = 0; x < rows.length; x++ )
		rows[x].style.backgroundColor = '';

	document.getElementById('tr_' + cp).style.backgroundColor = '#ccc';
	parent.document.getElementById('cpSeleccionado').value = cp;
}

function dblClickImg(x, y) {
	if (document.getElementById('modoEdicion').value == 'f')
		return;


	if (document.getElementById('cpSeleccionado').value == '') {
		alert('Debe seleccionar el código postal al que quiere asociar la coordenada.');
		return;
	}

	agregarImagen('img' + document.getElementById('cpSeleccionado').value, x, y, document);
	guardarCoordenadas(document.getElementById('cpSeleccionado').value, x, y);
}

function dblClickMapa() {
	if (document.getElementById('modoEdicion').value == 'f')
		return;


	if (document.getElementById('cpSeleccionado').value == '') {
		alert('Debe seleccionar el código postal al que quiere asociar la coordenada.');
		return;
	}

	agregarImagen('img' + document.getElementById('cpSeleccionado').value, event.offsetX - 8, event.offsetY - 8, document);
	guardarCoordenadas(document.getElementById('cpSeleccionado').value, event.offsetX - 8, event.offsetY - 8);
}

function eliminarCoordenada(cp) {
	if (document.getElementById('modoEdicion').value == 'f')
		return;


	if (confirm('¿ Realmente desea eliminar la coordenada del código postal ' + cp + ' ?')) {
		eliminarImagen(document.getElementById('img' + cp));
		iframeTitle.location.href = '/modules/regiones_sanitarias/eliminar_coordenadas.php?cp=' + cp;
	}
}

function eliminarImagen(obj) {
	if (obj != null) {
		obj.style.display = 'none';
		obj.id = '';
	}
}

function guardarCoordenadas(cp, x, y) {
	iframeTitle.location.href = '/modules/regiones_sanitarias/guardar_coordenadas.php?cp=' + cp + '&x=' + x + '&y=' + y;
}

function mostrarMenuBaja(obj, cp) {
	document.getElementById('idMenuBaja').cp = cp;
	with (document.getElementById('idMenuBaja')) {
		style.display = 'inline';
		style.left = obj.offsetLeft;
		style.top = obj.offsetTop + 16;
	}
}

function mostrarGrilla(id, tipo) {
	if ((divWin == null) || (divWin.style.display == 'none')) {
		//medioancho = (screen.width - 760) / 2;
		medioancho = 16;
		medioalto = document.body.offsetHeight - 240;
		divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=760px,height=200px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=1,scrolling=1');
	}

	divWin.load('iframe', '/modules/regiones_sanitarias/grilla_codigos_postales.php?id=' + id + '&tipo=' + tipo, 'Códigos Postales');
	divWin.show();
}

function ocultarMenuBaja() {
	document.getElementById('idMenuBaja').style.display = 'none';
}

function selectProvincia(provincia) {
	if (provincia == 2)		// Buenos Aires
		top.frames['principal'].location.href = '/modules/regiones_sanitarias/buenos_aires.php';
	else
		top.frames['principal'].location.href = '/modules/regiones_sanitarias/provincia.php?provincia=' + provincia;
}

function selectRegion(region) {
	top.frames['principal'].location.href = '/modules/regiones_sanitarias/region.php?region=' + region;
}

function showPrestadores(prestador, cp) {
	if ((divWin == null) || (divWin.style.display == 'none')) {
		//medioancho = (screen.width - 760) / 2;
		medioancho = 16;
		medioalto = document.body.offsetHeight - 240;
		divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=800px,height=200px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=1,scrolling=1');
	}

	sParams = 'cp=' + cp + '&prestador=' + prestador;
	divWin.load('iframe', '/modules/regiones_sanitarias/prestadores_por_zona.php?' + sParams, 'Prestadores');
	document.getElementById('iframePrestadores').src = 'set_popup_prestador_title.php?' + sParams;
	divWin.show();
}