var grid_row_backgroundColor_ORIGINAL = '';

function changePage(sUrl, bShowMessage, bSelf) {
	if (bShowMessage)
		with (document) {
			getElementById('divContentGrid').style.display = 'none';
			getElementById('divProcesando').style.display = 'block';
		}

	if (bSelf)
		document.location = sUrl + '&rnd=' + Math.random();
	else
		document.getElementById('iframeProcesando').src = sUrl + '&rnd=' + Math.random();
}

function mostrarMensajeEspera(msg) {
	document.getElementById('divGridEsperaTexto').innerHTML = msg;
	document.getElementById('divGridEspera').style.display = 'block';
	document.getElementById('divGridEsperaTexto').style.display = 'block';
}