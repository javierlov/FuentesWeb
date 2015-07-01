var x;
x=$(document);
x.ready(inicio);

  function inicio(){      
	SetearControlFecha("idtxtFecha", "idbtnFecha");
		
	$("#idcmbJurisdiccion").change(CompletarTxtJurisdiccion);	
	$("#idcmbFuero").change(CompletarTxtFuero);
	$("#idcmbJuzgadoNro").change(CompletarTxtJuzgado);	
	$("#idcmbSecretaria").change(CompletarSecretaria);
	
	$("#idcmbMotivo").change( BuscarMotivo );
	
	$("#idAceptarAjax").click(CallSalvarInstanciaABM);
	
	if($('#txtAnioExp').val() != '') BuscarAnioExpediente();	
  }
//----------------------------------------------------------	
  function CallSalvarInstanciaABM(){
	if(!ValidarFormInstanciasABMWebForm()){
		return false;
	}
	
	var NroJuicio = JuicioEnTramiteSESSION;
	var cmbJurisdiccion = $('#idcmbJurisdiccion').val().trim();
	var cmbFuero = $('#idcmbFuero').val().trim();
	var cmbJuzgadoNro = $('#idcmbJuzgadoNro').val().trim();
	var cmbSecretaria = $('#idcmbSecretaria').val().trim();
	var txtNroExp = $('#txtNroExp').val().trim();
	var txtAnioExp = $('#txtAnioExp').val().trim();
	var cmbMotivo = $('#idcmbMotivo').val().trim();
	var txtDetalle = $('#txtDetalle').val().trim();
	var usuario = LoginNameSESSION;
	var txtFecha = $('#idtxtFecha').val().trim();
		
	var accion = $('#idhaccion').val().trim();	
		
	if (SalvarInstanciaABM(accion, nroInstancia,
		NroJuicio, cmbJurisdiccion, cmbFuero, cmbJuzgadoNro, cmbSecretaria, txtNroExp, 
		txtAnioExp, cmbMotivo, txtDetalle, usuario, txtFecha) == true){
		
		if(accion == 'EDIT')
			MostrarVentana('Instancia modificada correctamente.');		
		if(accion == 'ALTA')
			MostrarVentana('Instancia ingresada correctamente.');		
		
		$("#idbtnCancelarVentana").click( function(){return false;} );
		$("#idbtnAceptarVentana").click( function(){ redirectpage(); });		
	}	
  }  
  
  function redirectpage(){
	window.location.href = '/InstanciasWebForm'; 							
	return true;	
  }  
//----------------------------------------------------------	
  function BuscarMotivo(){
	var iddiv = $("#divmotivo");							
	BuscarMotivoJuzgado(iddiv, $("#idcmbMotivo").val());
	$('#lblErrorestxtMotivo').empty();	
  }
  
  function BuscarAnioExpediente(){	
	var iddiv = $("#divtxtAnioExp");							
	iddiv.text('S');
	
	ObtenerAnioValidoExpediente(iddiv, $("#txtAnioExp").val());	
	//BuscaAnioValidoExpedienteSINCRO("#divtxtAnioExp", $("#txtAnioExp").val());	
	$('#lblErrorestxtNumExp').empty();	
  }
  
  function ValidarAnioExpediente(){
	var iddiv = $("#divtxtAnioExp");						
	
	if(iddiv.val() == 'S')
		return 0;
	else
		return 1;
		
  }
  
  function ValidarMotivo(){
	
	errorescount = 0;	
	$('#lblErrorestxtMotivo').empty();
	
	if ( $("#JurisdiccionPrev").length > 0 ) {
	
		var iddiv = $("#divmotivo");						
		var Motivo = iddiv.text();
		
		if(MostrarError(IsNullZero, $('#idcmbMotivo'), $('#lblErrorestxtMotivo'), 'Debe seleccionar un Motivo.') == 0) {		

			var Instancia = $("#InstanciaPrev").val();		
			var fraInstancia = $("#txtInstanciaID").text();						
			
			if (!((fraInstancia < Instancia) && (Motivo == 'ME'))) {			
				if (!((fraInstancia == Instancia) && (Motivo == 'IG'))) {				
					if (!((fraInstancia > Instancia) && (Motivo == 'MA'))) {									
					  var txtError = 'El Motivo seleccionado no corresponde al cambio de instancia';	
					  errorescount = MostrarError('', '', $('#lblErrorestxtMotivo'), txtError);
					}
				}
			}
			
		}else{
			errorescount = 1;	
		}
	}
	
	var motivoselect = ComboValorSeleccionado("idcmbMotivo");
	if(motivoselect == '0'){
		var txtError = 'Motivo seleccionado es inválido';	
		errorescount = MostrarError('', '', $('#lblErrorestxtMotivo'), txtError);				
	}
	
	return  errorescount;
  }
  
  function ValidarExpedienteDetalles(){	
	if (ValidarExpediente() == false) {
		var textError = 'El numero de expediente ya existe';		
		$("#lblErrorestxtNumExp").text(textError);
		return 1;
	}
	
	var resultado = MostrarError(IsNullZero, $('#txtNroExp'), $('#lblErrorestxtNumExp'), 'Debe Completar Num. Exp.', true);	
	if(resultado > 0) return 1;
	
	//if( $('#txtNroExp').val().trim() > '' )	errorescount += MostrarError(IsNullZero, $('#txtAnioExp'), $('#lblErrorestxtNumExp'), 'Debe Completar Num. Exp. ', true);
	
	if($('#txtAnioExp').text().trim() != '' ){
		if (ValidarAnioExpediente()){
			txtError = 'El año del expediente no es valido';
			MostrarError('', '', $('#lblErrorestxtNumExp'), txtError, true);	
			return 1;
		}
	}
	
	return 0;
  }
  
  function ValidarFormInstanciasABMWebForm(){	
  
	var errorescount = 0;
	var mesesNum = new Array ("01","02","03","04","05","06","07","08","09","10","11","12");	
	$("#lblErrorestxtNumExp").empty(); 	
	$("#lblErrorestxtJurisdiccion").empty(); 	
	$("#lblErrorestxtFuero").empty(); 	
	$("#lblErrorestxtJuzgado").empty(); 	
	$("#lblErrorestxtSecretaria").empty(); 	
	$("#lblErrorestxtFecha").empty(); 	
	$("#lblErrorestxtDetalle").empty(); 	
	
	errorescount += ValidarExpedienteDetalles();	
	//------------------------------------------------------------------------------------------------------
	errorescount += MostrarError(IsNullZero, $("#idcmbJurisdiccion"), $("#lblErrorestxtJurisdiccion"), "Debe seleccionar una Jurisdiccion.");
	errorescount += MostrarError(IsNullZero, $('#idcmbFuero'), $('#lblErrorestxtFuero'), 'Debe seleccionar una Fuero.');
	errorescount += MostrarError(IsNullZero, $('#idcmbJuzgadoNro'), $('#lblErrorestxtJuzgado'), 'Debe seleccionar una Juzgado.');	
	errorescount += MostrarError(IsNullZero, $('#idcmbSecretaria'), $('#lblErrorestxtSecretaria'), 'Debe seleccionar una Secretaria.');	
	//------------------------------------------------------------------------------------------------------
	errorescount += ValidarMotivo();
	
	errorescount += ValidarControlFecha('idtxtFecha', 'lblErrorestxtFecha');	
	//------------------------------------------------------------------------------------------------------	
	var controlErrorVal = document.getElementById('lblErrorestxtFecha').innerHTML;
	if(controlErrorVal == ''){
		errorescount += ValidarFechaIngreso();
	}
	
	//Motivo seleccionado es inválido
	
	if ( $("#JurisdiccionPrev").length > 0 ) {
		JurisdiccionPrev = $("#JurisdiccionPrev").val();
		FueroPrev = $("#FueroPrev").val();
		JuzgadoPrev = $("#JuzgadoPrev").val();
		
		Jurisdiccion = "";
		Fuero = "";
		Juzgado = "";		

		if ( $("#JurisdiccionPrev").length > 0 ) {
			Jurisdiccion = $("#idcmbJurisdiccion").val();
			Fuero = $("#idcmbFuero").val();
			Juzgado = $("#idcmbJuzgadoNro").val();
		}
		
		$('#ErrorestxtInstancia').html('');		
		if(JurisdiccionPrev != Jurisdiccion || FueroPrev != Fuero || JuzgadoPrev != Juzgado){
			errorescount += MostrarError('', '', $('#ErrorestxtInstancia'), 'La Instancia debe ser igual a la que se encontraba.');
		}		
		
	}else{
		$('#ErrorestxtInstancia').html('');
	}
	
	errorescount += MostrarError(IsNullZero, $('#txtDetalle'), $('#lblErrorestxtDetalle'), 'Debe completar detalle.');
	
	if(errorescount > 0){
		var x=$("#lblErrores");
		x.html("Errores ("+errorescount+").");
		x.show("fast");
		return false;
	}
	else{
		$("#lblErrores").empty();		
		return true;
	}  
  }
  
  function ValidarFechaIngreso(){
	
	var retorno = MostrarError(IsNullZero, $('#idtxtFecha'), $('#lblErrorestxtFecha'), 'Debe seleccionar una Fecha.')
	if(retorno > 0){ return 1;}

	var FechaHoy = ParsearFecha(GetFechaHoy());
	var FechaTxt = ParsearFecha($('#idtxtFecha').val());
	
	if(FechaTxt > FechaHoy){
		var mensaje = 'La fecha no puede ser mayor que la actual';		
		$('#lblErrorestxtFecha').html(mensaje);
		return 1;
	}	
	return 0;
  }
  
  function esperar(espera){
	string="pausa_alerta("+espera+");";
	setTimeout(string,espera);
 }
 
 function pausa_alerta(espera){
	alert("Ok "+espera/1000+" Segundos"); 
 }
 
  function ValidarExpediente(){
		
	BuscarAnioExpediente();
	
	var iderrorctrl = $("#lblErrorestxtNumExp");
	var nroInstancia = $('#idNroJuicio').val(); 
	var NroExpediente = $('#txtNroExp').val(); 
	var AnioExpediente = $('#txtAnioExp').val(); 
	var Secretaria = $('#idcmbSecretaria').val(); 	
	
	iderrorctrl.empty();	
		
	if( parseInt(Secretaria) > 0){
		//ValidarExpedienteNroYearSecretaria(iderrorctrl, nroInstancia, NroExpediente, AnioExpediente, Secretaria);				
		var resultado = ValidarYearMesExpediente(iderrorctrl, nroInstancia, NroExpediente, AnioExpediente, Secretaria);
		
		if (iderrorctrl.text().trim() == '' ) 
			return true;
		else 
			return false;	
	}
	
  }
  
  function CompletarTxtJurisdiccion(){
  	LimpiarControles(0);	
	var valorJuridiccion = $("#idcmbJurisdiccion").val();
	var valorFuero = "0";	
	
  	CompletarFueroSubmit("#idcmbFuero", EnvioJur(), EnvioJur(), valorJuridiccion, valorFuero);  	
  }
  
  function EnvioJur(){ $("#lblMensaje").text('');  }
  function finJur(){ $("#lblMensaje").text('');  }  
  
  function BloquearControles(){  	  	
  	$("#idcmbFuero").attr("disabled", "disabled");
  	$("#idcmbJuzgadoNro").attr("disabled", "disabled");
  	$("#idcmbSecretaria").attr("disabled", "disabled");
  	$("#idcmbMotivo").attr("disabled", "disabled");
  }
  
  function LimpiarControles(nivel){
  	if(nivel == 0){
	  	$("#idcmbFuero").empty();	  	
	}  	
	if(nivel <= 1){
	  	$("#idcmbJuzgadoNro").empty();	  	
		$("#txtInstancia").empty();	  	
	}  	
	if(nivel <= 2){
	  	$("#idcmbSecretaria	").empty();	  	
	}  	
			  	
  }
  
  /*----------------------------------------------------------------*/
  function CompletarTxtFuero(){
  	LimpiarControles(1);	
	var valorJurisdiccion= $("#idcmbJurisdiccion").val();
	var valorFuero=$("#idcmbFuero").val();
	
	//0 asi no carga el valor por default en el combo
	var valorJuzgado= 0; //$("#idtxtJuzgado").val();	
	
  	CompletarJuzgadoSubmit("#idcmbJuzgadoNro", inicioEnvioCargarFuero(), valorJurisdiccion, valorFuero, valorJuzgado);  	
  }
  
  function inicioEnvioCargarFuero(){
  	var texto = $("#idcmbFuero option:selected").text();  
  }  
/*------------------------------------------------------*/  
  function CompletarTxtInstancia(){	
	var txtInstanciaTxt = $("#txtInstancia");		
	var valorJurisdiccion = $("#idcmbJurisdiccion").val();
	var valorFuero = $("#idcmbFuero").val();
	var valorJuzgado = $("#idcmbJuzgadoNro").val();	
	
	var txtInstanciaID = $("#txtInstanciaID");			
	
	if(valorJuzgado > 0){
		ObtenerInstanciaSeleccionada(txtInstanciaTxt, valorJurisdiccion, valorFuero, valorJuzgado, '');
		ObtenerInstanciaSeleccionada(txtInstanciaID, valorJurisdiccion, valorFuero, valorJuzgado, 'CampoID');		
	}
  }
  
  function CompletarTxtJuzgado(){
	LimpiarControles(2);
  	
	var valorJuzgado= $("#idcmbJuzgadoNro").val();
	var valorSecretaria=$("#idcmbSecretaria").val();	
	
	CompletarSecretariaSubmit("#idcmbSecretaria", inicioEnvioCargarSecretaria(), valorJuzgado, 0)
	
	$("#txtInstancia").empty();	  	  	
	CompletarTxtInstancia();
		
  }
/*------------------------------------------------------*/  
  
  function inicioEnvioCargarSecretaria(){  	
  	var texto = $("#idcmbSecretaria option:selected").text();  	
  }
    
  function CompletarSecretaria(){  	
  	LimpiarControles(3);
  }
  
  
