var x;
x=$(document).ready(inicio);

  function inicio(){
	var x =  $('#cmbFuero');
	x.change(CompletaFueroID);
	
	x =  $('#cmbJurisdiccion');
	x.change(CompletaJurisdiccion);
	
	SetearControlFecha("txtfechaconcurso", "btnFechaConcurso");			
	SetearControlFecha("TxtVtoArt32", "btnVtoArt32");			
	SetearControlFecha("txtQuiebra", "btnQuiebra");			
	SetearControlFecha("txtVtoArt200", "btnVtoArt200");			
	SetearControlFecha("txtVerificacioncredito", "btnVerificacioncredito");			
	
	$("#idAceptarAjax").click(CallSalvarModificacionCYQ);
	
  } 
  
function CallSalvarModificacionCYQ(){
	if(!ValidarModificacionCYQ()){return false;}
	
	var txtsindico = ValorElementoID('txtSindic');	
	var txtdireccion = ValorElementoID('txtDireccion');	
	var txtlocaclidad = ValorElementoID('txtLocalidad');	
	var txtfuero = ComboValorSeleccionado('cmbFuero');	
	
	var txttelefono = ValorElementoID('txtTelefonos');	
	var txtjurisdiccion = ValorElementoID('cmbJurisdiccion');	
	var txtjuzgado = ValorElementoID('txtJuzgadoID');	
	var txtsecretaria = ValorElementoID('txtSecretaria');	
	var fechaconcurso = ValorElementoID('txtfechaconcurso');	
	var fechaquiebra = ValorElementoID('txtQuiebra');	
	var fechaart32 = ValorElementoID('TxtVtoArt32');	
	var fechaart200 = ValorElementoID('txtVtoArt200');	
	var fverificacioncredito = ValorElementoID('txtVerificacioncredito');	
	//var usuario = ValorElementoID('usuario');	
	//var nroorden = ValorElementoID('nroorden');	
	var montoprivilegio = ValorElementoID('txtMontoPrivilegio');	
	var montoquirografario = ValorElementoID('txtMontoQuirografario');	
	
	if ( SalvarModificacionCYQ(Accion, txtsindico, txtdireccion, txtlocaclidad, txtfuero,	txttelefono, txtjurisdiccion, txtjuzgado, txtsecretaria, fechaconcurso, fechaquiebra, fechaart32, fechaart200, fverificacioncredito, usuario, nroorden, montoprivilegio, montoquirografario) == true){		
		
		if(Accion == 'EDIT')	MostrarVentana('¿Desea modificar los datos?');		
		if(Accion == 'ALTA')	MostrarVentana('¿Desea Ingresar los datos?');		
		
		//se recarga la misma pagina
		$("#idbtnCancelarVentana").click( function(){return false;} );
		$("#idbtnAceptarVentana").click( function(){ RedirectPage(); });
		
		return true;
	}	
	return false; 
}

function RedirectPage(){
	window.location.href = '/ModificacionCYQ'; 							
	return true;
}

function CompletaFueroID(){
	var x =  $('#cmbFuero');
	$('#txtFueroId').val(x.val());	
}  

function CompletaJurisdiccion(){
	var x =  $('#cmbJurisdiccion');
	$('#txtJurisdiccionID').val(x.val());	
}  

function ValidarModificacionCYQ(){
	var errorescount = 0;	
	$("#lblErrores").empty();
	$('#ErrorestxtMontoPrivilegio').empty();
	$('#ErrorestxtMontoQuirografario').empty();
	$('#Errorestxtfechaconcurso').empty();
	$('#ErroresTxtVtoArt32').empty();
	$('#ErrorestxtQuiebra').empty();
	$('#ErrorestxtVtoArt200').empty();
	$('#ErrorestxtVerificacioncredito').empty();
   /*ESTAS VALIDACIONES POR AHORA NO VAN,    EN ESTA PANTALLA NO SE VALIDA CASI NADA
	errorescount += MostrarError(IsNullZero, $('#txtSindic'), $('#ErrorestxtSindic'), 'Debe completar Sindico.');	
	errorescount += MostrarError(IsNullZero, $('#txtDireccion'), $('#ErrorestxtDireccion'), 'Debe completar Direccion.');	
	errorescount += MostrarError(IsNullZero, $('#txtLocalidad'), $('#ErrorestxtLocalidad'), 'Debe completar Localidad.');	
	errorescount += MostrarError(IsNotNullAndZero, $('#txtTelefonos'), $('#ErrorestxtTelefonos'), 'Debe completar Telefono.');	
	errorescount += MostrarError(IsNullZero, $('#cmbFuero'), $('#ErrorescmbFuero'), 'Debe seleccionar Fuero.');	
	errorescount += MostrarError(IsNotNullAndZero, $('#txtJuzgadoID'), $('#ErrorestxtJuzgadoID'), 'Debe completar JuzgadoID.');	
	errorescount += MostrarError(IsNotNullAndZero, $('#txtSecretaria'), $('#ErrorestxtSecretaria'), 'Debe completar SecretariaID.');	
	errorescount += MostrarError(IsNullZero, $('#cmbJurisdiccion'), $('#ErrorescmbJurisdiccion'), 'Debe seleccionar Jurisdiccion.');	
	errorescount += MostrarError(IsNullZero, $('#txtfechaconcurso'), $('#Errorestxtfechaconcurso'), 'Debe seleccionar Fecha Presentacion en Concurso.');	
	errorescount += MostrarError(IsNullZero, $('#TxtVtoArt32'), $('#ErroresTxtVtoArt32'), 'Debe seleccionar Fecha Vto.Art 32.');	
	errorescount += MostrarError(IsNullZero, $('#txtQuiebra'), $('#ErrorestxtQuiebra'), 'Debe seleccionar Fecha Declaracion de quiebra.');	
	errorescount += MostrarError(IsNullZero, $('#txtVtoArt200'), $('#ErrorestxtVtoArt200'), 'Debe seleccionar Fecha Vto.Art 200.');	
	errorescount += MostrarError(IsNullZero, $('#txtVerificacioncredito'), $('#ErrorestxtVerificacioncredito'), 'Debe seleccionar Fecha Verificacion de credito.');	
	
	
	$('#txtMontoPrivilegio').val($('#txtMontoPrivilegio').val().trim());
	
	$('#txtMontoQuirografario').val($('#txtMontoQuirografario').val().trim());
	errorescount += MostrarError(IsNotNullAndZero, $('#txtMontoQuirografario'), $('#ErrorestxtMontoQuirografario'), 'Debe completar Monto quirografario valido.');	
	errorescount += ValidarMoneda("#txtMontoQuirografario",'#ErrorestxtMontoQuirografario');
	*/
	
	errorescount += ValidarMonto("#txtMontoPrivilegio",'#ErrorestxtMontoPrivilegio');	
	errorescount += ValidarMonto("#txtMontoQuirografario",'#ErrorestxtMontoQuirografario');
	
	//-------------------------------------------------------------------------------------------------	
	errorescount += ValidarControlFecha('txtfechaconcurso', 'Errorestxtfechaconcurso');	
	errorescount += ValidarControlFecha('TxtVtoArt32', 'ErroresTxtVtoArt32');	
	errorescount += ValidarControlFecha('txtQuiebra', 'ErrorestxtQuiebra');	
	errorescount += ValidarControlFecha('txtVtoArt200', 'ErrorestxtVtoArt200');	
	errorescount += ValidarControlFecha('txtVerificacioncredito', 'ErrorestxtVerificacioncredito');	
	//-------------------------------------------------------------------------------------------------		
	errorescount += ValidarFechasConcursoArt32(); 
	errorescount += ValidarFechaArt32();
	errorescount += ValidarFechaArt200();
	
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

function ValidarFechasConcursoArt32(){
	
	if($('#Errorestxtfechaconcurso').text().trim() != '' ) return 0;
	if($('#ErroresTxtVtoArt32').text().trim() != '' ) return 0;
	
	var Txtfechaconcurso = $('#txtfechaconcurso').val().trim();
	var TxtVtoArt32 = $('#TxtVtoArt32').val().trim();
	 
	if (Txtfechaconcurso == '' && TxtVtoArt32 == '') {  
		var txtErrorText = 'Alguna de las 2 fechas (Concurso/Quiebra) deben tener un valor';				
		$('#Errorestxtfechaconcurso').text(txtErrorText);
		return 1;
	}
	
	return 0;
}

function ValidarFechaArt32(){
  
  if($('#Errorestxtfechaconcurso').text().trim() != '' ) return 0;
  if($('#ErroresTxtVtoArt32').text().trim() != '' ) return 0;
  if($('#txtfechaconcurso').val().trim() == '' ) return 0;
  if($('#TxtVtoArt32').val().trim() == '' ) return 0;
	
  var parseTxtfechaconcurso = ParsearFecha($('#txtfechaconcurso').val());
  var parseTxtVtoArt32 = ParsearFecha($('#TxtVtoArt32').val());
	
  if (parseTxtVtoArt32 < parseTxtfechaconcurso){  
    var txtErrorText = 'La fecha de Vencimiento del Art.32 no puede ser menor que la fecha de Concurso';
	$('#ErroresTxtVtoArt32').text(txtErrorText);
	return 1;
  }
  return 0;  
}

function ValidarFechaArt200(){

  if($('#ErrorestxtQuiebra').text().trim() != '' ) return 0;
  if($('#ErrorestxtVtoArt200').text().trim() != '' ) return 0;
  if($('#txtQuiebra').val().trim() == '' ) return 0;	
  if($('#txtVtoArt200').val().trim() == '' ) return 0;	
  
  var parsetxtQuiebra = ParsearFecha($('#txtQuiebra').val());
  var parsetxtVtoArt200 = ParsearFecha($('#txtVtoArt200').val());
    
  if (parsetxtVtoArt200 < parsetxtQuiebra){    
    var txtErrorText = 'La fecha de Vencimiento del Art.200 no puede ser menor que la fecha de Quiebra';     
	$('#ErrorestxtVtoArt200').text(txtErrorText);
	return 1;
  }
  return 0;
}
