//R:\Testing\Extranet\modules\usuarios_registrados\estudios_juridicos\ConcursosQuiebras\CuotasWebForm.php
  function inicio(){	
	SetearControlFecha("txtFecha", "btnFecha");	
	$("#idAceptarAjax").click(CallSalvarCuotas);
  }
  
  function CallSalvarCuotas(){
	if(!ValidarCuotasWebForm()){return false;}
	
	var txtFecha = ValorElementoID('txtFecha');	
	var cantcuota = ValorElementoID('txtcantcuotas');	
	var periodicidadCuotas = ValorElementoID('txtperiodicidadCuotas');	
	var txtMonto = ValorElementoID('txtMonto');	
	var cmbTipo = ComboValorSeleccionado('cmbTipo');	
/*	
	$usuario = $_SESSION["usuario"];
	$nroorden = $_SESSION["ArrayCuotasWebForm"]["nroorden"];
*/	
	if ( SalvarCuotas(Accion, txtFecha, cantcuota, periodicidadCuotas, txtMonto, usuario, nroorden, cmbTipo) == true){		
		
		if(Accion == 'EDIT')
			MostrarVentana('Cuota modificada correctamente.');		
		if(Accion == 'ALTA')
			MostrarVentana('Cuota ingresada correctamente.');		
		
		$("#idbtnCancelarVentana").click( function(){return false;} );
		$("#idbtnAceptarVentana").click( function(){ RedirectPage(); });
				
		return true;
	}	
	return false; 
  }
  
  function RedirectPage(){
	window.location.href = '/AcuerdosWebForm'; 							
	return true;
  }
  
  
  function ValidarCuotasWebForm(){
	var errorescount = 0;	
	$("#lblErrores").empty();
	$("#ErrorestxtFecha").empty();
   
	errorescount += MostrarError(IsNullZero, $('#txtMonto'), $('#ErrorestxtMonto'), 'Debe completar Monto.', false);	
	if($('#ErrorestxtMonto').val().trim() == '' ){
		errorescount += ValidarMoneda($('#txtMonto'), $('#ErrorestxtMonto'));
	}
	
	errorescount += MostrarError(IsNullZero, $('#txtFecha'), $('#ErrorestxtFecha'), 'Debe completar Cant. Cuotas.', false);	

	txtErrorCuotas = 'La cantidad de cuotas debe ser superior a 0';
	errorescount += MostrarError(IsNullZero, $('#txtcantcuotas'), $('#Errorestxtcantcuotas'), txtErrorCuotas, false);	
	
	txtErrorPeriodo = 'La periodicidad de las cuotas debe ser superior a 0';
	errorescount += MostrarError(IsNullZero, $('#txtperiodicidadCuotas'), $('#ErrorestxtperiodicidadCuotas'), txtErrorPeriodo, false);	
	/*
	errorescount += MostrarError(IsNullZero, $('#cmbTipo'), $('#ErrorescmbTipo'), 'Debe Seleccionar Tipo.', false);	
	*/
	errorescount += ValidarControlFecha('txtFecha', 'ErrorestxtFecha');		
	errorescount += ValidarCuota();
	
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
		x.hide("slow");
		return true;
	}
  }
  
  function ValidarCuota(){
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosConcursosQuiebras.php";
	
	var nroorden = $("#nroorden").val();
	var fechavenc = $("#txtFecha").val();
	var cantcuotas = $("#txtcantcuotas").val();
	var tiempo = $("#txtperiodicidadCuotas").val();
		
	var pageParams = "FUNCION=ObtenerValidacionFechaCuotas";		
	pageParams += "&fechavenc=" + fechavenc;		
	pageParams += "&nroorden=" + nroorden;		
	pageParams += "&cantcuotas=" + cantcuotas;	
	pageParams += "&tiempo=" + tiempo;	
	
	var valorContext = "fechavenc: " + fechavenc;
		
	$("#Validacion").val("");
	
	ValidarFuncionServerSincro(pagefunciones, pageParams, valorContext, callbackError, recuperarDatosCallback);

	var valresult = parseInt($("#Validacion").val());
	
	if(isNaN(valresult)) 
		valresult = 0;
		
	//console.log("Resultado val result " + valresult);
	
	if( valresult > 0 ) {		
		txtError = "La fecha de vto de alguna cuota coincide con una existente";
		$("#ErrorestxtFecha").text(txtError);
		return 1;		
	}	
	return 0;	
  }
  