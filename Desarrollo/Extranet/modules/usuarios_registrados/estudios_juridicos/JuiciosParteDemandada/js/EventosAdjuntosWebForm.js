var x;
x=$(document);
x.ready(AsignarEventos);	

var paramid = '';
var parameaID = '';
  
  function AsignarEventos() {	  
		//$("#AceptaAdj").click(ValidarArchAdjEvento);
		//$("#CancelaAdj").click(CancelaArchAdj);		
		//test		$("#mostrarresultado").click(MostrarDialogResultado);
		
		IniDialogMensajes(1);
		MostrarDialogResultado();
		
  }
  
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
		var btnSiguiente = {id: "btnAceptarMsj", text: "", click: function() { $( this ).dialog( "close" ); EjecutaElimianarAdjuntos(paramid, parameaID); return true; } };
		var btnCancelar = {	id: "btnCancelarMsj", text: "", click: function() { $( this ).dialog( "close" ); return false; } };	
		botones = [btnSiguiente, btnCancelar]; 	
	}
		
	$('#dialogMensajesAdjuntos').dialog({
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

function showDialogMsj(titulo, subtitulo, mensaje, botones){	
	
	document.getElementById('divSubiendoImg').style.display = 'none';
	document.getElementById('divInfo').style.display = 'none';
	
	IniDialogMensajes(botones);
	
	if(titulo != ''){  JQDivSetValue('#ui-id-1', titulo);}
	if(subtitulo != ''){  JQDivSetValue('#tituloInfo', subtitulo);}
	if(mensaje != ''){  
		JQDivSetValue('#motivoInfo', mensaje);
		
		document.getElementById('divInfo').style.display = 'block';
		document.getElementById('divInfo').style.color = 'black';		
		if( ExisteTextoEnString( mensaje, 'Debe') || ExisteTextoEnString( mensaje, 'Error') || ExisteTextoEnString( titulo, 'Error') ){
			document.getElementById('divInfo').style.color = 'red';		
		}
		
	}
	
	if(botones == 0){  document.getElementById('divSubiendoImg').style.display = 'block';}
	
	$("#dialogMensajesAdjuntos").dialog("open");  	
	return true;
}	

function MostrarDialogSubiendo(){	
	showDialogMsj('Subiendo Archivo', 'Subiendo... por favor espere.', '', 1);	
	return true;
}

function MostrarDialogResultado(){
	if(ERRORESSUBIRARCHIVO != ''){		
		document.getElementById('divInfo').style.display = 'block';				
		showDialogMsj('Archivo Adjunto', 'Adjunto', ERRORESSUBIRARCHIVO, 1);		
	}
	return true;
}
  
function ValidarArchAdjEvento(){
	var textdescripcion = ValorElementoID('textdescripcion');		
	var uploadedfile = ValorElementoID('uploadedfileEvento');		
	var EventoID = ValorElementoID('EventoID');		
	
	$('#textdescripcion').removeAttr('disabled');
	
	if(uploadedfile == ''){
		//alert('Debe seleccionar un archivo');
		showDialogMsj('Error', 'Datos incompletos', 'Debe seleccionar un archivo para Asociar al Evento', 1);
		return false;
	}

	if(textdescripcion == '' ){
		//alert('Debe completar la descripcion');		
		showDialogMsj('Error', 'Datos incompletos', 'Debe completar la descripcion del archivo a Asociar al Evento', 1);
		return false;
	}
	
	
	showDialogMsj('Adjuntar Archivo', 'Procesando', 'Espere mientras se sube el archivo', 0);
	
	return true;	
}
    
  function ElimianarAdjuntosEvento(id, eaID){	  
	paramid = id;
	parameaID =	eaID;
	
	showDialogMsj('Eliminar Archivo', 'Elimina', '¿Esta seguro que desea eliminar el archivo adjunto a este Evento?', 2);
	
	return true;
  }
  
  function EjecutaElimianarAdjuntos(id, eaID){
	//var respuesta=confirm("¿Esta seguro de elimiar el archivo adjunto de este evento? "+id+" "+eaID );
    //if (respuesta==true){      
	 window.location.href = '/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=134&DELETE&EventoID='+id+'&eaid='+eaID;
	//}
     return true;
  }
  
  function CargarArchivoEvento(){
	 document.getElementById('textdescripcion').value = '';
	  
	//if(!this.value.length)
	if(!document.getElementById('uploadedfileEvento').value.length)
		return false; 
	
	var textdescripcion = ValorElementoID('textdescripcion');			
	var uploadedfile = ValorElementoID('uploadedfileEvento');		
	
	uploadedfile = uploadedfile.split('\\');
	document.getElementById('textdescripcion').value = uploadedfile[uploadedfile.length-1];
	document.getElementById('textdescripcion').value = CortarString('textdescripcion', 0, 99);
	
	
	return true;
  }
  