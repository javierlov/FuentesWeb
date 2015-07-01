var arrVisorImagenes;
var cerrarVisor = true;

function cerrarVisorImagenes() {
	if (cerrarVisor)
		document.getElementById('divVisorImagenes').style.display = 'none';
}

function getWidthAndHeight() {
	with (document) {
//		getElementById('imgVisorImagenesImagen').style.marginLeft = ((document.documentElement.offsetWidth - this.width) / 2) + 'px';
		getElementById('imgVisorImagenesImagen').style.marginTop = ((document.documentElement.offsetHeight - this.height) / 2) + 'px';

		getElementById('divVisorImagenesFlechas').style.left = '8px';
		getElementById('divVisorImagenesFlechas').style.top = ((document.documentElement.offsetHeight - 120) / 2) + 'px';
		getElementById('divVisorImagenesFlechaAnterior').style.left = '4px';
		getElementById('divVisorImagenesFlechaSiguiente').style.left = (document.documentElement.offsetWidth - 64) + 'px';

//		setTimeout("document.getElementById('imgVisorImagenesCargandoImagen').style.display = 'none'; document.getElementById('imgVisorImagenesImagen').style.display = 'block';", 400);
		getElementById('imgVisorImagenesCargandoImagen').style.display = 'none';
//		getElementById('imgVisorImagenesImagen').style.display = 'block';

		getElementById('divVisorImagenesFlechas').style.display = 'block';
		getElementById('divVisorImagenes').style.display = 'block';
		getElementById('divVisorImagenes').click;
	}
}

function isFlechaAnteriorVisible() {
	return (document.getElementById('divVisorImagenesFlechaAnterior').style.display == 'block');
}

function isFlechaSiguienteVisible() {
	return (document.getElementById('divVisorImagenesFlechaSiguiente').style.display == 'block');
}

function isVisorImagenesVisible() {
	if( document.getElementById('divVisorImagenes') )
		return (document.getElementById('divVisorImagenes').style.display == 'block');
	
	return false;
}

function mostrarImagen(ind) {
	document.getElementById('divVisorImagenes').style.display = 'block';
//	document.getElementById('imgVisorImagenesImagen').style.display = 'none';
	document.getElementById('imgVisorImagenesCargandoImagen').style.display = 'block';

	var path = '/functions/get_image.php';
	var params = '?file=' + arrVisorImagenes[ind];
	params+= '&mh=' + (document.documentElement.offsetHeight - 40);
	params+= '&mw=' + (document.documentElement.offsetWidth - 40);

	var newImg = new Image();
	newImg.onload = getWidthAndHeight;
	newImg.src = path + params;

	// Centro la imagen de espera..
	document.getElementById('imgVisorImagenesCargandoImagen').style.marginLeft = ((document.documentElement.offsetWidth - 200) / 2) + 'px';
	document.getElementById('imgVisorImagenesCargandoImagen').style.marginTop = ((document.documentElement.offsetHeight - 200) / 2) + 'px';

	document.getElementById('imgVisorImagenesImagen').src = path + params;
	document.getElementById('divVisorImagenesFlechaAnterior').onclick = function() { mostrarImagen(ind - 1); }
	document.getElementById('divVisorImagenesFlechaSiguiente').onclick = function() { mostrarImagen(ind + 1); }

	if (ind <= 0)
		document.getElementById('divVisorImagenesFlechaAnterior').style.display = 'none';
	else
		document.getElementById('divVisorImagenesFlechaAnterior').style.display = 'block';

	if (ind >= (arrVisorImagenes.length - 1))
		document.getElementById('divVisorImagenesFlechaSiguiente').style.display = 'none';
	else
		document.getElementById('divVisorImagenesFlechaSiguiente').style.display = 'block';
}

function mouseOutFlechas() {
	cerrarVisor = true;
}

function mouseOverFlechas() {
	cerrarVisor = false;
}