var x = $(document).ready(AsignarEventos); 
var BloqueaControles = false;
  
  function AsignarEventos(){	  
	$("#dialogMensaje").dialog({autoOpen: false});		
	
	$("#idbtnAceptar").click(Validar);
	$("#idbtnCanclear").click(NoValidar);		
	//calendar		
	SetearControlFecha("idtxtFecha", "idbtnFecha");	
	$("#idtxtFecha").keypress(LimpiarFechaError);		
	$("#idbtnFecha").click(LimpiarFechaError);		
	
	SetearControlFecha("idtxtFechaRecep", "idbtnFechaRecep");		
	
	if(BloqueaControles == true) BloquearControlesForm('idSentenciaWebForm');	  

	$("#idAceptarAjax").click(CallSalvarSentencia);	
	$("#cmbSentencia").change(ActualizarControles);
	
	ContarCaracteres();
	
	if(MuestraMensajeProceso == 1 ) muestraDialogMensajeError();
	if(MuestraMensajeProceso == 2 ) muestraDialogMensaje();
  }
  
  function ContarCaracteres(){
	CuentaCaracteres('txtDetalleSentencia', 'idcontarcaracteres', true);
  }
  
  function ActualizarControles(){
	var cmbSentencia = ComboValorSeleccionado('cmbSentencia');	
	var demandaObligatorio = listaMontoDemandado[cmbSentencia]; 	
	if(demandaObligatorio == 'S'){		
		SetfontWeight('txtMontoCondenaSentencia', "900");		
		SetfontWeight('idtxtPorcentajeIncapacidad', "900");
	}else{
		SetfontWeight('txtMontoCondenaSentencia', "normal");		
		SetfontWeight('idtxtPorcentajeIncapacidad', "normal");
	}
	
	LimpiarErrores();
  }
  
  function SetfontWeight(idControl, fontweight){
	   if(document.getElementById(idControl))
		document.getElementById(idControl).style.fontWeight=fontweight;		
  }
  
  function LimpiarFechaError(){
	$("#ErrorcmbFecha").empty();	
  }
  
  function CallSalvarSentencia() { 
	if( !ValidarSentenciaWebForm() ){return false;}
	
	//UpdateSentencia(
	var txtfechasentencia = $('#idtxtFecha').val().trim();	
	var txtfecharecep = $('#idtxtFechaRecep').val().trim();	
	
	//var jt_sentencia = $('#txtDetalleSentencia').html();		
	var jt_sentencia = TextAreaText('txtDetalleSentencia');	

	//var cmbSentencia = $('#cmbSentencia').val();	
	var cmbSentencia = ComboValorSeleccionado('cmbSentencia');	
	
	var txtimportehonorarios = $('#idtxtImporteHonorarios').val();	
	if(isNaN(txtimportehonorarios) ) txtimportehonorarios = 0;
	
	var txtimporteintereses = $('#idtxtimporteintereses').val();	
	if(isNaN(txtimporteintereses) ) txtimporteintereses = 0;
	
	var txtimportetasajusticia = $('#idtxtimportetasajusticia').val();	
	if(isNaN(txtimportetasajusticia) ) txtimportetasajusticia = 0;
	//idtxtPorcentajeIncapacidad
	//txtPorcentajeIncapacidad
	
	//var instancia 
	var txtMontoCondena = $('#txtMontoCondenaSentencia').val().trim();	
	//var txtPorcentajeIncapacidad = $('#idtxtPorcentajeIncapacidad').val().trim();	
	var txtPorcentajeIncapacidad = ValorElementoID('idtxtPorcentajeIncapacidad');	
		
	var Accion = 'EDIT';
	
	if ( SalvarSentencia(Accion, txtfechasentencia, txtfecharecep, jt_sentencia, 
			cmbSentencia,  usuario, jt_id, txtimportehonorarios, 
			txtimporteintereses, txtimportetasajusticia, instancia, 
			txtMontoCondena, txtPorcentajeIncapacidad) == true){		
		if(Accion == 'EDIT')
			MostrarVentana('Sentencia modificada correctamente.');			
		
		$("#idbtnCancelarVentana").click( function(){return false;} );
		$("#idbtnAceptarVentana").click( function(){ redirectpage(); });				
	}
		return false;				
  }
  
  function redirectpage(){
		window.location.href = '/AdminWebForm'; 							
		return true;
  }
  
  function abandonaPagina() { 
	//Esta funcion se ejecuta al abandonar la pagina
	var x = $('#lblErrores');
	x.html('No me abandones').show("slow");	
 }
  
  function LimpiarErrores(){
  	$("#lblErrores").empty();
  	$("#ErrorestxtMonto").empty();
	$("#ErrorestxtFecha").empty();
	$("#ErrorcmbFecha").empty();	
	$("#ErrorcmbFechaNotificacion").empty();
	$("#ErrorestxtPorcentajeIncapacidad").empty();	
  }
  
  function  ValidarSentenciaWebForm(){	
	var errorescount = 0;	
	LimpiarErrores();
  //------------------------------------------------------------------------
	errorescount += MostrarError(IsNullZero, $("#idtxtFecha"), $("#ErrorcmbFecha"), "Debe completar Fecha Sentencia.");	
	errorescount += ValidarControlFecha('idtxtFecha', 'ErrorcmbFecha');		
	errorescount += ValidarControlFecha('idtxtFechaRecep', 'ErrorcmbFechaNotificacion');		
	//------------------------------------------------------------------------
	if($("#ErrorcmbFecha").text().trim() == '' && $("#ErrorcmbFechaNotificacion").text().trim() == '' ){
	
		var txtFecha = $('#idtxtFecha').val();
		var txtFechaRecep = $("#idtxtFechaRecep").val().trim();	
		var FechaHoy = GetFechaHoy();
			
		errorescount += MostrarError(IsNullZero, $("#cmbSentencia"), $("#ErrorcmbSentencia"), "Debe seleccionar una sentencia.");	
		//Esta validacion no va ....
		//errorescount += MostrarError(IsNullZero, $("#idtxtFechaRecep"), $("#ErrorcmbFechaNotificacion"), "Debe completar Fecha Notificación.");	
		errorescount += MostrarError(IsNullZero, $("#txtDetalleSentencia"), $("#ErroresDetalle"), "El detalle de la sentencia no debe estar vacio.");		
		
		var parseFechaHoy = ParsearFecha(FechaHoy);
		var parsetxtFecha = ParsearFecha(txtFecha);
		var parsetxtFechaRecep = ParsearFecha(txtFechaRecep);
			
		/*------------------------------------------------------*/
		if (parseFechaHoy < parsetxtFecha ) {
			txtError = 'La Fecha de Sentencia no puede ser mayor que hoy.';
			$("#ErrorcmbFecha").text(txtError);
			errorescount += 1;		
		}else{	
			if(txtFechaRecep != ''){
				if (parsetxtFecha > parsetxtFechaRecep) {			
					txtError = 'La Fecha de notificacion debe ser mayor que la Fecha de Sentencia';
					MostrarError('', '', $("#ErrorcmbFechaNotificacion"), txtError);	
					errorescount += 1;
				}		
			}
		}	
	}
	/*------------------------------------------------------*/
	var cmbSentencia = $("#cmbSentencia").val();
	var demandaObligatorio = listaMontoDemandado[cmbSentencia]; 	
	var txtPorcentajeIncapacidad = $("#idtxtPorcentajeIncapacidad").val();
		
	var txtPorcentajeIncapacidadVisible = IncapacidadVisible;
		
	var txtMontoCondenaSentencia = $("#txtMontoCondenaSentencia").val();
	
	errorescount += ValidarMonto('#txtMontoCondenaSentencia','#ErrorestxtMonto');
	
	/*EsFederal == '0'  es cuando NO ES FEDERAL VER MAIL DE JUANPA (RE: despejando dudas de cuando EsFedera  = True)*/
	
	if (EsFederal == '0' && demandaObligatorio == 'S'){
		
		//Esto es simpre falso en la vesion Delphi web existe esta validacion
		if (IncapacidadVisible == 'S'){
			var percentSent = ValFloatRedondeaArriba(txtPorcentajeIncapacidad);
			if (percentSent <= 0 ){
				txtError = 'El Porcentaje de Incapacidad debe ser mayor a 0.';
				$("#ErrorestxtPorcentajeIncapacidad").html(txtError);
				errorescount += 1;
			}
		}
				
		if( $("#ErrorestxtMonto").html() == ''){
			var montoSent = ValFloatRedondeaArriba(txtMontoCondenaSentencia);
			if ( montoSent <= 0 ){
				txtError = 'El Monto de la condena de la sentencia debe ser mayor a 0';
				$("#ErrorestxtMonto").html(txtError);
				errorescount += 1;
			}
		}
	}
		
	//El monto no se valida.........(esta asi en la version delphi web)
	//errorescount += MostrarError(IsNullZero, $("#txtMontoCondenaSentencia"), $("#ErrorestxtMonto"), "Debe completar monto condena.");	
			
	if(errorescount > 0){
		var x=$("#lblErrores");
		x.html("Errores ("+errorescount+")").show("fast");
		return false;
	}
	else{
		var x=$("#lblErrores");
		x.empty().hide("slow");
		return true;
	}  
	
  }
  
  function CompletarFechaAsignacion(){  	
	$('#txtFecha').val(FechaHoy()).select();
  }  
    
  function CompletarFechaRecep(){  	
	$('#txtFechaRecep').val(FechaHoy()).select();
  }  

  function NoValidar(){		
	document.getElementById("idSentenciaWebForm").onsubmit=function(){return true;};	
  }
  
  function Validar(){		
	document.getElementById("idSentenciaWebForm").onsubmit=ValidarSentenciaWebForm;	
  }
		
//----------------------------------------------------------	
function muestraDialogMensaje(){
	iniDialogMensaje(2);
	document.getElementById('ui-id-1').innerHTML = 'Sentencia';
	document.getElementById('dialogTitulo').innerHTML = 'Sentencia Modificada: ';
	document.getElementById('dialogInfoTitulo').innerHTML = 'La sentencia fue actualizada.';
	$( "#dialogMensaje" ).dialog('open');
}

function muestraDialogMensajeError(){
	iniDialogMensaje(1);
	document.getElementById('ui-id-1').innerHTML = 'Sentencia';
	document.getElementById('dialogTitulo').innerHTML = 'Error: ';
	document.getElementById('dialogInfoTitulo').innerHTML = 'No se pudo actualizar la Sentencia .. intente nuevamente.';
	$( "#dialogMensaje" ).dialog('open');

}

function iniDialogMensaje(optBotones){
	
	var btnAceptar = {id: "btnAceptarMsj", 
						text: "", 
						click: function() { $( this ).dialog( "close" ); 
											return false; } };
							
	var btnSiguiente = {id: "btnAceptarMsj", 
						text: "", 
						click: function() { $( this ).dialog( "close" ); 
											redirectpage();
											return true; } };
							
	var btnCancelar = {	id: "btnCancelarMsj", 
						text: "", 
						click: function() { $( this ).dialog( "close" ); 
											 
											 return false; } };	
											
	if(optBotones == 1)		botones = [btnAceptar];
	if(optBotones == 2)		botones = [btnSiguiente];
	if(optBotones == 3)		botones = [btnSiguiente, btnCancelar];
								
		
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
	
	
