var x;
x=$(document);
x.ready(inicio);

  function inicio(){
		
	$("#idcmbJurisdiccion").click(CompletarTxtJurisdiccion);	
	$("#idcmbFuero").change(CompletarTxtFuero);
	$("#idcmbJuzgadoNro").change(CompletarInstancia);
	
	$("#idbtnModificar").click(HabilitarControles);		
	
	leerJurisdiccion();	
	if(BloqueaControles == true) BloquearControlesForm('idAdminWebForm');	
	//$("#idAceptarAjax").click(CallSalvarAdmin);
	
  } 
 //---------------------------------------------- 
  function CallSalvarAdmin(){
	if(!ValidarAdminWebForm() ) { return false;}
	//var NroJuicio = $('#NroJuicio').val().trim();	
	//var usuario = $('#usuario').val().trim();	
	//var cmbJurisdiccion = $('#idcmbJurisdiccion').val().trim();		
	var cmbJurisdiccion = ComboValorSeleccionado('idcmbJurisdiccion');		
	var cmbFuero = ComboValorSeleccionado('idcmbFuero');		
	var cmbJuzgadoNro = ComboValorSeleccionado('idcmbJuzgadoNro');		
	var cmbSecretaria = ComboValorSeleccionado('cmbSecretaria');		
	
	//ValorElementoID	
	var txtNroExp = ValorElementoID('txtNroExp');		
	var txtAnioExp = ValorElementoID('txtAnioExp');		
	var txtResProbable = ValorElementoID('txtResProbable');		
	
	var cmbEstado = ComboValorSeleccionado('idcmbEstados');			
	
	var Accion = 'EDIT';
	
	if ( SalvarAdmin(Accion, txtResProbable, cmbEstado, usuario,
				NroJuicio, cmbJurisdiccion, cmbFuero, cmbJuzgadoNro, 
				cmbSecretaria, txtNroExp, txtAnioExp) == true){
				
		if(Accion == 'EDIT')
			MostrarVentana('Datos del Juicio modificados correctamente.');	
			
		$("#idbtnAceptarVentana").click( function(){ redirectpage(); });
		return true;
	}
		return false;  
  }
  
  function redirectpage(){
	window.location.href = '/AdminWebForm'; 							
	return true;
  }
  
 //---------------------------------------------- 
  
  function ValidarExpediente(){
		
	var iderrorctrl = $("#ErrorestxtNroExp");
	var nroInstancia = $('#idNroJuicio').val(); 
	var NroExpediente = $('#txtNroExp').val(); 
	var AnioExpediente = $('#txtAnioExp').val(); 
	var Secretaria = $('#cmbSecretaria').val(); 	
	
	iderrorctrl.empty();
		
	if( parseInt(Secretaria) > 0){		
		var resultado = ValidarYearMesExpediente(iderrorctrl, nroInstancia, NroExpediente, AnioExpediente, Secretaria);
		
		if (iderrorctrl.text().trim() == '' ) 
			return true;
		else 
			return false;	
	}
	
  }

 function HabilitarControles(){
	/*deshabilita controles
		$('#idcmbJurisdiccion').attr('disabled','-1')
	*/	
	///// habilitar Controles
	$('#idcmbJurisdiccion').removeAttr('disabled');
	$('#idcmbJuzgadoNro').removeAttr('disabled');
	$('#idcmbFuero').removeAttr('disabled');
	$('#cmbSecretaria').removeAttr('disabled');
	
	/*
		jQuery 1.9-
		$('#control').attr('readonly', true);
		jQuery 1.9+
		$('#control').prop('readonly', true);
	*/
	
	$('#txtNroExp').prop('readonly', false);	
	$('#txtAnioExp').prop('readonly', false);	
	$('#txtResProbable').prop('readonly', false);	
	
	$("#panelbotones").empty();	
		
	document.getElementById('panelbotonesAceptar').style.display = 'block';
	document.getElementById('divContent').style.overflow = 'hidden';
	
	$("#idAceptarAjax").click(CallSalvarAdmin);
	
	//Se agrega el combo Estados....
	var cmb = $("#iddivEstado");
	cmb.empty();	
	$comboHTML = "<select name='cmbEstado' id='idcmbEstados' class='combo' ></select>";
	cmb.html($comboHTML);	
	
	//JT_IDESTADO
	seleccionado = $('#JT_IDESTADO').val();				
	CompletarComboEstadosJuridicos("#idcmbEstados", EnvioJur(), seleccionado);	
		
 } 
  
	 function BotonCancelar(){	
		var valor = $("#idbtnCancelar");
		var r = confirm("¿Cancelar la modificacion?");
		if(r == true){
			window.location.href='/AdminWebForm';
		}
	 }

  function ValidarAdminWebForm(){
		var errorescount = 0;	
		$("#lblErrores").empty();
		//$("#ErrorestxtNroExp").empty();
		
		//txtResProbable VER MAIL Validaciones de datos (Montero, Melina <mmontero@provart.com.ar> martes 12/08/2014 11:22)
		//errorescount += MostrarError(IsNullZero, $('#txtResProbable'), $('#ErrorestxtResProbable'), 'Debe completar Res. Probable.');	
		errorescount += MostrarError(IsNullZero, $('#idcmbEstados'), $('#ErrorestxtEstado'), 'Debe seleccionar un estado.');			
		
		var txtErrorJurisdiccion = 'Jurisdiccion seleccionada es inválida';
		errorescount += MostrarError(IsNullZero, $('#idcmbJurisdiccion'), $('#ErrorescmbJurisdiccion'), txtErrorJurisdiccion);
		
		var txtErrorJuzgado = 'Nro de Juzgado seleccionado es inválido';
		errorescount += MostrarError(IsNullZero, $('#idcmbJuzgadoNro'), $('#ErroresidcmbJuzgadoNro'), txtErrorJuzgado);
		//errorescount += MostrarError(IsNullZero, $('#idtxtInstancia'), $('#ErrorestxtInstancia'), 'Debe completar Instancia.');
		
		var txtErrorFuero = 'Fuero seleccionado es inválido';
		errorescount += MostrarError(IsNullZero, $('#idcmbFuero'), $('#ErrorescmbFuero'), txtErrorFuero);
		
		var txtErrorSecretaria = 'Secretaria seleccionada es inválida';
		errorescount += MostrarError(IsNullZero, $('#cmbSecretaria'), $('#ErrorescmbSecretaria'), txtErrorSecretaria);

		if($('#ErrorescmbJurisdiccion').text().trim() == ''){
			if ( !ValidarExpediente() ) {
				errorescount += 1;
				$("#ErrorestxtNroExp").text("El número de expediente ya existe");
			}
		}

		
		if( $('#ErrorestxtNroExp').text().trim() == '' )
			errorescount += MostrarError(IsNullZero, $('#txtNroExp'), $('#ErrorestxtNroExp'), 'Debe completar Nro. Exp.', true);
		
		//if( $('#txtNroExp').val().trim() > '' )	errorescount += MostrarError(IsNullZero, $('#txtAnioExp'), $('#ErrorestxtNroExp'), 'Debe completar Nro. Exp.', true);
		
		if(errorescount > 0){
			var x=$("#lblErrores");
			x.html("Errores ("+errorescount+").");
			x.show("slow");
			return false;
		}
		else{
			var x=$("#lblErrores");
			x.empty().hide("slow");
			return true;
		}  

	}		 
//----------------------------------------------------------------
  function CompletarInstancia(){	
	idcmbJuzgadoNro = $("#idcmbJuzgadoNro");
	cmbSecretaria = $("#cmbSecretaria");
	idcmbJurisdiccion = $("#idcmbJurisdiccion");
	idcmbFuero = $("#idcmbFuero");
	idtxtInstancia = $("#idtxtInstancia");
	
	BuscaCompletaInstancia(idcmbJuzgadoNro, cmbSecretaria, idcmbJurisdiccion, idcmbFuero, idtxtInstancia);
  }

function CompletarTxtFuero(){  	
  	LimpiarControles(1);	
	var valorJurisdiccion= $("#idcmbJurisdiccion").val();
	var valorFuero=$("#idcmbFuero").val();
	var valorJuzgado="0";	
  	CompletarJuzgadoSubmit("#idcmbJuzgadoNro", EnvioJur(), valorJurisdiccion, valorFuero, valorJuzgado);  	
}

function CompletarTxtJurisdiccion(){
  	var valor = $("#idcmbJurisdiccion").val();  	  	 	
  	LimpiarControles(0);	
	var valorJuridiccion = valor;
	var valorFuero = "0";		
  	CompletarFueroSubmit("#idcmbFuero", EnvioJur(), EnvioJur(), valorJuridiccion, valorFuero);  

	//OcultarDivProcesando();	
  }
  
  function EnvioJur(){			
	return true;
  }

 function leerJurisdiccion(){	
	var valor = $("#idcmbJurisdiccion option:selected").text();	
	valor = valor + "  " + $("#idcmbJuzgadoNro option:selected").text();	
	
	$("#idHJuzgadoComp").val(valor);	
    return valor;
 }

 function LimpiarControles(nivel){
  	if(nivel == 0){
	  	$("#idcmbFuero").empty();	  	
	}  	
	if(nivel <= 1){
	  	$("#idcmbJuzgadoNro").empty();	  	
	}  	
	if(nivel <= 2){		
		$("#idtxtInstancia").empty();	  	
	  	$("#cmbSecretaria").empty();	  	
	}  	  		
 }
//---------------------------------------------------------------- 
 
 
 function hiddenJurisdiccion(){	
	var valor = $("#idHJuzgadoComp").val();
	MostrarVentana(valor);
 }
 
 function leer_cmbJurisdiccion(){	
	valor = leerJurisdiccion();	
    MostrarVentana(valor);
  }
  //----------------------------------------------------
  
  function BuscaCompletaInstancia(idcmbJuzgadoNro, cmbSecretaria, idcmbJurisdiccion, idcmbFuero, idtxtInstancia){	
  	LimpiarControles(2);  		
	var valorSecretaria=0;	
	var valorJurisdiccion = 0;
	var valorFuero= 0;
	var valorJuzgado= $(idcmbJuzgadoNro).val();
	
	CompletarSecretariaSubmit(cmbSecretaria, function(){return true;}, valorJuzgado, valorSecretaria);
	
	/////completa el control instancia es readonly
	valorJurisdiccion = $(idcmbJurisdiccion).val();	
	valorFuero=$(idcmbFuero).val();
		
	ObtenerInstanciaSeleccionada(idtxtInstancia, valorJurisdiccion, valorFuero, valorJuzgado, '');		
	
  }  
//--------------------------------------------------------
 function MostrarVentanaResultadoOK(){
	/* Muestra la ventana de mensaje */	
	var mensajeResultado = '¿Cancela la modificacion?';
	MostrarVentanaOKCancel(mensajeResultado);	
		
	$("#idbtnCancelarOKCancel").click( function(){ RedirectCancelarOKCancel();  }  );	
	$("#idbtnAceptarOKCancel").click( function(){ RedirectAceptarOKCancel(); }  );	
  }
  
  function RedirectCancelarOKCancel(){
	document.getElementById('VentanaMensajeOKCancel').style.display = 'none';
	document.getElementById('VentanaFondoOKCancel').style.display = 'none';
  }
  
  function RedirectAceptarOKCancel(){
	window.location.href = '/AdminWebForm';
	return true;
  }
  //--------------------------------------------------------