function encriptarPassword() {
	obj = document.getElementById('ps');
	if (obj != null)
		if (obj.value != '')
			obj.value = hex_md5(obj.value);

	obj = document.getElementById('psn');
	if (obj != null)
		if (obj.value != '')
			obj.value = hex_md5(obj.value);

	obj = document.getElementById('cnf');
	if (obj != null)
		if (obj.value != '')
			obj.value = hex_md5(obj.value);
}

function enviarForm() {
	obj = document.getElementById('psn');
	if (obj != null)
		document.getElementById('cc').value = obj.value.length;

//	encriptarPassword();
	document.getElementById('formLogin').submit();
}

function keyPress(e) {
	tecla = (document.all)?e.keyCode:e.which;
	if (tecla == 13)
		enviarForm();
}