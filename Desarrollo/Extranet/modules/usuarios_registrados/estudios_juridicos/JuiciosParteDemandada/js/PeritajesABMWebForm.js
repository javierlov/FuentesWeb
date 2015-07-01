var x;
x=$(document);
x.ready(inicio);

var ValidaPeritoCompleto = true;

  function inicio(){    
	
	$("#dialogMensaje").dialog({autoOpen: false});		
	
	ValidaPeritoCompleto = true;
	if(document.getElementById("guardarSubmit"))
		document.getElementById("guardarSubmit").value = 'NO';
	
	$("#cmbTipoPericia").change(TipoPericiaControles);		
	
	$("#idbtnPeritoNuevo").click(AbrirPeritoNuevo);
	$("#idbtnPeritoNuevo1").click(AbrirPeritoNuevo);	
	$("#idAceptarAjax").click(ValidarPeritoCompleto);

	$("#AceptarFuncSubmit").click(CallSubmit);
	$("#idbtnPeritoEditar").click(EditarPerito);
	$("#idbtnPeritoEditar1").click(EditarPerito);

	//Se asignan los eventos calendario a los controles
	SetearControlFecha("txtFechaAsignacion", "btnFechaAsignacion");	
	SetearControlFecha("txtFechaPericia", "btnFechaPericia");	
	SetearControlFecha("txtFVencImpugnacion", "btnFVencImpugnacion");	
	
	//$("#idbtnBuscarPeritoAjax").click(BuscarPeritoSeleccion);
	$("#idbtnBuscarPeritoAjax").click(BuscarPeritoCUILSeleccion);
	$("#idbtnBuscarPeritoCUILAjax").click(BuscarPeritoCUILSeleccion);
	
	$("#idchkBuscarpor0").click(MuestraOcultaBuscar);
	$("#idchkBuscarpor1").click(MuestraOcultaBuscar);
			
	OcultarControles();
	
	if(MuestraRedirect) 
		MuestraRedirectSubmit();
	else if(MuestraFallo) 
		MuestraFalloSubmit();
	
  }  
  
//----------------------------------------------------------	
 function MuestraOcultaBuscar(){
	$("#lblErrores").empty();
	$("#ErroresBuscarPor").empty();
	$("#ErrorescmbTipoPericia").empty();
	
	document.getElementById('idbuscapornombre').style.display = 'none';
	document.getElementById('idbuscacuil').style.display = 'none';	
	 
	if(document.getElementById('idchkBuscarpor0').checked ) { 
		document.getElementById('idbuscacuil').style.display = 'block';			
		document.getElementById("txtBuscarPeritoApellido").value = '';
		document.getElementById("txtBuscarPeritoNombre").value = '';
		return true;
	}else{
		if(document.getElementById('idchkBuscarpor1').checked ) { 	
			document.getElementById('idbuscapornombre').style.display = 'block';
			document.getElementById("txtcuil1").value = '';
			document.getElementById("txtcuil2").value = '';
			document.getElementById("txtcuil3").value = '';		
			return true;
		}		
	}	
	return true;
 }
 
 function ValidarApellidoNombreVacio(){
	$("#ErroresBuscarPor").empty();
	$("#ErrorescmbTipoPericia").empty();
	
	if(document.getElementById("txtBuscarPeritoApellido").value == '' && document.getElementById("txtBuscarPeritoNombre").value == ''){
		var textoErrorComplete = 'Complete Apellido y/o Nombre para buscar';			
		MostrarError('', '', $("#ErroresBuscarPor"), textoErrorComplete);								
		return false;
	}
	return true;
 }

 function ValidarApellidoNombreCantCaract(){
	if(document.getElementById("txtBuscarPeritoApellido").value != '' || document.getElementById("txtBuscarPeritoNombre").value != ''){
		$("#ErroresBuscarPor").empty();
		var valido = true;
		
		var apellidocont = document.getElementById("txtBuscarPeritoApellido").value;		
		if(apellidocont != '')
			if( apellidocont.length < 3)
				valido = false;
		
		var nombrecont = document.getElementById("txtBuscarPeritoNombre").value;
		if(nombrecont != '')
			if(nombrecont.length < 3)
				valido = false;
		
		if(!valido){
			var textoErrorComplete = 'Apellido y/o Nombre deben ingresar al menos 3 caractes';			
			MostrarError('', '', $("#ErroresBuscarPor"), textoErrorComplete);								
			return false;
		}
	}
	return true;
 }
  
 function ValidarTipoPericiaVacio(){	
	$("#ErrorescmbTipoPericia").empty();
	if(MostrarError(IsNullZero, $("#cmbTipoPericia"), $("#ErrorescmbTipoPericia"), "Debe seleccionar Tipo Pericia.") == 1)
		return false;
	return true;
 }
 
 function FuncionProcesarItems(){		
	
	$("#listadoApellido").click(ItemSeleccionadoSelect);
	$("#listadoApellido").focus();	
	$("#listadoApellido").blur(PerdioelFoco);	
	$("#listadoApellido").keypress( function(event){ 
									if ( event.which == 13 ){ 
										event.preventDefault(); 
										ItemSeleccionadoSelect(); 
									} } );	
										
	return true;
}

function ItemSeleccionadoSelect(){	

	if(document.getElementById('idchkBuscarpor0').checked ){ 
		document.getElementById('listaitemsApellidoNombre').style.display = 'none';	
		
		var strtext = ComboValorTexto("listadoApellido");
		//var total = strtext.length; 
		//var inicio = strtext.search("-");
		//var resstrtext = strtext.substr(inicio+2, total);
		var resstrtext = strtext;
				
		var valortipoperito = BuscaAttrSelectItem('listadoApellido', 'tipoperito');			
		var idPerito = ComboValorSeleccionado("cmbTipoPericia");		
		
		if(valortipoperito == idPerito){
			resstrtext = resstrtext.toUpperCase();
			document.getElementById("ResultadoBuscarPor").innerHTML = resstrtext;			
		}
		else{
			document.getElementById("ResultadoBuscarPor").innerHTML = '';
			document.getElementById("ErroresBuscarPor").innerHTML = 'Tipo de perito invalido para esta pericia.';
			return false;
		}
		
	}
	
	if(document.getElementById('idchkBuscarpor1').checked ){ 	
		document.getElementById('listaitemsApellidoNombre').style.display = 'none';	

		//NO LE QUITA MAS EL CUIT AL TEXTO PARA MOSTRAR
		//var resstrtext = RetornaSubString("listadoApellido", "-");		
		var resstrtext = ComboValorTexto("listadoApellido");		
		document.getElementById("ResultadoBuscarPor").innerHTML = resstrtext;
			
				//NO LIMPIO LOS CONTROLES DESPUES DE SELECCINAR
		//document.getElementById("txtBuscarPeritoApellido").value = '';
		//document.getElementById("txtBuscarPeritoNombre").value = '';
	}
	
	document.getElementById("ErroresBuscarPor").innerHTML = '';
	document.getElementById("ResultadoIdPerito").value = ComboValorSeleccionado("listadoApellido");	
	
	
	var LIST_APELLIDO = BuscaAttrSelectItem('listadoApellido', 'APELLIDO');
	var LIST_NOMBRE = BuscaAttrSelectItem('listadoApellido', 'NOMBRE');
	PE_APELLIDO = LIST_APELLIDO; 			
	PE_NOMBREINDIVIDUAL = LIST_NOMBRE; 			
			
}

function ValidarDatosPerito(){	
	var valorresult =  AvisoDatosPerito();
	
	if( (ValidaPeritoCompleto) && (valorresult != '') ){
		var titulo = 'Peritaje'; 
		var subtitulo = 'Datos incompletos ';
		var mensaje = valorresult;
		
		MuestraMensajePerito(titulo, subtitulo, mensaje);		
		return false;
	}
	
	if( !ValidarPeritajesABMWebForm() ){
		return false;
	}	
	return true;	
}

function RetornaSubString(idControlText, CaracterInicio){
	/*recoracta un string desde el primer caracter CaracterInicio
		retorna el resto del string en mayuscula*/
	var strtext = ComboValorTexto(idControlText);
	var inicio = strtext.search(CaracterInicio);
	var total = strtext.length; 
	var resstrtext = strtext.substr(inicio+2, total);
	//convierte a mayuscula
	resstrtext = resstrtext.toUpperCase();
	return resstrtext;

}

function PerdioelFoco(){
	document.getElementById('listaitemsApellidoNombre').style.display = 'none';		
	
	var itemseleccion = ComboValorTexto("listadoApellido");	
	if(itemseleccion == '')	ItemSeleccionadoSelect();
}

 function GetParametrosfuncion(){
	var strparametros = "FUNCION=BuscarPeritosListado";		
	var idPerito = ComboValorSeleccionado("cmbTipoPericia");		
	
	if(document.getElementById('idchkBuscarpor1').checked ) { 
		var PeritoApellido=$("#txtBuscarPeritoApellido").val();
		var PeritoNombre=$("#txtBuscarPeritoNombre").val();
		
		strparametros = strparametros+
						"&Apellido="+encodeURIComponent(PeritoApellido)+
						"&Nombre="+encodeURIComponent(PeritoNombre)+
						"&tipoPerito="+encodeURIComponent(idPerito);
	}

	if(document.getElementById('idchkBuscarpor0').checked ) { 
		var cuit = $("#txtcuil1").val();
		var cuit = cuit + $("#txtcuil2").val();
		var cuit = cuit + $("#txtcuil3").val();
		// tipoperito; variable global		
		strparametros = strparametros+"&cuit="+encodeURIComponent(cuit)+"&tipoPerito="+encodeURIComponent(idPerito);				
	}			
	return strparametros;
 }
  
 function GetPaginafunciones(){
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php";
	return pagefunciones;
 }
//----------------------------------------------------------	
  function ValidarPeritoCompleto(){
	
	var muestraPerito = ValidarDatosPerito();
		
	if(muestraPerito != ''){
		MostrarVentanaOKCancel(muestraPerito);
		//$("#idbtnCancelarOKCancel").click( OpcionSalvarDatos );	
		//$("#idbtnAceptarOKCancel").click( returnOption );			
		
		document.getElementById("idbtnCancelarOKCancel").onclick = OpcionSalvarDatos;
		document.getElementById("idbtnAceptarOKCancel").onclick = returnOption;
		
		return true;
	}else
		CallSalvarPeritajeABM();
	
	return true;
  }
  
  function returnOption(){
	OcultarVentanaOKCancel();
	EditarPerito();
	return false;
  }
  
  function OpcionSalvarDatos(){
	OcultarVentanaOKCancel();	
	
	if(CallSalvarPeritajeABM())
		return true;
	else
	   return false
  }
	  
  function CallSalvarPeritajeABM(){
		
	if( !ValidarPeritajesABMWebForm() ){
		return false;
	}			
	var txtFechaAsignacion = trimString($('#txtFechaAsignacion').val());	
	var txtFechaPericia = trimString($('#txtFechaPericia').val());	
	var txtFVencImpug = trimString($('#txtFVencImpugnacion').val());	
	var cmbPericia = trimString($('#cmbTipoPericia').val());	
	
	var txtResultados = trimString($('#txtResultados').val());				
	var incapacidadDemanda = trimString($('#idtxtIncapacidadDemandada').val());		
	var incapacidadPeritoMedico = trimString($('#idtxtIncapacidadPerMedico').val());	
	
	var ibmArt = trimString($('#idtxtIBMArt').val());	
	var ibmPericial = trimString($('#idtxtIBMPericial').val());	
	//var impugnacion = $('#chkImpugnacion_0').val().trim();
	var impugnacion = 'X';
	if(document.getElementById('chkImpugnacion_0').checked) { impugnacion = 'S'; }		
	if(document.getElementById('chkImpugnacion_1').checked) { impugnacion = 'N'; }		
	
	var codPerito = ValorElementoID("ResultadoIdPerito");
	
	if(Accion == 'ALTA')
		idperito = nrojuicio;
		
	idperito = SalvarPeritajeABM(Accion, txtFechaAsignacion, 
			txtFechaPericia,txtFVencImpug, cmbPericia, 
			txtResultados, idperito, 
			usuario, incapacidadDemanda, incapacidadPeritoMedico, 
			ibmArt, ibmPericial, impugnacion, codPerito );
			
	if (idperito > 0){
			
		if(Accion == 'EDIT'){
			iniDialogMensajePeritoNuevo('ADJ');
			document.getElementById('ui-id-1').innerHTML = 'Peritaje';
			document.getElementById('dialogTitulo').innerHTML = 'Peritaje Modificado: ';
			document.getElementById('dialogInfoTitulo').innerHTML = 'Peritaje Modificado correctamente. \n ¿Desea Adjuntar archivos?.';
			$( "#dialogMensaje" ).dialog('open');
			
			/*
			MostrarVentana('Peritaje modificado correctamente.');		
				*/				
		}
		
		if(Accion == 'ALTA'){
			iniDialogMensajePeritoNuevo('ADJ');
			document.getElementById('ui-id-1').innerHTML = 'Peritaje';
			document.getElementById('dialogTitulo').innerHTML = 'Peritaje Ingresado: ';
			document.getElementById('dialogInfoTitulo').innerHTML = 'Peritaje Ingresado correctamente. \n ¿Desea Adjuntar archivos?.';
			$( "#dialogMensaje" ).dialog('open');
			
			/*
			MostrarVentana('Peritaje ingresado correctamente.');		
			*/
		}
		
		//$("#idbtnCancelarVentana").click( redirectCancel );
		//$("#idbtnAceptarVentana").click( redirectpage );		
	}	
	return true;
  }  
  
  function redirectpage(){
	window.location.href = '/PeritajesWebForm';
	return true;	
  }
  
  function redirectpageAdjuntos(){		
	window.location.href = '/index.php?pageid=135&id='+idperito;
	// window.location.href = '/PeritajesAdjuntosWebForm/'+idperito;
	return true;	
  }
  
  function redirectCancel(){	
	return false;	
  }
//----------------------------------------------------------	
  function URLPeritoGetParametros(accionPerito){
	
	if(accionPerito == 'ALTA'){
		var urlreenviar = '/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=115&Accion=ALTA';
		return urlreenviar;
	}
	
	var valorCombo = ComboValorTexto('cmbTipoPericia');		
	var idselectCobmo = $("#cmbTipoPericia").val();
	var IdPeritoEdit = $("#ResultadoIdPerito").val();

	var FechaAsignacion = $("#txtFechaAsignacion").val();
	var FechaPericia = $("#txtFechaPericia").val();
	var FVencImpugnacion = $("#txtFVencImpugnacion").val();

	var IncapacidadDemandada = $("#idtxtIncapacidadDemandada").val();
	var IncapacidadPerMedico = $("#idtxtIncapacidadPerMedico").val();

	var IBMArt = $("#idtxtIBMArt").val();
	var IBMPericial = $("#idtxtIBMPericial").val();

	var chkImpugnacion = 'X';				
	if(document.getElementById('chkImpugnacion_0').checked) { chkImpugnacion = 'S'; }		
	if(document.getElementById('chkImpugnacion_1').checked) { chkImpugnacion = 'N'; }		

	var txtResultados = $("#txtResultados").val();

	var Apellido  = PE_APELLIDO;//ValorElementoID("txtBuscarPeritoApellido");
	var Nombre = PE_NOMBREINDIVIDUAL;//ValorElementoID("txtBuscarPeritoNombre"); 
		
	$("#ErrorescmbTipoPericia").empty();

	//pageid 108 = 115
	var urlreenviar = '/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=115';
	urlreenviar+='&Accion='+accionPerito;
	urlreenviar+='&Apellido='+Apellido;
	urlreenviar+='&Nombre='+Nombre;
	urlreenviar+='&idperito='+IdPeritoEdit;
	urlreenviar+='&cmbTipoPericia='+idselectCobmo;

	urlreenviar+='&FechaAsignacion='+FechaAsignacion;
	urlreenviar+='&FechaPericia='+FechaPericia;
	urlreenviar+='&FVencImpugnacion='+FVencImpugnacion;

	urlreenviar+='&IncapacidadDemandada='+IncapacidadDemandada;
	urlreenviar+='&IncapacidadPerMedico='+IncapacidadPerMedico;

	urlreenviar+='&IBMArt='+IBMArt;
	urlreenviar+='&IBMPericial='+IBMPericial;

	urlreenviar+='&chkImpugnacion='+chkImpugnacion;
	urlreenviar+='&txtResultados='+txtResultados;
		
	urlreenviar+='&cmbTipoPericiaValor='+valorCombo;
	urlreenviar+='&IdPeritoEdit='+IdPeritoEdit;
		
	return urlreenviar;
	
  }
  
  function EditarPerito(){
	//edita el perito seleccionado
	var errorescount = 0;
	errorescount += MostrarError(IsNullZero, $("#cmbTipoPericia"), $("#ErrorescmbTipoPericia"), "Debe seleccionar Tipo Pericia.");
	errorescount += MostrarError(IsNullZero, $("#ResultadoIdPerito"), $("#ErroresBuscarPor"), "Debe seleccionar Perito.");
	
	if(errorescount > 0){
		return false;
	}
	else{
		window.location.href =  URLPeritoGetParametros("EDIT");
		return true;
	}	
  }
  
  function AbrirPeritoNuevo(){
	var errorescount = 0;
	errorescount += MostrarError(IsNullZero, $("#cmbTipoPericia"), $("#ErrorescmbTipoPericia"), "Debe seleccionar Tipo Pericia.");

	if(errorescount > 0){
		return false;
	}
	else{
		var valorCombo = ComboValorTexto('cmbTipoPericia');		
		var idselectCobmo = $("#cmbTipoPericia").val();				
		var IdPeritoEdit = $("#ResultadoIdPerito").val();
	
		var urlreenviar = URLPeritoGetParametros("ALTA");		
		urlreenviar+='&cmbTipoPericiaValor='+valorCombo;
		urlreenviar+='&IdPeritoEdit='+IdPeritoEdit;		
		
		window.location.href = urlreenviar;
		return true;
	}	
  }
  
  function TipoPericiaControles(){
	OcultarControles();
	
	$("#idtxtIBMArt").val('0');
	$("#idtxtIBMPericial").val('0');	
	$("#idtxtIncapacidadDemandada").val('0');	
	$("#idtxtIncapacidadPerMedico").val('0');	
	
	LimpiarComboPeritosNombre();
  }
  
  function OcultarControles(){
  /*
	<option value=1> MÉDICA </option>  
	<option value=2> PSICOLÓGICA </option>  
	<option value=3> CONTABLE </option>  
	<option value=4> TÉCNICA </option>  
	<option value=5> CALIGRÁFICA </option>  
	<option value=6> ASISTENCIA SOCIAL </option>  
	<option value=7> OTRAS </option>  
*/	
	var x = $("#cmbTipoPericia");
	
	if(x.val() == 3 || x.val() == 9){
		document.getElementById('idGrupoIncapacidad').style.display = 'none';
		document.getElementById('idGrupoIBM').style.display = 'block';		
	}	
	else if(x.val() == 1 || x.val() == 2 || x.val() == 8){
		document.getElementById('idGrupoIncapacidad').style.display = 'block';
		document.getElementById('idGrupoIBM').style.display = 'none';		
	}
	else{
		document.getElementById('idGrupoIncapacidad').style.display = 'none';
		document.getElementById('idGrupoIBM').style.display = 'none';
	}	
	LimpiaTodoErroresDiv();
  }
  
  
  function presionSubmit() {
	var vtxtPeritoApellido= $("#txtPeritoApellido").val();
	var vtxtPeritoNombre=$("#txtPeritoNombre").val();
	var vcmbTipoPericia=$("#cmbTipoPericia").val();
	
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php";
	pagedatos = "?FUNCION=ObtenerPeritos";	
	pagedatos += "&Nombre="+vtxtPeritoNombre;
	pagedatos += "&Apellido="+vtxtPeritoApellido;	
	pagedatos += "&tipoPericia="+vcmbTipoPericia;	
	
	var v=$("#nro").attr("value");
	$.ajax({
		   async:true,
		   type: "POST",
		   dataType: "html",
		   contentType: "application/x-www-form-urlencoded",
		   url: pagefunciones,
		   data: pagedatos,
		   beforeSend:inicioEnvio,
		   success:llegadaDatos,
		   timeout:4000,
		   error:problemas
		 }); 
	return false;
  }
  
	function inicioEnvio(){
	  var x=$("#idcmbPeritosNombre");
	  x.html("<option selected='selected'>inico busqueda...</option>");
	}

	function llegadaDatos(datos){
	  $("#idcmbPeritosNombre").html(datos);
	}

	function problemas(){
	  var x=$("#idcmbPeritosNombre");
	  x.html("<option selected='selected'>Problemas en el server...</option>");
	}
  
  function LimpiarComboPeritosNombre(){	
	document.getElementById('ResultadoIdPerito').value = '';
	document.getElementById('ResultadoBuscarPor').innerHTML = '';
  }
  
  function LimpiaTodoErroresDiv(){
	//$(".input_textError").empty();
  }

  function ValidarPeritajesABMWebForm() {
 	
	var errorescount = 0;
	
  	$("#mostrarErrores").empty();
  	
	$("#ErroresidtxtIncapacidadDemandada").empty();
	$("#ErrorestxtIncapacidadPerMedico").empty();
	$("#ErroreschkImpugnacion").empty();
	$("#ErrorestxtFechaAsignacion").empty();
	$("#ErrorestxtResultados").empty();
	$("#ErrorestxtFechaPericia").empty();
	$("#ErrorestxtFVencImpugnacion").empty();
	//$("#ErroresBuscarPor").empty();
			
	errorescount += MostrarError(IsNullZero, $("#cmbTipoPericia"), $("#ErrorescmbTipoPericia"), "Tipo de Pericia inválida, debe seleccionar Tipo Pericia.");				
	/*------------------------------------------------------------*/	
	if( trimString($("#txtFechaAsignacion").val()) != '' ) 
		errorescount += ValidarControlFecha("txtFechaAsignacion", "ErrorestxtFechaAsignacion" );
	
	if( trimString($("#txtFechaPericia").val()) != '' ) 
		errorescount += ValidarControlFecha("txtFechaPericia", "ErrorestxtFechaPericia" );
		
	if( trimString($("#txtFVencImpugnacion").val()) != '' ) 
		errorescount += ValidarControlFecha("txtFVencImpugnacion", "ErrorestxtFVencImpugnacion" );		
	
	/*------------------------------------------------------------*/
	if( trimString($("#ErrorestxtFechaAsignacion").text()) == '' ) {
		var FechaHoy = GetFechaHoy();
		var FechaAsignacion = $('#txtFechaAsignacion').val();
		
		var parseFechaHoy = ParsearFecha(FechaHoy);
		var parseFechaAsignacion = ParsearFecha(FechaAsignacion);
	
		if (parseFechaHoy < parseFechaAsignacion) {
			txtError = 'La Fecha de Notificación no puede ser mayor que hoy.';
			errorescount += MostrarError('', '', $("#ErrorestxtFechaAsignacion"), txtError);
		}
	}
	/*------------------------------------------------------------*/
	if( trimString($("#ErrorestxtFechaPericia").text()) == '' ) {
		var FechaPericia = trimString($("#txtFechaPericia").val());
		
		var Resultado = trimString($("#txtResultados").text());
		if(Resultado == '') Resultado = trimString($("#txtResultados").val());
		
		var parseFechaPericia = ParsearFecha(FechaPericia);
	
		if(  (FechaPericia != '' &&  Resultado == '')  ){			
			ErrorRespuesta = 'Debe completar la finalización de la pericia correctamente, completando fecha de pericia y resultado de pericia.';
			$("#ErrorestxtResultados").text(ErrorRespuesta);
			errorescount += 1;						
		}
		
		if(FechaPericia != ''){ 
			var FechaAsignacion = $('#txtFechaAsignacion').val();
			var parseFechaAsignacion = ParsearFecha(FechaAsignacion);
			if ( (FechaAsignacion != '') && (parseFechaPericia < parseFechaAsignacion) ) {
				txtError = 'Fecha Pericia debe ser mayor a Fecha Notificación.';
				errorescount += MostrarError('', '', $("#ErrorestxtFechaPericia"), txtError);
			}	
		}		
	}
	
	/*
	//Se descarta esta validacion ver mail (Pericias; Montero, Melina <mmontero@provart.com.ar>; miércoles 13/08/2014 11:31)
	if(!$("#chkImpugnacion_0").is(':checked')) { 
		if(!$("#chkImpugnacion_1").is(':checked')) { 
			ErrorImpugCheck = 'Debe indicar la impugnación.';
			errorescount += MostrarError('', '', $("#ErroreschkImpugnacion"), ErrorImpugCheck);		
		}
	}
	*/	
	/*----------------------------------------------------------------*/
	var TipoPericia = $("#cmbTipoPericia");
	
	if(TipoPericia.val() == 3 || TipoPericia.val() == 9){
		errorescount += MostrarError(IsNullLessZero, $("#idtxtIBMArt"), $("#ErrorestxtIBMArt"), "Debe completar monto valido IBM Art.");		
		errorescount += ValidarMoneda("#idtxtIBMArt",'#ErrorestxtIBMArt');
		
		errorescount += MostrarError(IsNullLessZero, $("#idtxtIBMPericial"), $("#ErrorestxtIBMPericial"), "Debe completar monto valido IBM Pericial.");	
		errorescount += ValidarMoneda("#idtxtIBMPericial", "#ErrorestxtIBMPericial");
		
		errorescount += ValidarMonto('#idtxtIBMArt','#ErrorestxtIBMArt');
		errorescount += ValidarMonto('#idtxtIBMPericial','#ErrorestxtIBMPericial');
	}	
	
	if(TipoPericia.val() == 1 || TipoPericia.val() == 2 || TipoPericia.val() == 8){		
		errorescount += ValidaCamposPorcentaje("#idtxtIncapacidadDemandada", "#ErroresidtxtIncapacidadDemandada");
		errorescount += ValidaCamposPorcentaje("#idtxtIncapacidadPerMedico", "#ErrorestxtIncapacidadPerMedico");
	}		
	
	if(  trimString($("#ErroresBuscarPor").text()) == '' )
		errorescount += MostrarError(IsNullZero, $('#ResultadoIdPerito'), $("#ErroresBuscarPor"), 'Debe seleccionar un Perito.');			
	else
		errorescount += 1;

	if(errorescount > 0){
		var x=$("#lblErrores");
		x.html("Errores ("+errorescount+")").show("fast");
		return false;
		
	}
	else{
		var x=$("#lblErrores");
		x.empty();
		x.hide("slow");
		return true;
	}  	
  }
  
  function ValidaCamposPorcentaje(ControlValdida, DivErrorMsj){  
	
	var errorsum = MostrarError(IsNullLessZero, $(ControlValdida), $(DivErrorMsj), "Debe completar monto Incapacidad Demandada");
	/*							
	if(errorsum == 0)
		errorsum = ValidaSoloNumero(ControlValdida, DivErrorMsj);
	
	var valorcampo = $(ControlValdida).val();		
	
	if(errorsum == 0)		
		if (valorcampo > 100)			
			errorsum = MostrarError('', $(ControlValdida), $(DivErrorMsj), "Incapacidad Demandada debe ser menor a 100%");	
			
	if(errorsum == 0)
		if ($('#idtxtIncapacidadDemandada').val() < 0)			
			errorsum = MostrarError('', $(ControlValdida), $(DivErrorMsj), "Incapacidad Demandada incorrecta");	
	*/		
	return errorsum;
  }
  
  function ValidaSoloNumero(ControlValdida, DivErrorMsj){  
	if( trimString($(DivErrorMsj).html()) == '') {		
		var ErrorFormato = "Formato invalido";				
		var txtValida = trimString($(ControlValdida).val());		
		
		if( isNaN(txtValida) ){
			return MostrarError('','', $(DivErrorMsj), ErrorFormato);			
		}			 
			
		var txtValida = parseInt($(ControlValdida).val());		
		$(ControlValdida).val(txtValida);		
		
		if(!isNumber(txtValida)){
			return MostrarError('','', $(DivErrorMsj), ErrorFormato);						
		}			
	}	
	return 0;
  }

function NoSeEncontroResult(){	
	var textoErrorComplete = 'No se encontraron resultados';			
	MostrarError('', '', $("#ErroresBuscarPor"), textoErrorComplete);								
		
	document.getElementById('listaitemsApellidoNombre').innerHTML = '';
	document.getElementById("ResultadoBuscarPor").innerHTML = '';
	document.getElementById("ResultadoIdPerito").value = 0;
	/////document.getElementById("ErroresBuscarPor").innerHTML = '';
	return false;
}  

function GetParametrosDatosPerito(){
	var strparametros = "FUNCION=BuscarPeritosListado";		
	var idPerito = ValorElementoID("ResultadoIdPerito");
	
	strparametros = strparametros+
				"&idPerito="+encodeURIComponent(idPerito);

	return strparametros;
 }
//--------------------------------------------------------------------
function RecorrerElementosCUIL(arrjson, IDdivLista){
	var datos = JSON.parse(arrjson);
	var result  = '';
	//oculta la lista de resultados
	
	if(datos.length == 0){	
		NoSeEncontroResult();
		return false;
	}
	
	
	for (var i=0; i < datos.length; i++){	
		result  = result + '<option value="'+datos[i].id+'"  tipoperito="'+datos[i].idtipoperito+'" ';
		result 	= result + ' APELLIDO="'+datos[i].apellido+'" ';
		result 	= result + ' NOMBRE="'+datos[i].nombreindividual+'" >';
		result  = result + datos[i].cuit + " - " + datos[i].apellido + " " + datos[i].nombreindividual;		
		result  = result + '</option>';
	}
	var size = 8; 
	if( i < 7) size = i+1;
		
	if( result != '')
		result  = '<select onclick="ItemSeleccionadoSelect();" id="listadoApellido" size="'+size+' width=100% " ><option value="0"></option>'+result+'</select>';
		
	//hago visible el div donde estan los datos
	document.getElementById(IDdivLista).style.display = 'block';
	//asigno el select a el div que lo muestra...
	document.getElementById(IDdivLista).innerHTML = result;
	return true;
} 
//--------------------------------------------------------------------
function BuscarPeritoCUILSeleccion(){
	var buscarporcuit = false;
	
	if(document.getElementById('idchkBuscarpor0').checked ){ 
		 buscarporcuit = true;
		if(document.getElementById("txtcuil1").value == '') buscarporcuit = false;			
		if(document.getElementById("txtcuil2").value == '') buscarporcuit = false;			
		if(document.getElementById("txtcuil3").value == '') buscarporcuit = false;
		
		if(!buscarporcuit){
			alert('Complete los tres campos del Cuit/Cuil para buscar.');
			return false;
		}
	}
	
	if(document.getElementById('idchkBuscarpor1').checked ){ 
		buscarporcuit = true;		
		if(ValidarApellidoNombreVacio() == false){					
			ValidarTipoPericiaVacio();
			buscarporcuit = false;					
			return false;
		}		
		//ErrorescmbTipoPericia				
		if( ValidarTipoPericiaVacio() == false ){			
			buscarporcuit = false;					
			return false;
		}		
		if( !ValidarApellidoNombreCantCaract() ) 
			return false;	
	}
	
	if(buscarporcuit == true){
			var IDdivresult = 'listaitemsApellidoNombre';		
			var Apellido  = ValorElementoID("txtBuscarPeritoApellido");
			var Nombre = ValorElementoID("txtBuscarPeritoNombre"); 
				
			var resultados = ObtenerPeritosListadoNombre(
				GetPaginafunciones(), 
				GetParametrosfuncion(), 
				"listaitemsApellidoNombre");
				
			 //if(RecorrerElementosNombre(resultados, IDdivresult))
			 if(RecorrerElementosCUIL(resultados, IDdivresult))
					FuncionProcesarItems();	
	}	
}
//--------------------------------------------------------------------
function AvisoDatosPerito(){
	var idPerito = ValorElementoID("ResultadoIdPerito");
	var masdatos = '';
	
	if(idPerito == 0) {
		masdatos  = 'Perito incompleto: <b>Debe seleccionar un perito</b>';
		return masdatos;
	}
	
	var resultados = ObtenerPeritosListadoNombre(
		GetPaginafunciones(), 
		GetParametrosDatosPerito(), "listaitemsApellidoNombre");
	
	document.getElementById("listaitemsApellidoNombre").innerHTML = '';
	
	var datos = JSON.parse(resultados);
	var result  = 'No se encontraron Resultados';
	
	if(datos.length == 0){			
		return result;
	}
	
	for (var i=0; i < datos.length; i++){					
		if(datos[i].cuit == '') masdatos = ' Cuit/Cuil'; 		
		if(datos[i].direccion == '') {
			if(masdatos != '') masdatos += ", ";
			masdatos += ' Direccion. '; 
		}
	}		
	if(masdatos > ''){
		masdatos  = 'Perito incompleto: <b>'+masdatos+'</b><p>¿Desea compleatar el perito ahora?';
		return masdatos;
	}
	
	return masdatos;
}
//--------------------------------------------------------------------------------------
  function MostrarVentanaResultadoOK(mensajeResultado, resultadoEstado){
	/* Muestra la ventana de mensaje */	
	if(!mensajeResultado) var mensajeResultado = 'vacio';
		MostrarVentanaResultado(mensajeResultado);
		
	$("#idbtnAceptarVentanaResultado").click( function(){ redirectpage(); }  );	
  }
  
  function MuestraRedirectSubmit(){	  
		iniDialogMensajePeritoNuevo('ADJ');
		document.getElementById('ui-id-1').innerHTML = 'Peritaje';
		document.getElementById('dialogTitulo').innerHTML = 'Peritaje: ';		
		
		if(Accion == 'EDIT')
			document.getElementById('dialogInfoTitulo').innerHTML = 'Peritaje Modificado correctamente. \n ¿Desea Adjuntar archivos?.';
		if(Accion == 'ALTA')
			document.getElementById('dialogInfoTitulo').innerHTML = 'Peritaje Ingresado correctamente. \n ¿Desea Adjuntar archivos?.';
		
		$('#dialogMensaje').dialog('open');
  }
  
  function MuestraFalloSubmit(){	  
		iniDialogMensajePeritoNuevo('PER');
		document.getElementById('ui-id-1').innerHTML = 'Peritaje';
		document.getElementById('dialogTitulo').innerHTML = 'Peritaje: ';		
		document.getElementById('dialogInfoTitulo').innerHTML = 'Fallo actualizando datos.';
		$('#dialogMensaje').dialog('open');
  }
  
  function MuestraMensajePerito(titulo, subtitulo, mensaje){	  
		iniDialogMensajePeritoNuevo('PER');
		document.getElementById('ui-id-1').innerHTML = titulo;
		document.getElementById('dialogTitulo').innerHTML = subtitulo;		
		document.getElementById('dialogInfoTitulo').innerHTML = mensaje;
		$('#dialogMensaje').dialog('open');
		return true;
  }
  
  function iniDialogMensajePeritoNuevo(opcBotones){
	
	var btnAceptar = {id: "btnAceptarMsj", 
						text: "", 
						click: function() { $( this ).dialog( "close" ); 
											if( !idperito ) 
												idperito = 0; 
											
											if( idperito == 0 )
												AbrirPeritoNuevo();
											else
												EditarPerito();
											
											return true; } };
							
	var btnCancelar = {	id: "btnCancelarMsj", 
						text: "", 
						click: function() { $( this ).dialog( "close" ); 											
											 ValidaPeritoCompleto = false;
											 document.getElementById("guardarSubmit").value = 'GUARDAR';
											 document.getElementById("idPeritajesABMWebForm").submit();											 
											 return true; 
											 } };	
											
	var btnbtnAceptarA = {id: "btnAceptarMsj", 
						text: "", 
						click: function() { $( this ).dialog( "close" ); 
											redirectpageAdjuntos();
											return true; } };
							
	var btnCancelarA = {	id: "btnCancelarMsj", 
						text: "", 
						click: function() { $( this ).dialog( "close" ); 
											 redirectpage();
											 return false; } };	
											
	if(opcBotones == 'PER')
		botones = [btnAceptar, btnCancelar];
		
	if(opcBotones == 'ADJ')
		botones = [btnbtnAceptarA, btnCancelarA];
		
	$( "#dialogMensaje" ).dialog({						
			position:{my: "center top",  at: "center top",  of: "#divContent"},
			autoOpen:false,
			modal: true,			
			// show:"scale",
			// show:"clip",
			buttons:botones	
	});
	
	if( document.getElementById('btnAceptarMsj'))	JQAsignaClaseCSS("#btnAceptarMsj", "btnAceptar btnHover");		
	if( document.getElementById('btnCancelarMsj'))	JQAsignaClaseCSS("#btnCancelarMsj", "btnCancelarEJ btnHover");	
  }

  function CallSubmit(){
	if(document.getElementById("guardarSubmit"))
		document.getElementById("guardarSubmit").value = 'NO';	
	
	if( ValidarDatosPerito() ){
		// document.getElementById("idPeritajesABMWebForm").submit();
			document.getElementById("guardarSubmit").value = 'GUARDAR';	
			document.getElementById("idPeritajesABMWebForm").submit();
	  }
  }
  