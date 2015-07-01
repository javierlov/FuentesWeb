var x =$(document);
x.ready(AsignarEventos);	
  
  function AsignarEventos() {	  		
	
	$("#dialogMensaje").dialog({autoOpen: false});		

	if(muestraventana == 1){
		MostararVentanaEvento();		
	}
	
	$("#idAceptarAjax").click(CallSalvarEventoABM);
	ContarCaracteres();
	SetearControlFecha("txtFecha", "btnFecha");	
	SetearControlFecha("txtFechaVencimiento", "btnFechaVencimiento");		
		
  }
	
  function MostararVentanaEvento(){
	iniDialogMensaje();
	document.getElementById('ui-id-1').innerHTML = 'Evento';
	document.getElementById('dialogTitulo').innerHTML = 'Evento';		
	document.getElementById('dialogInfoTitulo').innerHTML = mensaje + '\n ¿Desea Adjuntar archivos?.';
	$('#dialogMensaje').dialog('open');
  }
  
  function ContarCaracteres(){
	CuentaCaracteres('txtObservaciones', 'idcontarcaracteres', true);
  }
  
  function CallSalvarEventoABM(){
	if(!ValidarEventosABMWebForm()){
		return false;
	}

	var txtfecha = $('#txtFecha').val().trim();	
	var txtfechavencimiento = $('#txtFechaVencimiento').val().trim();	
	var txtobservaciones = $('#txtObservaciones').val().trim();		
	var cmbEventos = $('#cmbEventos').val().trim();	
	var ClaveID = 0;
		
	if(Accion == 'EDIT')
		ClaveID = EventoID;
	if(Accion == 'ALTA')
		ClaveID = nrojuicio;
	
	if ( SalvarEventoABM(Accion, txtfecha, txtfechavencimiento, txtobservaciones, ClaveID, usuario, cmbEventos) == true){		
		if(Accion == 'EDIT')
			MostrarVentana('Evento modificado correctamente.');		
		if(Accion == 'ALTA')
			MostrarVentana('Evento ingresado correctamente.');		
			
		$("#idbtnCancelarVentana").click( function(){return false;} );
		$("#idbtnAceptarVentana").click( function(){ redirectpage(); });
		
	}
		return false;	
  }
  
  function redirectpage(){
		//pageid 101 = 108
		window.location.href = '/index.php?pageid=108'; 							
		return true;
  }
  
  
  function ValidarEventosABMWebForm(){
	var errorescount = 0;	
	$("#lblErrores").empty();
	$("#ErrorestxtFecha").empty();
	$('#ErrorestxtFechaVencimiento').empty();	

	errorescount += MostrarError(IsNullZero, $('#txtFecha'), $('#ErrorestxtFecha'), 'Debe completar Fecha.');	
	//--------------------------------------------------------------------
	errorescount += ValidarControlFecha('txtFecha', 'ErrorestxtFecha');	
	
	if( trimString($('#txtFechaVencimiento').val() ))
		errorescount += ValidarControlFecha('txtFechaVencimiento', 'ErrorestxtFechaVencimiento');	
	//--------------------------------------------------------------------
	
	if( $("#ErrorestxtFecha").text().trim() == '' && $("#ErrorestxtFechaVencimiento").text().trim() == '' ){
		var FechaHoy = GetFechaHoy();
		var Fecha = $('#txtFecha').val();
		var FechaVencimiento = $('#txtFechaVencimiento').val();
		var FechadeNotificacion = $('#idFechadeNotificacion').val();
		//console.log("FechadeNotificacion " + FechadeNotificacion);
		
		var parseFechaHoy = ParsearFecha(FechaHoy);
		var parseFecha = ParsearFecha(Fecha);
		var parseFechaVencimiento = ParsearFecha(FechaVencimiento);
		var parseFechadeNotificacion = ParsearFecha(FechadeNotificacion);
		
		//La Fecha del evento no debe ser mayor que hoy. 
		if (parseFechaHoy < parseFecha) {
			txtError = 'La Fecha del evento no debe ser mayor que hoy.';
			$("#ErrorestxtFecha").text(txtError);
			errorescount += 1;		
		}	
		
		if ( parseFecha > parseFechaVencimiento ) {
			txtError = 'La Fecha de Vencimiento debe ser mayor o igual a la fecha de notificación.';
			$('#ErrorestxtFechaVencimiento').text(txtError);
			errorescount += 1;
		}
		
		if (parseFecha < parseFechadeNotificacion) {				
			txtError = 'La Fecha del Evento debe ser mayor que la fecha de Notificacion del juicio.';
			$('#ErrorestxtFecha').text(txtError);
			errorescount += 1;
		}
	}	
	
	errorescount += MostrarError(IsNullZero, $('#cmbEventos'), $('#ErrorescmbEventos'), 'Debe seleccionar Evento.');	
	
	/*
		errorescount += MostrarError(IsNullZero, $('#txtObservaciones'), $('#ErrorestxtObservaciones'), 'Debe completar Observaciones.');	
	*/

	if(errorescount > 0){
		var x=$("#lblErrores");
		x.html("Errores ("+errorescount+").");					
		x.show("slow");
		return false;
	}
	else{
		$("#lblErrores").empty();		
		return true;
	}  
	
  }
  
  function ShowMessageOk(){
	alert("los datos se ingresaron correctamente");
  }
  
  //----------------------------------------------------------	

function iniDialogMensaje(){
	
	var btnSiguiente = {id: "btnAceptarMsj", 
						text: "", 
						click: function() { $( this ).dialog( "close" ); 
											window.location.href = '/index.php?pageid=134&id='+EventoID;
											return true; } };
							
	var btnCancelar = {	id: "btnCancelarMsj", 
						text: "", 
						click: function() { $( this ).dialog( "close" ); 
											window.location.href = '/EventosWebForm';
											 //redirectpage();
											 return false; } };	
											
	botones = [btnSiguiente, btnCancelar];
		
	$( "#dialogMensaje" ).dialog({
			position:{my: "center top",  at: "center top",  of: "#divContent"},
			autoOpen:false,
			modal: true,
			//show:"scale",
			buttons:botones	
	});
	
	if( document.getElementById('btnAceptarMsj'))	JQAsignaClaseCSS("#btnAceptarMsj", "btnAceptar");		
	if( document.getElementById('btnCancelarMsj'))	JQAsignaClaseCSS("#btnCancelarMsj", "btnCancelarEJ");	
}
//----------------------------------------------------------	