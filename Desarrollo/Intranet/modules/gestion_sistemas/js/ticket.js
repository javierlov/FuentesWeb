var attachmentsAmount = 0;

/*
 function AddAttachment(idelement) {
 objInput = document.getElementById('attachmentInput' + String(attachmentsAmount - 1));
 if (objInput != undefined) {
 if (objInput.value == "") {
 $inputLibre = true;
 } else {
 $inputLibre = false;
 }
 } else {
 $inputLibre = false;
 }

 if ($inputLibre) {
    AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Todav�a tiene una casilla libre para adjuntar un archivo.');
 return false;
 } else {
 AjaxRequest(idelement, 'ajax_ticket_attachments.php', attachmentsAmount);
 objCelda = document.getElementById('btnAdd' + String(attachmentsAmount - 1));
 if (objCelda != undefined) {
 objCelda.style.display = 'none';
 }
 attachmentsAmount = attachmentsAmount + 1;
 return true;
 }
 }
 */

function AddAttachment(idelement) {
	objInput = document.getElementById('attachmentInput' + String(attachmentsAmount - 1));
	if (objInput != undefined) {
		if (objInput.value == "") {
			$inputLibre = true;
		} else {
			$inputLibre = false;
		}
	} else {
		$inputLibre = false;
	}

	if ($inputLibre) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Todav�a tiene una casilla libre para adjuntar un archivo.');
		return false;
	} else {
		AjaxRequest(idelement, 'ajax_ticket_attachments.php', attachmentsAmount);
		objCelda = document.getElementById('btnAdd' + String(attachmentsAmount - 1));
		if (objCelda != undefined) {
			objCelda.style.display = 'none';
		}
		attachmentsAmount = attachmentsAmount + 1;
		return true;
	}
}

function CambioDetallePedido() {
	ValidarPermisoUsuario();
	if (!document.formSolicitud)
		return true;

	AjaxRequest('DivDetallePrioridad', 'ajax_detalle_prioridad.php', document.formSolicitud.DetallePedido.options[document.formSolicitud.DetallePedido.selectedIndex].value);

	if (document.getElementById('AplicacionSelect'))
		AjaxRequest('AplicacionSelect', 'ajax_detalle_ejecutable.php', document.formSolicitud.DetallePedido.options[document.formSolicitud.DetallePedido.selectedIndex].value);

	if (attachmentsAmount == 1) {
		AjaxRequest('attachmentInicial', 'ajax_ticket_attachments.php', 0);
	}
	if (document.getElementById('DivEjecutable'))
		AjaxRequest('DivEjecutable', 'ajax_detalle_ejecutable.php', document.formSolicitud.DetallePedido.options[document.formSolicitud.DetallePedido.selectedIndex].value);

	if (attachmentsAmount == 1) {
		AjaxRequest('attachmentInicial', 'ajax_ticket_attachments.php', 0);
	}
	
	ValidarTienePermiso();
}

function ValidarTienePermiso(){
	var usuario = '';
	
	if(usuarioLogeado)
		var usuario = usuarioLogeado;
	
	var TicketDetalle = document.getElementById('DetallePedido').value;
	
	document.getElementById('DivTicketMensajes').style.display = 'none';
	var mensaje = TienePermiso("DivTicketMensajes", usuario, TicketDetalle);
	var resultado = (mensaje == 'OK');
	TicketTienePermiso = true;

	if(!resultado){
		TicketTienePermiso = false;
		document.getElementById('imgProcesando').style.display = 'none';
		var DetallePedido = ReturnTextCombo('DetallePedido');
		document.getElementById('btnSubmit').disabled = false;
		
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Usted no tiene permiso para generar un ticket con el motivo '+DetallePedido+', consulte con su responsable.');
		document.getElementById('DivAreaMensajes').style.display = 'block';
		
		return false
	 }
	 
	document.getElementById('imgProcesando').style.display = 'none';
	document.getElementById('DivTicketMensajes').style.display = 'none';	
	return true;
}

function submitFormTicket(url_params) {
	document.formSolicitud.action = document.formSolicitud.action + url_params;
	document.formSolicitud.submit();
}

function ValidarFormAutorizacion() {
	if (document.getElementById('autoriza').value == -1) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Por favor indique si desea autorizar o rechazar el pedido.');
		document.getElementById('autoriza').focus();
		return false;
	}
	if ((document.getElementById('autoriza').value == 'N') && (document.getElementById('comentarios').value == '')) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Indique por favor el motivo del rechazo.');
		document.getElementById('comentarios').focus();
		return false;
	}
	return ValidarForm(formAutorizacion);
}

function ValidarFormCalificacion() {
	if (document.getElementById('resuelto').value == -1) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Por favor indique si su pedido ha sido resuelto.');
		document.getElementById('resuelto').focus();
		return false;
	}
	if ((document.getElementById('resuelto').value != 'N') && (document.getElementById('calificacion').value == -1)) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Por favor indique que calificaci�n nos dar�a por la resoluci�n de este pedido.');
		document.getElementById('calificacion').focus();
		return false;
	}
	if ((document.getElementById('resuelto').value == 'N') && (document.getElementById('calificacion').value > -1)) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Si el ticket no fue resuelto, nos pondremos a revisar el motivo. Pero a�n no es tiempo de que nos califique, deje el campo calificaci�n en blanco.');
		document.getElementById('calificacion').focus();
		return false;
	}
	if (((document.getElementById('calificacion').value == 5) || (document.getElementById('calificacion').value == 6)) && (document.getElementById('comentarios').value == '')) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Nos gustar�a que si su calificaci�n es regular o mala nos indique el motivo.');
		document.getElementById('comentarios').focus();
		return false;
	}
	return ValidarForm(formCalificacion);
}

function ValidarFormInformacion() {
	if (document.getElementById('comentarios').value == '') {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Ser�a bueno que nos aporte mas informaci�n sobre lo solicitado.');
		document.getElementById('comentarios').focus();
		return false;
	}
	return ValidarForm(formInformacion);
}

function ValidarFormTicket() {
	if (document.getElementById('TipoPedido').value == -1) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Debe seleccionar el tipo de pedido.');		
		document.getElementById('TipoPedido').focus();
		return false;
	}
	if (document.getElementById('DetallePedido').value == -1) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Debe seleccionar el motivo del pedido.');
		document.getElementById('DetallePedido').focus();
		return false;
	}
	if ((document.getElementById('Ejecutable') != null) && (document.getElementById('Ejecutable').value == -1)) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Debe seleccionar la aplicaci�n del Portal que corresponda.');
		document.getElementById('Ejecutable').focus();
		return false;
	}
	if (document.getElementById('notas') && document.getElementById('notas').value == '') {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Indique una breve descripci�n de su pedido.');
		document.getElementById('notas').focus();
		return false;
	}
	if (document.getElementById('notas') && document.getElementById('notas').value.length > 1000) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'La descripci�n de su pedido no puede superar los 1000 caracteres.');
		document.getElementById('notas').focus();
		return false;
	}
	if (document.getElementById('Prioridad') && document.getElementById('Prioridad').value == -1) {
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Debe seleccionar la prioridad de su pedido.');
		document.getElementById('Prioridad').focus();
		return false;
	}
	
	if(	!TienePermisoTicketPedido() ) 
		return false;
	
	var result = ValidarForm(formSolicitud);

	if (result)
		with (document) {
			getElementById('btnSubmit').disabled = true;
			getElementById('imgProcesando').style.display = 'inline';
			AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', '');
		}

	return result;
	//document.getElementById("btnSubmit").disabled = true;
}

function ValidarPermisoUsuario() {

	if (!document.formSolicitud)
		return 1;

	if (!document.formSolicitud.UsuarioSolicitud)
		return 1;

	if ((document.formSolicitud.UsuarioSolicitud.selectedIndex > -1) & (document.formSolicitud.DetallePedido.selectedIndex > -1)) {
		if ((document.formSolicitud.UsuarioSolicitud.options[document.formSolicitud.UsuarioSolicitud.selectedIndex].value > 0) & (document.formSolicitud.DetallePedido.options[document.formSolicitud.DetallePedido.selectedIndex].value > 0)) {
			AjaxRequest('DivAreaMensajes', 'ajax_mensaje_permiso.php', document.formSolicitud.UsuarioSolicitud.options[document.formSolicitud.UsuarioSolicitud.selectedIndex].value, document.formSolicitud.DetallePedido.options[document.formSolicitud.DetallePedido.selectedIndex].value);
		}
	}
	return 1;
}

function TienePermisoTicketPedido(){

	if (document.getElementById('DetallePedido') && !TicketTienePermiso) {
		var DetallePedido = ReturnTextCombo('DetallePedido');
		
		AjaxRequest('DivAreaMensajes', 'ajax_mensajes.php', 'Usted no tiene permiso para generar un ticket con el motivo '+DetallePedido+', consulte con su responsable.');
		document.getElementById('DivAreaMensajes').style.display = 'block';
		
		document.getElementById('DetallePedido').focus();
		return false;
	}	
	return true;
}