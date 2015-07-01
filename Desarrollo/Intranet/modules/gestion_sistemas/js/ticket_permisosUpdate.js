$(document).ready(inicializarEventos);

var ArraysUsuariosPermisos = [];

function inicializarEventos() {
	BuscaPermisoUsuarios(1);
	ini_dialogEliminaUsuario('');
	ini_dialogProcesoOK('');
	
	document.getElementById("btnGuardar").disabled  = true;
}

function GuardarPermisos() {

	ActivarGif();
	
	//document.getElementById('btnGuardar').disabled = true;
	//document.getElementById('btnCancelar').disabled = true;

	if (ActualizarPermisos(UsuarioNombre, motivoid, ArraysUsuariosPermisos, ArraysUsuariosPermisosDelete)) {
		DesactivarGif();
		//window.history.back();
	}
	
	ini_dialogProcesoOK('usuario');

	$('#tituloProcesoOK').empty().html("Colaborador - Permisos");
	$('#motivoProcesoOK').empty().html("Permisos Actualizados");
	$("#dialogProcesoOK").dialog("open");
	//window.location.href = '/modules/gestion_sistemas/index.php?sistema=1&MNU=6&subsistema=1&check=123456789';

}

function mostrarMensajeElimUsu(titulo, encabezado, mensaje) {

	$('#ui-id-1').empty().html(titulo);
	$('#tituloEliminaUsuario').empty().html(encabezado);
	$('#motivoEliminaUsuario').empty().html(mensaje);

	$("#dialogEliminaUsuario").dialog("open");
	return true;
}

function ini_dialogProcesoOK(funcion){
	
	$('#dialogProcesoOK').dialog({
	  position:{my: "center top",  at: "center top",  of: "#divContenido"},
      autoOpen : false,
	  resizable: false,
      width : 400,
      modal: true,
	  /*
	  show: {
        effect: "fade",
        duration: 504
      },
      hide: {
        effect: "clip",
        duration: 100
      },
	  */
      buttons: [{
			id : "POK_btnAceptar",
			text : "",
			click : function() {
				$(this).dialog("close");
					if (funcion != '')
						window.location.href = '/modules/gestion_sistemas/index.php?sistema=1&MNU=6&subsistema=1&check=123456789';
			}
		}]
     });
  
	
	$("#POK_btnAceptar").addClass("btnAceptar");
	return true;
}

function ini_dialogEliminaUsuario(funcion, parametro) {

	$('#dialogEliminaUsuario').dialog({
		autoOpen : false,
		resizable: false,
		width : 400,
		modal : true,
		buttons : [{
			id : "EU_btnAceptar",
			text : "",
			click : function() {
				$(this).dialog("close");
				if (funcion != '')
					funcion(parametro);
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


// ------------------------- ticket_permisosUpdate.js --------------------------------

function EtiquetasUserAct(ArraysUsuariosPermisos) {

	var usuariosDiv = document.getElementById('UsuariosActivos');
	var etiquetas = '';

	for (var users in ArraysUsuariosPermisos) {
		var etiqName = "Etiqueta_" + users;
		var usuario = ArraysUsuariosPermisos[users].toUpperCase();
		etiquetas += "<div class='contenedor-etiquetas' id='" + etiqName + "'  style='display:block;' onclick='mostrarUserEtiqueta( " + users + " ) ' >" + usuario + " </div>";
	}

	usuariosDiv.innerHTML = etiquetas;
	
	if( etiquetas == '') {
		ArraysUsuariosPermisos.length = 0; 
		ArraysUsuariosPermisosDelete.length = 0;
	}
	
	if ((ArraysUsuariosPermisos.length + ArraysUsuariosPermisosDelete.length ) > 0 ) 
		document.getElementById("btnGuardar").disabled  = false
	else
		document.getElementById("btnGuardar").disabled  = true;
	
}

function DeleteUserEtiqueta(usuario) {
		ArraysUsuariosPermisosDelete.push(usuario);
		var idx = ArraysUsuariosPermisos.indexOf(usuario);
		if (idx != -1)
			ArraysUsuariosPermisos.splice(idx, 1);

		UncheckOption(usuario);
		//BuscaPermisoUsuarios(1);
		EtiquetasUserAct(ArraysUsuariosPermisos);
}

function mostrarUserEtiqueta(num) {
	var usuario = ArraysUsuariosPermisos[num].toUpperCase();
	var mensaje = 'Quitar el usuario ' + usuario + ' de la lista de usuarios bloqueados.';

	ini_dialogEliminaUsuario(DeleteUserEtiqueta, usuario);
	mostrarMensajeElimUsu('Permisos', 'Colaborador Bloqueado', mensaje);

	return true;
}

function UncheckOption(valor) {
	
	if (!document.getElementById(valor))
		return false;
		
	document.getElementById(valor).checked = false;
	
	ArraysUsuariosPermisosDelete.push(valor);

	var idx = ArraysUsuariosPermisos.indexOf(valor);
	if (idx != -1)
		ArraysUsuariosPermisos.splice(idx, 1);
	
}

function CheckOption(valor) {

	if (valor == 'LOAD') {
		EtiquetasUserAct(ArraysUsuariosPermisos);
	}

	if (!document.getElementById(valor))
		return false;

	var check = document.getElementById(valor).checked;
	if (check) {
		ArraysUsuariosPermisos.push(valor);

		var idx = ArraysUsuariosPermisosDelete.indexOf(valor);
		if (idx != -1)
			ArraysUsuariosPermisosDelete.splice(idx, 1);
	} else {
		ArraysUsuariosPermisosDelete.push(valor);

		var idx = ArraysUsuariosPermisos.indexOf(valor);
		if (idx != -1)
			ArraysUsuariosPermisos.splice(idx, 1);
	}

	EtiquetasUserAct(ArraysUsuariosPermisos);
	
	
	return true;
}

