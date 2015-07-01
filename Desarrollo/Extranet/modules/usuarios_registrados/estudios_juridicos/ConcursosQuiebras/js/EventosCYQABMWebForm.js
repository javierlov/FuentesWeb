var x;
x=$(document).ready(inicio);

  function inicio(){
	SetearControlFecha("txtFecha", "btnFecha");		
	
	$("#idAceptarAjax").click(CallSalvarEventosCYQABM);	
  } 
  
  function CallSalvarEventosCYQABM(){
	if(!ValidarEventosCYQABM()){return false;}

	var txtfecha = ValorElementoID('txtFecha');	
	var txtobservaciones = TextAreaText('txtObservaciones');	
	var cmbEventos = ComboValorSeleccionado('cmbEventos');		
			
	if ( SalvarEventosCYQABM(Accion, txtfecha, txtobservaciones, usuario, cmbEventos, nroorden, nroevento) == true){	
	
		if(Accion == 'EDIT')	MostrarVentana('Evento modificado correctamente.');		
		if(Accion == 'ALTA')	MostrarVentana('Evento ingresado correctamente.');		
		
		$("#idbtnCancelarVentana").click( function(){return false;} );
		$("#idbtnAceptarVentana").click( function(){ RedirectPage(); });					
		return true;
	}
		return false;  
  }
  
  function RedirectPage(){
		window.location.href = '/EventosCYQWebForm';
		return true;
  }
  
  function ValidarEventosCYQABM(){
	var errorescount = 0;	
	$("#lblErrores").empty();
	$("#ErrorestxtFecha").empty();
   
	errorescount += MostrarError(IsNullZero, $('#cmbEventos'), $('#ErrorescmbEventos'), 'Debe seleccionar un Evento.');		
	errorescount += MostrarError(IsNullZero, $('#txtFecha'), $('#ErrorestxtFecha'), 'Debe seleccionar una Fecha.');	
	errorescount += ValidarControlFecha('txtFecha', 'ErrorestxtFecha');		
	//errorescount += MostrarError(IsNullZero, $('#txtObservaciones'), $('#ErrorestxtObservaciones'), 'Debe completar Observaciones.');	
		
	if(errorescount > 0){
		var x=$("#lblErrores");
		x.html("Errores ("+errorescount+").");					
		x.show("slow");
		return false;
	}
	else{
		var x=$("#lblErrores");
		x.empty();
		x.hide("slow");
		return true;
	}   
  }
  