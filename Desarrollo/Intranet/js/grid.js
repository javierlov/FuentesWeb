var grid_row_backgroundColor_ORIGINAL = '';

function changePage(sUrl, bShowMessage, bSelf) {
	if (document.getElementById('divProcesando') != null)
		if (document.getElementById('divProcesando').show != undefined) {
			document.getElementById('originalGrid').style.display = 'none';
			document.getElementById('divProcesando').style.display = 'block';
		}

	if (document.getElementById('iframeProcesando') != null)
		document.getElementById('iframeProcesando').src = sUrl + '&rnd=' + Math.random();
	else
		document.location = sUrl + '&rnd=' + Math.random();
}

function mostrarMensajeEspera(msg) {
	document.getElementById('divGridEsperaTexto').innerHTML = msg;
	document.getElementById('divGridEspera').style.display = 'block';
	document.getElementById('divGridEsperaTexto').style.display = 'block';
}