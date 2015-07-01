var x;
x=$(document);
x.ready(AsignarPericias);	

var paramid = '';
var parameaID = '';
  
  function AsignarPericias() {	  
		//$("#AceptaAdj").click(ValidarArchAdj);
		//$("#CancelaAdj").click(CancelaArchAdj);
		
		//test
		$("#mostrarresultado").click(MostrarDialogResultado);
		
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
		var btnSiguiente = {id: "btnAceptarMsj", text: "", click: function() { $( this ).dialog( "close" ); EjecutaElimianarAdjuntosPericia(paramid, parameaID); return true; } };
		var btnCancelar = {	id: "btnCancelarMsj", text: "", click: function() { $( this ).dialog( "close" ); return false; } };	
		botones = [btnSiguiente, btnCancelar]; 	
	}
		
	$('#dialogMensajesAdjuntosPericias').dialog({
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
	
	$("#dialogMensajesAdjuntosPericias").dialog("open");  	
	return true;
}	

function MostrarDialogSubiendo(){	
	showDialogMsj('Subiendo Archivo', 'Subiendo... por favor espere.', '', 1);	
	return true;
}

function MostrarDialogResultado(){
	if(ERRORESSUBIRARCHIVO != ''){		
		showDialogMsj('Archivo Adjunto', 'Pericias...', ERRORESSUBIRARCHIVO, 1);	
	}
	return true;
}
  
function ValidarArchAdjPericia(){
	var textdescripcion = ValorElementoID('textdescripcion');		
	var uploadedfile = ValorElementoID('uploadedfilePericia');		
	var PericiaID = ValorElementoID('PericiaID');		
	
	$('#textdescripcion').removeAttr('disabled');  
	
	if(uploadedfile == ''){
		//alert('Debe seleccionar un archivo');
		showDialogMsj('Error', 'Datos incompletos', 'Debe seleccionar un archivo para Asociar a la Pericia', 1);
		return false;
	}
	
	if(textdescripcion == '' ){
		//alert('Debe completar la descripcion');		
		showDialogMsj('Error', 'Datos incompletos', 'Debe completar la descripcion del archivo a Asociar a la Pericia', 1);
		return false;
	}
	
	showDialogMsj('Adjuntar Archivo', 'Procesando', 'Espere mientras se sube el archivo', 0);
	
	return true;
}
    
  function ElimianarAdjuntosPericia(id, eaID){	  
	paramid = id;
	parameaID =	eaID;
	
	showDialogMsj('Eliminar Archivo', 'Elimina', '¿Esta seguro que desea eliminar el archivo adjunto a esta Pericia?', 2);
	
	return true;
  }
  
  function EjecutaElimianarAdjuntosPericia(id, eaID){ 
	 window.location.href = '/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=135&DELETE&PericiaID='+id+'&eaid='+eaID;
     return true;
  }
  
  
  function CargarArchivoPericia(){
	 document.getElementById('textdescripcion').value = '';
		
	//if(!this.value.length)
	if(!document.getElementById('uploadedfilePericia').value.length)
		return false; 
	
	var textdescripcion = ValorElementoID('textdescripcion');		
	var uploadedfile = ValorElementoID('uploadedfilePericia');		
	
	uploadedfile = uploadedfile.split('\\');
	document.getElementById('textdescripcion').value = uploadedfile[uploadedfile.length-1];
	document.getElementById('textdescripcion').value = CortarString('textdescripcion', 0, 99);
	
	return true;
  }
  