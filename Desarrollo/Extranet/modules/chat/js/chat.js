function abrirChat() {
	document.getElementById('iframeChat').src = '/modules/chat/marco.php?rnd=' + Math.random();
}

function adjuntarArchivo() {
	with (document) {
//		getElementById('divAdjuntar').style.display = 'none';
		getElementById('imgSubiendoArchivo').style.display = 'block';
	}
	formAdjuntarArchivo.submit();
}

function cambiarFondo(obj) {
	obj.style.backgroundColor = '';
	obj.style.color = '';
	window.parent.document.getElementById('spanError' + obj.id).innerHTML = '';
}

function cambiarSector(valor) {
	with (window.parent.document) {
		getElementById('divTituloDni').style.display = (valor == 1)?'block':'none';
		getElementById('dniChat').style.display = (valor == 1)?'block':'none';
	}
}

function cerrarChat() {
	with (window.parent.document) {
		if ((getElementById('mensaje2') == null) || (getElementById('mensaje2').disabled == true))		// La sesión ya la cerró el operador..
			salirChat(true);
		else {		// La está cerrando el usuario..
			getElementById('divCerrarChat').style.display = 'block';
			getElementById('divCerrarChatFondo').style.display = 'block';
		}
	}
}

function detectarEnterEnvioMensaje(e) {
	e = e || window.event;
	var key = e.keyCode;

	if (key == 13)
		enviarMensaje();
}

function enviarMensaje() {
	if (document.getElementById('mensaje2').value.trim() == '') {
		document.getElementById('mensaje2').value = '';
		document.getElementById('mensaje2').focus();
	}
	else
		formEnviarMensaje.submit();
}

function escribirMensaje(win, msg) {
	with (win.document) {
		getElementById('divMensajes').innerHTML+= msg;
		getElementById('divMensajes').scrollTop = getElementById('divMensajes').scrollHeight;
		getElementById('mensaje2').value = '';
		getElementById('mensaje2').focus();
	}
}

function iniciarChat() {
	document.getElementById('formChatInicio').submit();
}

function minimizarChat() {
	with (window.parent.document) {
		if ((getElementById('mensaje2') != null) && (getElementById('mensaje2').disabled == true))		// La sesión ya la cerró el operador..
			salirChat(true);
		else {		// Sino, minimizo..
			getElementById('divChatFondo').style.display = 'none';
			getElementById('divChatContenido').innerHTML = '';
			getElementById('divChatContenido').style.width = '0';
			getElementById('imgBotonChat').onClick = 'abrirChat()';
			getElementById('imgBotonChat').style.cursor = 'pointer';
		}
	}
}

function salirChat(salir) {
	if (salir) {
		if (document.getElementById('iframeChat') == null)
			window.parent.document.getElementById('iframeChat').src = '/modules/chat/cerrar_chat.php?rnd=' + Math.random();
		else
			document.getElementById('iframeChat').src = '/modules/chat/cerrar_chat.php?rnd=' + Math.random();
	}
	else
		with (window.parent.document) {
			getElementById('divCerrarChat').style.display = 'none';
			getElementById('divCerrarChatFondo').style.display = 'none';
		}
}