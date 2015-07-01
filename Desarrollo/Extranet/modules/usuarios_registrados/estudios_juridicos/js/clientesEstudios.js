function abrirVentanaBusquedaEntidad(canal) {
	var height = 400;
	var width = 600;
	var left = ((window.innerWidth - width) / 2);
	var top = ((window.innerHeight - height) / 2) - window.screenTop;

	divWin = null;
	divWin = dhtmlwindow.open('divBoxOrdenEstablecimientos', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
	divWin.load('iframe', '/modules/usuarios_registrados/estudios_juridicos/buscar_entidad.php?c=' + canal, 'Buscar Entidad');
	divWin.show();
}

function cambiaCanal(canal) {
	iframeProcesando.location.href = '/modules/usuarios_registrados/estudios_juridicos/cambia_canal.php?idcanal=' + document.getElementById('canal').value;
}

function cambiaEntidad(entidad) {
	iframeProcesando.location.href = '/modules/usuarios_registrados/estudios_juridicos/cambia_entidad.php?identidad=' + document.getElementById('entidad').value;
}

function cambiaFormaPago(valor) {
	with (document)
		switch (valor) {
			case 'B':
				getElementById('cbu').readOnly = true;
				getElementById('cbu').style.backgroundColor = '#ccc';
				getElementById('cbu').value = '';
				getElementById('tarjetaCredito').style.display = 'none';
				getElementById('tarjetaCredito').value = -1;
				getElementById('tarjetaCreditoFalso').style.display = 'inline';
				break;
			case 'DA':
				getElementById('cbu').readOnly = false;
				getElementById('cbu').style.backgroundColor = '';
				getElementById('cbu').value = '';
				getElementById('labelCbu').innerHTML = 'C.B.U.';
				getElementById('tarjetaCredito').style.display = 'none';
				getElementById('tarjetaCredito').value = -1;
				getElementById('tarjetaCreditoFalso').style.display = 'inline';
				break;
			case 'TC':
				getElementById('cbu').readOnly = false;
				getElementById('cbu').style.backgroundColor = '';
				getElementById('cbu').value = '';
				getElementById('labelCbu').innerHTML = 'Nº Tarjeta';
				getElementById('tarjetaCredito').style.display = 'inline';
				getElementById('tarjetaCreditoFalso').style.display = 'none';
				break;
		}
}

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

	obj = document.getElementById('ps');	
	if ((obj != null) && (obj.value.length < 0)) {	
		alert('La Contraseña Nueva debe tener al menos 8 caracteres.');
		
		obj.focus();
		return false;
	}
	//No voy a encriptar por ahora
	//encriptarPassword();
	document.getElementById('formLogin').submit();
}

function keyPress(e) {
	tecla = (document.all)?e.keyCode:e.which;
	if (tecla == 13)
		enviarForm();
}

function mostrarCarta(contrato, entidadContrato) {
	if ((!formRC.sumaAseguradaRC[0].checked) && (!formRC.sumaAseguradaRC[1].checked) && (!formRC.sumaAseguradaRC[2].checked)) {
		alert('Antes de ver la carta debe seleccionar la Suma Asegurada.');
		return;
	}

	if (formRC.sumaAseguradaRC[0].checked)
		valor = 250;
	if (formRC.sumaAseguradaRC[1].checked)
		valor = 500;
	if (formRC.sumaAseguradaRC[2].checked)
		valor = 1000;

	window.open('/modules/usuarios_registrados/estudios_juridicos/rc_contratos_activos/mostrar_carta.php?c=' + contrato + '&ec=' + entidadContrato + '&sa=' + valor, 'extranetWindow', 'location=0');
}