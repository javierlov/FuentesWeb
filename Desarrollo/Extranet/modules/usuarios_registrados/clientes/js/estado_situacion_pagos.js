function generarEstadoSituacionPago() {
	if (ValidarCombo(document.getElementById('periodo')))
		with (document.getElementById('iframePdf')) {
			src = '	/modules/usuarios_registrados/clientes/estado_de_situacion_de_pagos/ver_pdf.php?periodo=' + document.getElementById('periodo').value;
			style.display = 'block';
		}
}