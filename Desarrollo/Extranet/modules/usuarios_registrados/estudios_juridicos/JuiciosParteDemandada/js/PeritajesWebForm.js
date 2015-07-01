var x;
x=$(document);
x.ready(AsignarEventosPericias);		

var idPericia = 0;  
  
  function AsignarEventosPericias() {	
  
	IniDialogMensajes(2);	
	/*
	SetDialogEliminaPericia();
	
	var x=$("#btnElimAceptar").click( function(){ $( this ).dialog( "close" ); RedirectCancelar(); return true; } );
	
	var x=$("#btnElimCanclear").click( 
		function(){ 
			alert('elimina');
			
			$( this ).dialog( "close" ); 
			RedirectCancelar(); 
			return false; 
		} );
		*/
		
		
	/*	
		AddToolTips(['idPericia','idFechaNotif','idFechaPericia', 'idFechaVencImpug','idImpug','idCant'], 3);
		var x=$("#botonNuevoPeritaje");
		x.click(AbrirNuevoPeritaje);
		
		x=$("#txtNroExpediente");
		x.click(function(){this.select();});
		
		x=$("#txtNroCarpeta");
		x.click(function(){this.select();});
*/		
  }
  
  function SetDialogEliminaPericia(){
		
		cantBotones = 2; 
		nameDialog = 'dialogElimPeritaje';
		nameBoton1 = 'btnElimAceptar';
		nameBoton2 = 'btnElimCanclear';
		funcBoton1 = ''; 
		funcBoton2 = ''; 
		classBoton1 = 'btnAceptar';
		classBoton2 = 'btnCancelarEJ';
		
		JQUI_IniDialogMsj(cantBotones, nameDialog, nameBoton1, nameBoton2, funcBoton1, funcBoton2, classBoton1, classBoton2);		

		
  }
  
  function muestraDialogoEliminar(){
		idDialog = 'dialogElimPeritaje';
		idTitulo = 'idTitulo';
		idMensaje = 'idMotivo';
		idDivMensaje = 'idDivInfo';
		idDivLoading = 'idDivLoading';
		titulo = 'Peritaje';
		subtitulo = "Elimina Peritaje Dialog";
		mensaje = "¿Está seguro que desea eliminar este Peritaje?";
		botones = 2;
		
		JQUI_ShowDialogMsj(idDialog, idTitulo, idMensaje, idDivMensaje, idDivLoading, titulo, subtitulo, mensaje, botones)		
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
		
	$('#dialogElimPeritaje').dialog({
		position:{my: "center top",  at: "center top",  of: "#divContent"},
		autoOpen: false,
		width: 400,
		modal:true,
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
	
	$("#dialogElimPeritaje").dialog("open");  	
	return true;
}	

    //--------------------------------------------------------------------
  
  function AbrirNuevoPeritaje(){
	//pageid 105 = 112
	$(location).attr('href', '/index.php?pageid=112');
  }
  
  function ValidarPeritajesWebForm(){
    var resultado = '';    
    $('#lblErrores').empty();  
  }
//----------------------------------------------------------------------------
  function AsignarBotones(){
	$("#idbtnCancelarVentana").click( function(){ RedirectCancelar(); } );
	$("#idbtnAceptarVentana").click( function(){ RedirectPage(); });				
  }
  
  function RedirectCancelar(){	
	alert('Cancelo la operacion');
	return true;
  }
  
  function EliminarPericia(id){					
	//muestraDialogMsj('Elimina', '', '¿Está seguro de que desea eliminar este Peritaje?', 2);
	muestraDialogoEliminar();
	idPericia = id;
	return true;
  }
  
  function RedirectPage(){					
	//pageid 104 = 111		
   	window.location.href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?pageid=111&DELETE&id='+idPericia;	   		
	return true;
  }
    
  function MostrarVentanaResultadoOK(mensajeResultado, resultadoEstado){
  	/* Muestra la ventana de mensaje */			
	MostrarVentanaOKCancel(mensajeResultado);		
	$("#idbtnAceptarVentanaResultado").click( function(){ RedirectCancelarRespuesta(); }  );	
  }
  
  function RedirectCancelarRespuesta(){
	window.location.href = '/PeritajesWebForm';
	return true;
  }
  
