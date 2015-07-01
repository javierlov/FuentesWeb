var paso = 1;

function enviar() {
	if (!ValidarRadioButton(document.getElementById('formEncuesta')['opcion' + paso]))
		return;

	document.getElementById('formEncuesta').submit();
}

function showHideObservacion(preguntaid, respuestaid) {
	var obj = document.getElementById('formEncuesta')['opcion' + preguntaid];
	for (i=0; i < obj.length; i++) {
		if (document.getElementById('trObservacion' + obj[i].value) != null)
			if (obj[i].value == respuestaid)
				document.getElementById('trObservacion' + obj[i].value).style.display = 'block';
			else {
				document.getElementById('trObservacion' + obj[i].value).style.display = 'none';
				document.getElementById('Observacion' + obj[i].value).value = '';
			}
	}
}

function siguiente() {
	if (!ValidarRadioButton(document.getElementById('formEncuesta')['opcion' + paso]))
		return;

	if (paso == 1) {
		document.getElementById('table1').style.display = 'none';
		if (document.getElementById('formEncuesta').opcion1[0].checked)
			paso = 2;
		else
			paso = 4;
		document.getElementById('table' + paso).style.display = 'block';
	}
	else if (paso == 2) {
		document.getElementById('table2').style.display = 'none';
		if (document.getElementById('formEncuesta').opcion2[0].checked)
			paso = 4;
		else
			paso = 3;
		document.getElementById('table' + paso).style.display = 'block';
	}
	else if (paso == 3) {
		paso = 4;
		document.getElementById('table3').style.display = 'none';
		document.getElementById('table' + paso).style.display = 'block';
	}

	if (paso == 4) {
		document.getElementById('trEnviar').style.display = 'block';
		document.getElementById('trSiguiente').style.display = 'none';
	}
}