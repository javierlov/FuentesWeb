var x;
x=$(document);
x.ready(AsignarEventos);	

var idEvento = 0;
  
  function AsignarEventos() {	  
		BuscarWGFalseInterval();
  }
  
  function CompletarFechaAsignacion(){  	  	
	$('#txtFecha').val(FechaHoy()).select();
  }  
  
  
  function CompletarFechaVencimientoAsignacion(){  	
	$('#txtFechaVencimiento').val(FechaHoy()).select();
  }  
  
  function AsignarBotones(){
	$("#idbtnCancelarVentana").click( function(){ RedirectCancelar(); } );
	$("#idbtnAceptarVentana").click( function(){ RedirectPage(); });				
  }
  
  function RedirectCancelar(){
	window.location.href = '/EventosWebForm';  
	return true;
  }
  
  function RedirectPage(){					
	//pageid 106 = 113
	window.location.href = '/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=113&DELETE&id='+idEvento;
	return true;
  }
  
  function MostrarVentanaResultadoOK(mensajeResultado, resultadoEstado){
	/* Muestra la ventana de mensaje */	
	if(!mensajeResultado) var mensajeResultado = 'vacio';
	MostrarVentanaResultado(mensajeResultado);
		
	$("#idbtnAceptarVentanaResultado").click( function(){ RedirectCancelarRespuesta(); }  );	
  }
  
  function RedirectCancelarRespuesta(){
	window.location.href = '/EventosWebForm';
	return true;
  }
//-------------------------------
  function MostrarVentanaEliminar(mensajeResultado, resultadoEstado){
	/* Muestra la ventana de mensaje */	
	if(!mensajeResultado) var mensajeResultado = 'vacio';
	MostrarVentanaSoloOK(mensajeResultado);
		
	$("#idbtnAceptarSoloOK").click( function(){ RedirectCancelarRespuesta(); }  );	
  }    
  
 //--------------------------------------------------------------------
  function IniDialogMensajes(agregaboton){
	
	var botones = '';
	
	if(agregaboton == 1){
		var botones = [
			{id: "btnAceptarMsj",text: "",click: function() {					
					$( this ).dialog( "close" );														
					return true;
				}
			}
		]; 
	}
	if(agregaboton == 2){		
		var btnSiguiente = {id: "btnAceptarMsj", text: "", click: function() { $( this ).dialog( "close" ); RedirectPage(); return true; } };
		var btnCancelar = {	id: "btnCancelarMsj", text: "", click: function() { $( this ).dialog( "close" ); return false; } };	
		botones = [btnSiguiente, btnCancelar]; 	
	}
		
	$('#dialogElimEvento').dialog({
		position:{my: "center top",  at: "center top",  of: "#divContent"},
		autoOpen: false,
		width: 400,
		modal:true,
		resizable: false,
		bgiframe:true,
		buttons: botones,
		open: function(event, ui) { 
			if(agregaboton == 0){
					$(".ui-dialog-titlebar-close", ui.dialog).hide(); 
			}			
		}
	});	
	
	if( document.getElementById('btnAceptarMsj'))	JQAsignaClaseCSS("#btnAceptarMsj", "btnAceptar");		
	if( document.getElementById('btnCancelarMsj'))	JQAsignaClaseCSS("#btnCancelarMsj", "btnCancelarEJ");	
			
}


function muestraDialogMsj(titulo, subtitulo, mensaje, botones){	
	//setea los textos del mensaje
	
	IniDialogMensajes(botones);
	
	if(titulo != ''){  JQDivSetValue('#ui-id-1', titulo);}
	
	
	if(subtitulo != ''){  
		JQDivSetValue('#idTitulo', subtitulo);
		document.getElementById('idTitulo').style.display = 'block';
	}else{
		document.getElementById('idTitulo').style.display = 'none';
	}	
	
	if(mensaje != ''){  
		JQDivSetValue('#idMotivo', mensaje);
		
		document.getElementById('idDivInfo').style.display = 'block';
		document.getElementById('idMotivo').style.display = 'block';
		document.getElementById('idMotivo').style.color = 'black';		
		if( ExisteTextoEnString( mensaje, 'Debe') || ExisteTextoEnString( mensaje, 'Error') || ExisteTextoEnString( titulo, 'Error') ){
			document.getElementById('idMotivo').style.color = 'red';		
		}		
	}
	
	if(botones == 0){  document.getElementById('divSubiendoImg').style.display = 'block';}
	
	$("#dialogElimEvento").dialog("open");  	
	return true;
}	

function EliminarEvento(id){
	muestraDialogoEliminar();
	idEvento = id;
	return true;
}

function muestraDialogoEliminar(){
	
		idDialog = 'dialogElimEvento';
		idTitulo = 'idTitulo';
		idMensaje = 'idMotivo';
		idDivMensaje = 'idDivInfo';
		idDivLoading = 'idDivLoading';
		titulo = 'Evento';
		subtitulo = "Elimina Evento";
		mensaje = "¿Está seguro que desea eliminar este Evento?";
		botones = 2;
		
		IniDialogMensajes(botones);
		
		JQUI_ShowDialogMsj(idDialog, idTitulo, idMensaje, idDivMensaje, idDivLoading, titulo, subtitulo, mensaje, botones);
		return true;
  }
  
 //--------------------------------------------------------------------