function subirArchivo() {
	if (ValidarForm(document.getElementById('formArchivo'))) {
		document.getElementById('divPaso1').style.display = 'none';
		document.getElementById('divPaso2').style.display = 'block';
		document.getElementById('formArchivo').submit();
	}
}