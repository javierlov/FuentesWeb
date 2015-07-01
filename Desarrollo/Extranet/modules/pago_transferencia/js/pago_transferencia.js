function ajustarTamanoIframe(iFrame) {
	var code = iFrame.contentWindow.document.body.innerHTML;
	var cant = 0;

	while (code.indexOf('gridFondoOnMouseOver') != -1) {
		cant++;
		code = code.substr(code.indexOf('gridFondoOnMouseOver') + 2);
	}

	if (cant == 0)
		iFrame.height = 64;
	else
		iFrame.height = 64 + (cant * 20);
}

function encriptarPassword() {
	objPs = document.getElementById('ps');
	if (objPs != null)
		if (objPs.value != '')
			objPs.value = hex_md5(objPs.value);

	objPsn = document.getElementById('psn');
	if (objPsn != null)
		if (objPsn.value != '')
			objPsn.value = hex_md5(objPsn.value);

	objCnf = document.getElementById('cnf');
	if (objCnf != null)
		if (objCnf.value != '')
			objCnf.value = hex_md5(objCnf.value);
}