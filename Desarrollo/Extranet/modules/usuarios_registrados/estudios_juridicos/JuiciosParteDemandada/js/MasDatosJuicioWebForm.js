var x;
x=$(document);
x.ready(inicio);

  function inicio(){
	$("#idbtnAceptar").click(CallSalvarMasDatosJuicio);	
	
	if(BloqueaControles == true)
		BloquearControlesForm('idMasDatosJuicioWebForm');	  
  }  
  
  function CallSalvarMasDatosJuicio(){
	if(!ValidarFormMasDatosJuicioWeb()){ return false;}
	var Domicilio = $('#txtDomicilio').val().trim();
	var Telefonos = $('#txtTelefonos').val().trim();  
	var Fax = $('#txtFax').val().trim(); 
	var Email = $('#txtEmail').val().trim(); 
	var usuario = usuarioSESSION; 
	var idJuicio = idJuicioSESSION;
		
	if (SalvarMasDatosJuicio(Domicilio, Telefonos, Fax, Email, usuario, idJuicio) == true){
		MostrarVentana('Los datos fueron actualizados.');		
		$("#idbtnAceptarVentana").click( function(){ redirectpage(); });		
	}
  }
  
 function redirectpage(){
	window.location.href = '/AdminWebForm'; 							
 }
  
 function Volver(){
  window.history.back();
 }
 
 function Redirect() {
	window.parent.location.href = '/MasDatosJuicioWebForm';
 }
 
 function ValidarFormMasDatosJuicioWeb(){	
	//no hay campos obligatorios.... asi que quito todas las validaciones
	
	return true;
	
	/*
	var errorescount = 0;	
	$("#lblErrores").empty();

	errorescount += MostrarError(IsNullZero, $('#txtDomicilio'), $('#ErrorestxtDomicilio'), 'Debe completar Domicilio.', false);	
	
	//errorescount += MostrarError(IsNullZero, $('#txtEmail'), $('#ErrorestxtEmail'), 'Debe completar Email.', false); 	
	var ErrorResult = RegExpValidarEmail("txtEmail");	
	if(ErrorResult != '' ){	
		errorescount += MostrarError('', '', $('#ErrorestxtEmail'), ErrorResult, false);		
	}	
	
	//errorescount += MostrarError(IsNullZero, $('#txtTelefonos'), $('#ErrorestxtTelefonos'), 'Debe completar Telefono.', false);
	ErrorResult = RegExpValidarTelefono("txtTelefonos");
	if(ErrorResult != '' ){
		errorescount += MostrarError('', '', $('#ErrorestxtTelefonos'), ErrorResult, false);
	}
		
	//errorescount += MostrarError(IsNullZero, $('#txtFax'), $('#ErrorestxtFax'), 'Debe completar Fax.', false);
 	ErrorResult = RegExpValidarTelefono("txtFax");
	if(ErrorResult != '' ){
		errorescount += MostrarError('', '', $('#ErrorestxtFax'), ErrorResult, false);
	}
	
	if(errorescount > 0){
		var x=$("#lblErrores");
		x.html("Errores ("+errorescount+").");
		x.show("slow");
		return false;
	}
	else{
		$("#lblErrores").html('');		
		return true;
	} 
	*/
 }
 
 