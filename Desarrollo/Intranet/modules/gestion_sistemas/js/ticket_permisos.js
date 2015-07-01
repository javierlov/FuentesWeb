//cambio jlovatto 17/06/2015
$(document).ready(inicializarEventos);

function inicializarEventos() {
	$('#idDialogMsj').dialog({autoOpen : false });
}

function EventEliminarPermisoGrupo(idsolicitud, descpadre, descmotivo) {
	ini_dialogEliminaGrupo( idsolicitud );
	mostrarMensajeElimGrupo('Eliminar', 'Eliminar Grupo', '¿Esta seguro de eliminar este grupo de usuarios bloqueados?');
	return true;
}

function EventEliminarPermisoGrupo1(idsolicitud, descpadre, descmotivo) {
	var answer = confirm("Pedido: " + descpadre + "\n Detalle: " + descmotivo + "\n ¿Esta seguro que desea eliminar todos los permisos para este motivo? ");
	if (answer) {
		ActivarGif();
		EliminarPermisoGrupo(grupoids);

		var paginaactual = document.getElementById('paginaactual').value;
		BuscaColaboradores(paginaactual);
		DesactivarGif();
	}
}


function ini_dialogEliminaGrupo(parametro) {

	$('#idDialogMsj').dialog({
		position:{my: "center top",  at: "center top",  of: "#divContenido"},
		autoOpen : false,
		width : 400,
		modal : true,
		buttons : [{
			id : "EU_btnAceptar",
			text : "",
			click : function() {
				$(this).dialog("close");
				if (parametro != '')
					EliminarPermisoGrupo(parametro);
					location.reload();
				return true;
			}
		}, {
			id : "EU_btnCancelar",
			text : "",
			click : function() {
				$(this).dialog("close");				
				return false;
			}
		}]
	});

	$("#EU_btnAceptar").addClass("btnAceptar");
	$("#EU_btnCancelar").addClass("btnCancelar2");

}

function mostrarMensajeElimGrupo(titulo, encabezado, mensaje) {
	
	$('#ui-id-1').empty().html(titulo);	
	$('#idSubtitulo').empty().html(encabezado);
	$('#idMensaje').empty().html(mensaje);

	$("#idDialogMsj").dialog("open");
	return true;
}
