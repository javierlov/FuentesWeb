/*Funciones genericas para manipular Dialogos jqui ..*/
  function JQUI_IniDialogMsj(cantBotones, nameDialog, nameBoton1, nameBoton2, funcBoton1, funcBoton2, classBoton1, classBoton2){
	
	var botones = '';
	
	if(cantBotones == 1){
		var botones = [
			{id: nameBoton1,text: "",click: function() {					
					$( this ).dialog( "close" );														
					return true;
				}
			}
		]; 
	}
	if(cantBotones == 2){		
		//var btnSiguiente = {id: nameBoton1, text: "", click: function() { $( this ).dialog( "close" ); funcBoton1; return true; } };
		//var btnCancelar = {	id: nameBoton2, text: "", click: function() { $( this ).dialog( "close" ); funcBoton2; return false; } };	
		var btnSiguiente = {id: nameBoton1, text: ""   };
		var btnCancelar = {	id: nameBoton2, text: "" };	
		botones = [btnSiguiente, btnCancelar]; 	
	}
		
	$('#'+nameDialog).dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: botones,
		open: function(event, ui) { 
			if(cantBotones == 0){
					$(".ui-dialog-titlebar-close", ui.dialog).hide(); 
			}			
		}
	});	
	
	if( document.getElementById(nameBoton1)) JQAsignaClaseCSS("#"+nameBoton1, classBoton1);		
	if( document.getElementById(nameBoton2)) JQAsignaClaseCSS("#"+nameBoton2, classBoton2);	
			
}

function JQUI_ShowDialogMsj(idDialog, idTitulo, idMensaje, idDivMensaje, idDivLoading, titulo, subtitulo, mensaje, botones){	
	/*setea los textos del mensaje*/
	//document.getElementById(idTitulo).style.display = 'none';
	//document.getElementById(idMensaje).style.display = 'none';
	
	if(titulo != ''){  JQDivSetValue('#ui-id-1', titulo);}
	
	
	if(document.getElementById(idTitulo)){
		if(subtitulo != ''){  
			JQDivSetValue('#'+idTitulo, subtitulo);
			document.getElementById(idTitulo).style.display = 'block';
		}else{
			document.getElementById(idTitulo).style.display = 'none';
		}	
	}
	
	if(mensaje != ''){  
		JQDivSetValue('#'+idMensaje, mensaje);
		
		if(ExisteElementoHTML(idDivMensaje) ) document.getElementById(idDivMensaje).style.display = 'block';
		if(ExisteElementoHTML(idMensaje) ) document.getElementById(idMensaje).style.display = 'block';
		if(ExisteElementoHTML(idMensaje) ) document.getElementById(idMensaje).style.color = 'black';		
		
		if( ExisteTextoEnString( mensaje, 'Debe') || ExisteTextoEnString( mensaje, 'Error') || ExisteTextoEnString( titulo, 'Error') ){
			if(ExisteElementoHTML(idMensaje) ) document.getElementById(idMensaje).style.color = 'red';		
		}		
	}
	
	if(botones == 0){  
		if( document.getElementById(idDivLoading) ){
		document.getElementById(idDivLoading).style.display = 'block';
		}
	}
	
	$("#"+idDialog).dialog("open");  	
	return true;
}	

function ExisteElementoHTML(idElemento){
	if( document.getElementById(idElemento) )
		return true;
	else
		return false;
}