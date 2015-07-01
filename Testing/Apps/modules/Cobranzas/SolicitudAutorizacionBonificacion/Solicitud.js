function aprobar() {
	document.getElementById('PERMITE').value = 'S';
	document.getElementById('builtForm').submit();
}

function rechazar() {
	document.getElementById('PERMITE').value = 'N';
	document.getElementById('builtForm').submit();
}