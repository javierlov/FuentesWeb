function subirArchivo() {
	with (document) {
		getElementById('divPaso1').style.display = 'none';
		getElementById('divPaso2').style.display = 'block';
		getElementById('formArchivo').submit();
	}
}