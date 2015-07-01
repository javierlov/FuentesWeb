var x;
x=$(document).ready(inicio);
  
  function inicio(){
	  SetearControlFecha("txtFechadeVto", "btnFechadeVto");
	  SetearControlFecha("txtFechadePago", "btnFechadePago");
	  SetearControlFecha("txtFechaExtincion", "btnFechaExtincion");  
	  $("#idAceptarAjax").click(CallSalvarAcuerdos);
  } 
  
  function CallSalvarAcuerdos(){
	if(!ValidarAcuerdosABMWeb()){return false;}
	
	var txtfechavenc = ValorElementoID('txtFechadeVto');	
	var txtMonto = ValorElementoID('txtMonto');	
	var txtfechapago = ValorElementoID('txtFechadePago');	
	var txtobservaciones = ValorElementoID('txtObservaciones');	
	var txtFechaExtincion = ValorElementoID('txtFechaExtincion');	
	var cmbTipo = ComboValorSeleccionado('cmbTipo');	

	if ( SalvarAcuerdosABM(Accion, txtfechavenc, txtMonto, txtfechapago, txtobservaciones, usuario, 
				nroorden, NroPago, txtFechaExtincion, cmbTipo) == true){
				
		if(Accion == 'EDIT') MostrarVentana('Acuerdo modificado correctamente.');		
		if(Accion == 'ALTA') MostrarVentana('Acuerdo ingresado correctamente.');		
		
		$("#idbtnCancelarVentana").click( function(){return false;} );
		$("#idbtnAceptarVentana").click( function(){ redirectpage(); });		
		return true;
		
	}	
	return false; 
  }

  function redirectpage(){
	window.location.href = '/AcuerdosWebForm';
	return true;	
  }
//----------------------------------------------------------	      
  
function ValidarAcuerdosABMWeb(){
	var errorescount = 0;	
	
	$('#ErrorestxtFechadeVto').empty();
	$('#Validacion').val('');
	$('#ErrorestxtFechadePago').empty();
	$('#ErrorestxtFechaExtincion').empty();
	
	txtError = 'El monto debe ser superior a 0';
	errorescount += MostrarError(IsNullZero, $('#txtMonto'), $('#ErrorestxtMonto'), txtError);	
	errorescount += MostrarError(IsNullZero, $('#txtFechadeVto'), $('#ErrorestxtFechadeVto'), 'Debe Seleccionar Fecha de Vto.');	
	/*
	errorescount += MostrarError(IsNullZero, $('#txtFechadePago'), $('#ErrorestxtFechadePago'), 'Debe Seleccionar Fecha de Pago.');	
	errorescount += MostrarError(IsNullZero, $('#txtObservaciones'), $('#ErrorestxtObservaciones'), 'Debe Completar Observaciones.');	
	errorescount += MostrarError(IsNullZero, $('#txtFechaExtincion'), $('#ErrorestxtFechaExtincion'), 'Debe Seleccionar Fecha Extincion.');	
	errorescount += MostrarError(IsNullZero, $('#cmbTipo'), $('#ErrorescmbTipo'), 'Debe Seleccionar un tipo.');	
	*/
	errorescount += ValidarControlFecha('txtFechadeVto', 'ErrorestxtFechadeVto');	
	errorescount += ValidarControlFecha("txtFechadePago", "ErrorestxtFechadePago");
	errorescount += ValidarControlFecha("txtFechaExtincion", "ErrorestxtFechaExtincion");  
	
	errorescount += ValidarFechaVto();
		
	errorescount += ValidarMonto('#txtMonto','#ErrorestxtMonto');
	
	if(errorescount > 0){
		var x=$("#lblErrores");
		x.html("Errores ("+errorescount+").");					
		x.show("slow");
		return false;
	}
	else{
		var x=$("#lblErrores");
		x.empty();
		x.hide(	);
		return true;
	}
}  

function ValidarFechaVto(){
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosConcursosQuiebras.php";

	var fechavenc = $('#txtFechadeVto').val();
	var nroorden = $('#nroorden').val();
	var nropago = $('#NroPago').val();

	var pageParams = "FUNCION=ObtenerValidacionFechaAcuerdoModif";		
		pageParams += "&fechavenc="+fechavenc;		
		pageParams += "&nroorden="+nroorden;		
		pageParams += "&nropago="+nropago;		
	
	var valorContext = "fechavenc: " + fechavenc;
		
	$("#Validacion").val("");
	
	ValidarFuncionServerSincro(pagefunciones, pageParams, valorContext, callbackError, recuperarDatosCallback);

	var valresult = parseInt($("#Validacion").val());
	
	if(isNaN(valresult)) 
		valresult = 0;
		
	//console.log("Resultado ValidarFuncionServerSincro " + valresult);
	
	if( valresult > 0 ) {		
		txtError = 'La fecha de vto no puede ser igual a una existente';
		$('#ErrorestxtFechadeVto').text(txtError);
		return 1;		
	}	
	return 0;		
}
//------------------------------------------------------------------------

//------------------------------------------------------------------------
