function carga() {
	posicion = 0;

	if (navigator.userAgent.indexOf("MSIE") >= 0)		// IE
		navegador = 0;
	else
		navegador = 1;		// Otros
}

function comienzoMovimiento(event, id) {
	elMovimiento = document.getElementById(id);

	// Obtengo la posicion del cursor
	if (navegador == 0) {
		cursorComienzoX = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
		cursorComienzoY = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;

		document.attachEvent("onmousemove", enMovimiento);
		document.attachEvent("onmouseup", finMovimiento);
	}

	if (navegador == 1) {
		cursorComienzoX = event.clientX + window.scrollX;
		cursorComienzoY = event.clientY + window.scrollY;

		document.addEventListener("mousemove", enMovimiento, true);
		document.addEventListener("mouseup", finMovimiento, true);
	}

	elComienzoX = parseInt(elMovimiento.style.left);
	elComienzoY = parseInt(elMovimiento.style.top);

	// Actualizo el posicion del elemento
	elMovimiento.style.zIndex =++ posicion;

	evitaEventos(event);
}

function enMovimiento(event) {
	var xActual, yActual;

	if (navegador == 0) {
		xActual = window.event.clientX + document.documentElement.scrollLeft + document.body.scrollLeft;
		yActual = window.event.clientY + document.documentElement.scrollTop + document.body.scrollTop;
	}

	if (navegador == 1) {
		xActual = event.clientX + window.scrollX;
		yActual = event.clientY + window.scrollY;
	}

	elMovimiento.style.left = (elComienzoX + xActual - cursorComienzoX) + "px";
	elMovimiento.style.top = (elComienzoY + yActual - cursorComienzoY) + "px";

	evitaEventos(event);

	for (var i=1; i<=8; i++) {
		document.getElementById('titulo' + i).style.color = '';

		destino = document.getElementById('destino' + i);
		if ((parseInt(elMovimiento.style.top) >= parseInt(destino.style.top)) &&
			(parseInt(elMovimiento.style.top) <= (destino.offsetHeight + parseInt(destino.style.top))))
			document.getElementById('titulo' + i).style.color = '#f00';
	}

/*
document.getElementById('data').innerHTML =
	'Destino left: ' + document.getElementById('destino1').style.left + '<br />' +
	'Destino top: ' + document.getElementById('destino1').style.top + '<br />' +
	'Destino offsetWidth: ' + (parseInt(document.getElementById('destino1').style.left) + document.getElementById('destino1').offsetWidth) + '<br />' +
	'Destino offsetHeight: ' + (parseInt(document.getElementById('destino1').style.top) + document.getElementById('destino1').offsetHeight) + '<br />' +
	'OBJ left: ' + elMovimiento.style.left + '<br />' +
	'OBJ top: ' + elMovimiento.style.top + '<br />';
*/
}

function evitaEventos(event) {
// Funcion que evita que se ejecuten eventos adicionales

	if (navegador == 0) {
		window.event.cancelBubble = true;
		window.event.returnValue = false;
	}

	if (navegador == 1)
		event.preventDefault();
}

function finMovimiento(event) {
	if (navegador == 0) {
		document.detachEvent("onmousemove", enMovimiento);
		document.detachEvent("onmouseup", finMovimiento);
	}

	if (navegador == 1) {
		document.removeEventListener("mousemove", enMovimiento, true);
		document.removeEventListener("mouseup", finMovimiento, true);
	}

	noticia = getDestino();
	if (noticia != null)
		with (document) {
			getElementById('titulo' + noticia).style.color = '';

			idTmp = getElementById('idNoticia' + noticia).value;
			titleTmp = getElementById('titulo' + noticia).innerText;

			getElementById('idNoticia' + noticia).value = getElementById('idNoticia' + elMovimiento.id.substr(6)).value;
			getElementById('titulo' + noticia).innerText = elMovimiento.innerText;

			getElementById('idNoticia' + elMovimiento.id.substr(6)).value = idTmp;
			elMovimiento.innerText = titleTmp;
		}

	elMovimiento.style.left = elComienzoX + 'px';
	elMovimiento.style.top = elComienzoY + 'px';
}

function getDestino() {
	for (var i=1; i<=8; i++) {
		destino = document.getElementById('destino' + i);

		if ((parseInt(elMovimiento.style.top) >= parseInt(destino.style.top)) &&
			(parseInt(elMovimiento.style.top) <= (destino.offsetHeight + parseInt(destino.style.top))))
			return i;
	}

	return null;
}