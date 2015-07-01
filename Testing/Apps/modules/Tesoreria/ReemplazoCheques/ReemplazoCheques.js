function aceptar() {
	if (!ValidarCampoTexto(document.getElementById("NUEVO_BENEFICIARIO")))
		return false;

	document.getElementById('CANCELAR').value = 'F';
	document.getElementById('builtForm').submit();
}

function cancelar() {
	document.getElementById('CANCELAR').value = 'T';
	document.getElementById('builtForm').submit();
}