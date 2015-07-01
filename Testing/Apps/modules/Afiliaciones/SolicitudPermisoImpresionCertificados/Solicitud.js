function no() {
	if (validar()) {
		document.getElementById('PERMITE').value = 'N';
		document.getElementById('builtForm').submit();
	}
}

function si() {
	if (validar()) {
		document.getElementById('PERMITE').value = 'S';
		document.getElementById('builtForm').submit();
	}
}