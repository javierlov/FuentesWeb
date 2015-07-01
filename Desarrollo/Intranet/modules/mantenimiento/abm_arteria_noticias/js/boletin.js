var divWin = null;


function agregar() {
	window.location.href = '/arteria-noticias-abm/0';
}

function ajustarImagen(img, height, width) {
	if (img.style.height == height)
		img.style.height = '';
	else
		img.style.height = height;

	if (img.style.width == width)
		img.style.width = '';
	else
		img.style.width = width;
}

function cambiaFondo() {
	with (document) {
		if (getElementById('fondo').value == -1) {
			getElementById('imgFondoChica').style.visibility = 'hidden';
			getElementById('imgFondoGrande').style.visibility = 'hidden';
		}
		else {
			getElementById('imgFondoChica').style.visibility = 'visible';
			getElementById('imgFondoGrande').style.visibility = 'visible';
			getElementById('imgFondoChica').src = '/modules/arteria_noticias/fondo_titulos/fondo_chico_' + getElementById('fondo').value + '.jpg';
			getElementById('imgFondoGrande').src = '/modules/arteria_noticias/fondo_titulos/fondo_grande_' + getElementById('fondo').value + '.jpg';
		}
	}
}

function cancelar() {
	window.location.href = '/arteria-noticias-abm-busqueda/0';
}

function editarNoticia(num) {
	window.location.href = '/arteria-noticias-abm/' + document.getElementById('id').value + '/' + num;
}

function enviarBoletin() {
	if (ValidarForm(formEnviarBoletin))
		if (confirm('El boletín se va a enviar en este momento.\n¿ Confirma el envío del boletín a los destinatarios ingresados ?')) {
			document.getElementById('btnEnviar').style.visibility = 'hidden';
			document.getElementById('spanEnviando').style.visibility = 'visible';
			formEnviarBoletin.submit();
		}
}

function mostrarPanelAbm() {
	with (document) {
		getElementById('divMostrarPanelAbm').style.display = 'none';
		getElementById('divPanelAbm').style.display = 'block';
	}
}

function mouseOutNotica(num) {
	with (document)
		switch (num) {
			case 1:
				getElementById('trTitulo' + num).style.backgroundColor = '';
				getElementById('trCuerpo' + num).style.backgroundColor = '';
				break;
			case 2:
			case 3:
			case 4:
				getElementById('divNoticia' + num).style.backgroundColor = '';
				break;
			case 5:
			case 6:
			case 7:
			case 8:
				getElementById('spanNoticia' + num).style.backgroundColor = '';
				break;

		}
}

function mouseOverNotica(num) {
	with (document)
		switch (num) {
			case 1:
				getElementById('trTitulo' + num).style.backgroundColor = '#77daff';
				getElementById('trCuerpo' + num).style.backgroundColor = '#77daff';
				break;
			case 2:
			case 3:
			case 4:
				getElementById('divNoticia' + num).style.backgroundColor = '#77daff';
				break;
			case 5:
			case 6:
			case 7:
			case 8:
				getElementById('spanNoticia' + num).style.backgroundColor = '#77daff';
				break;
		}
}

function ocultarPanelAbm() {
	with (document) {
		getElementById('divPanelAbm').style.display = 'none';
		getElementById('divMostrarPanelAbm').style.display = 'block';
	}
}

function ordenar(id) {
	if ((divWin == null) || (divWin.style.display == 'none')) {
		medioancho = (document.body.offsetWidth / 2) - 208;
		medioalto = (document.body.offsetHeight / 2) - 108;
		divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=600px,height=264px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=1,scrolling=1');
	}

	divWin.load('iframe', '/modules/mantenimiento/abm_arteria_noticias/orden.php?id=' + id, 'Ordenar noticias');
	divWin.show();
}

function setImagenPortada(imgName) {
	iframeProcesando.location = '/modules/mantenimiento/abm_arteria_noticias/guardar_imagen_portada.php?id=' + document.getElementById('id').value + '&imgName=' + imgName;
}

function showTmpWin(id, tipo, titulo) {
	if ((divWin == null) || (divWin.style.display == 'none')) {
		medioancho = (document.body.offsetWidth / 2) - 208;
		medioalto = (document.body.offsetHeight / 2) - 108;
		divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=416px,height=216px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=1,scrolling=1');
	}

	divWin.load('iframe', '/modules/mantenimiento/abm_arteria_noticias/campo_suelto.php?id=' + id + '&tipo=' + tipo + '&titulo=' + titulo, 'Indique el valor');
	divWin.show();
}

function volverAlBoletin() {
	window.location.href = '/arteria-noticias-abm/' + document.getElementById('idboletin').value;
}