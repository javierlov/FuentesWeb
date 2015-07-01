var x;
x=$(document);
x.ready(inicio);

  function inicio(){ 
	
	$("#dialogMensaje").dialog({autoOpen: false});
	
	$("#idAceptarAjax").click(CallSalvarPeritoABM);	
	
	idperito = GetIdPerito();		
	$("#btnCancelar").click( function(){ RedirectPage(tipoperito, idperito, "CANCEL"); });
	$("#idbtnVolver").click( function(){ RedirectPage(tipoperito, idperito, "CANCEL"); });
	
	//$("#txtcuil3").keyup(AutoSelect);		
	$("#txtcuil3").keypress(function (event) {
								if (event.which == 13 || event.keyCode == 13) {       
									AutoSelect();
									return false;
								}
								return true;
							});	
	$("#txtcuil1").change(ModifTituloPerito);
	$("#txtcuil2").change(ModifTituloPerito);
	$("#txtcuil3").change(ModifTituloPerito);	
	
	if(Accion == 'EDIT')
		SetearEstadoAccion(false);
	if(Accion == 'ALTA')
		SetearEstadoAccion(true);
  }
  
  function ModifTituloPerito(){
	//ahora se puede modificar el cuit/cuil del perito
	//SetearEstadoAccion( document.getElementById("txtcuil3").value == "" );	
	AutoSelect();
	return true;
  }
  
  function SetearEstadoAccion(insert){
	if(insert){	
		AsignTextATd("TituloPerito", "Agregar Perito");
		Accion = 'ALTA';		
	}
	else{
		AsignTextATd("TituloPerito", "Editar Perito");
		Accion = 'EDIT';		
	}
  }
  
  function AutoSelect(){	
	
	LimpiarErrorMsj();
	
	if($("#txtcuil1").val().trim() == '') {return false;}  		
	if($("#txtcuil2").val().trim() == '') {return false;}  		
	if($("#txtcuil3").val().trim() == '') {return false;}  		
	
	if($("#txtcuil3").val().trim() != ''){  				
		var valor = ObtenerPeritosListadoNombre(GetPaginafunciones(), GetParametrosfuncion(), 'listaitems');				
		if(RecorrerElementosNombre(valor, 'listaitems'))
			FuncionProcesarItems(valor);	
	}
	
  }
  
function ItemSeleccionadoSelect(valor){	
	
	var idTipoPericia = tipoperito;		
	SetIdPerito( ComboValorSeleccionado("listadoApellido") );					
	
	if (RetornaDatosPerito(valor, idperito, idTipoPericia)) {	
		
		if($("#txtcuil3").val().trim() != '') {  
			SetIdPerito( ComboValorSeleccionado("listadoApellido") );					
			Accion = 'EDIT';				
			var IDdivresult = 'listaitems';	
			//si
			SetearEstadoAccion( document.getElementById("txtcuil3").value == "" );
						
			return true;
		}
	}
	return false;	
}
//--------------------------------------------------------------------
function RecorrerElementosNombre(arrjson, IDdivLista){
	var datos = JSON.parse(arrjson);
	var result  = '';
	//oculta la lista de resultados
	
	if(datos.length == 0){			
		SetearEstadoAccion(true);
		
		document.getElementById('listaitems').innerHTML = '';
		
		if(document.getElementById("ResultadoBuscarPor"))
			document.getElementById("ResultadoBuscarPor").innerHTML = '';
			
		if(document.getElementById("ResultadoIdPerito"))
			document.getElementById("ResultadoIdPerito").value = 0;
			
		//limpiarControles();
		return false;
	}
	
	
	for (var i=0; i < datos.length; i++){	
		result  = result + '<option value="'+datos[i].id+'" >';
		result  = result + datos[i].apellido + " " + datos[i].nombreindividual + " " + datos[i].id;		
		result  = result + '</option>';
	}
	var size = 8; 
	if( i < 7) size = i+1;
		
	if( result != '')
		result  = '<select id="listadoApellido" size="'+size+' width=100% "><option value="0"></option>'+result+'</select>';
		
	//limpiarControles();
	//hago visible el div donde estan los datos
	document.getElementById(IDdivLista).style.display = 'block';
	//asigno el select a el div que lo muestra...
	document.getElementById("listaitems").innerHTML = result;
	return true;
}   

function limpiarControles(){
	document.getElementById("txtApellido").value = "";
	document.getElementById("txtNombre").value = "";
	document.getElementById("txtDireccion").value = "";
	document.getElementById("txtEMail").value = "";
	document.getElementById("txtTelefono").value = "";
	//document.getElementById("cmbDesignacion").selectedIndex  = 0;			
}


function RetornaDatosPerito(arrjson, idPerito, idTipoPericia){
/*
	Esta funcion recorre una estructura con notacion json
	y destibuye los elementos en los controles de pantalla perito
*/
	var datos = JSON.parse(arrjson);
	var result  = '';
	
	document.getElementById('listaitems').style.display = 'none';			
	//limpio los controles
	limpiarControles();	

	for (var i=0; i < datos.length; i++){	
		if(datos[i].id == idPerito){		
			
			if(idTipoPericia == datos[i].idtipoperito){
				document.getElementById("txtApellido").value = datos[i].apellido;
				document.getElementById("txtNombre").value = datos[i].nombreindividual;
				document.getElementById("txtDireccion").value = datos[i].direccion;
				document.getElementById("txtEMail").value = datos[i].email;
				document.getElementById("txtTelefono").value = datos[i].telefono;						
				SelecOptionCombo("cmbDesignacion", datos[i].parteoficio);
				return true;				
			}
			else{
				alert("Tipo de perito no es valido para esta pericia. \n Revise los datos.");
				SetIdPerito(0);
				document.getElementById("txtcuil1").value = "";
				document.getElementById("txtcuil2").value = "";
				document.getElementById("txtcuil3").value = "";
				return false;
			}
			
		}		
	}
	return false;		
}
//---------------------------------------------------------------------------------  
  
  function GetParametrosfuncion(){
		var cuit = $("#txtcuil1").val();
		var cuit = cuit + $("#txtcuil2").val();
		var cuit = cuit + $("#txtcuil3").val();
		// tipoperito; variable global
		
		var strparametros = "FUNCION=BuscarPeritosListado"+
					"&cuit="+encodeURIComponent(cuit)+					
					"&tipoPerito="+encodeURIComponent(tipoperito);				
					
		return strparametros;
  }
  
  function GetPaginafunciones(){
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php";
	return pagefunciones;
  }
  
  function SetIdPerito(valor){
	document.getElementById("idperito").value = valor;
	idperito = valor;
  }
  
  function GetIdPerito(){
	var id_perito = 0;
	//if(Accion == 'EDIT'){
		id_perito = ValorElementoID("idperito");
		if(id_perito == 0)
			id_perito = ComboValorSeleccionado("listadoApellido");					
	//}
	
	return id_perito;
  }
  
  function CallSalvarPeritoABM(){  	
	if(!ValidarPeritoWebForm()){return false;}
	
	var nombre = ValorElementoID('txtNombre'); 
	var apellido = ValorElementoID('txtApellido');
	var cuil = ValorElementoID('txtcuil1') + ValorElementoID('txtcuil2') + ValorElementoID('txtcuil3'); 
	var parteoficio = ComboValorSeleccionado('cmbDesignacion'); 
	var direccion = ValorElementoID('txtDireccion'); 	
	var email = ValorElementoID('txtEMail'); 	
	var telefono = ValorElementoID('txtTelefono'); 	
	
	if(Accion == 'EDIT'){
		idperito = GetIdPerito();		
	}
	if(Accion == 'ALTA')
		idperito = 0;
	
	var Resp_idperito =  SalvarPeritoABM(Accion, nombre, apellido, cuil, tipoperito, parteoficio, usuario, direccion, email, telefono, idperito);
	var mensajeperito = '';

	if ( Resp_idperito == true || Resp_idperito > 0){		
				
		if(Accion == 'EDIT') {
			//MostrarVentana('Perito actualizado correctamente.');
			mensajeperito = 'Perito actualizado correctamente.';
		}			
			
		if(Accion == 'ALTA') {
			//MostrarVentana('Perito ingresado correctamente.');
			mensajeperito = 'Perito ingresado correctamente.';
		}

		//-------------------------------------------------
		iniDialogMensaje(tipoperito, Resp_idperito);
		document.getElementById('ui-id-1').innerHTML = 'Perito';
		document.getElementById('dialogTitulo').innerHTML = 'Perito ' + apellido + '  ' + nombre + ' : ';
		document.getElementById('dialogInfoTitulo').innerHTML = mensajeperito;
		$( "#dialogMensaje" ).dialog('open');
			
		
	}
	
	return false;
  }
  
  function RedirectPage(tipoperito, idperito, OpcBoton){
				
		var nombre = ValorElementoID('txtNombre'); 
		var apellido = ValorElementoID('txtApellido');
		var htxtcuil = ValorElementoID('txtcuil1')+ValorElementoID('txtcuil2')+ValorElementoID('txtcuil3');
		
		nombre = nombre.toUpperCase();
		apellido = apellido.toUpperCase();
		if(idperito == true) idperito = GetIdPerito();		
		
		if(OpcBoton == 'CANCEL'){
			//pageid 105 = 112
			var urlreenviar = '/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=112&idperito='+idperito+
							'&cmbTipoPericia='+tipoperito+
							'&RedirectPageAnt='+RedirectPageAnt+
							'&htxtcuil='+htxtcuil;
		}
			
		if(OpcBoton == 'ACEPTAR'){
			var cuit = $('#txtcuil1').val() + $('#txtcuil2').val() + $('#txtcuil3').val();
			//pageid 105 = 112
			var urlreenviar = '/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=112&Cuit='+cuit+
								'&Apellido='+apellido+
								'&Nombre='+nombre+
								'&idperito='+idperito+
								'&cmbTipoPericia='+tipoperito+
								'&RedirectPageAnt='+RedirectPageAnt+
								'&htxtcuil='+htxtcuil;
		}
		
		window.location.href = urlreenviar;		
		return true;
  }
  
  function LimpiarErrorMsj(){
	$("#lblErrores").empty();
	$("#ErrorescmbDesignacion").empty();
	$("#ErrorestxtApellido").empty();
	$("#ErrorestxtNombre").empty();
	$("#Errorestxtcuil1").empty();
  }
  
  function ValidarPeritoWebForm(){  	
  	var errorescount = 0;	
	LimpiarErrorMsj();
   
	errorescount += MostrarError(IsNullZero, $('#cmbDesignacion'), $('#ErrorescmbDesignacion'), 'Debe Seleccionar Designacion.');	
	errorescount += MostrarError(IsNullZero, $('#txtApellido'), $('#ErrorestxtApellido'), 'Debe completar Apellido.');	
	errorescount += MostrarError(IsNullZero, $('#txtNombre'), $('#ErrorestxtNombre'), 'Debe completar Nombre.');	
	
	$('#ErrorestxtDireccion').empty();
	$('#Errorestxtcuil1').empty();
	
	if( ($('#txtcuil1').val() != '') || ($('#txtcuil2').val() != '') || ($('#txtcuil3').val() != '') ){		
		if( ($('#txtcuil1').val() == '') || ($('#txtcuil2').val() == '') || ($('#txtcuil3').val() == '') ){		
			errorescount += MostrarError('', '', $('#Errorestxtcuil1'), 'Debe completar Cuil.');	
		}else{
			var cuit = $('#txtcuil1').val() + $('#txtcuil2').val() + $('#txtcuil3').val();
			if (!ValidarCuit(cuit)){
				errorescount += MostrarError('', '', $('#Errorestxtcuil1'), 'Cuit/Cuil invalido.');	
			}
		}		
	}
	
	/* Estos campos no son obligatorios para ingresar un nuevo perito.. 
		Tomado de la version Delphi web
		
	errorescount += MostrarError(IsNullZero, $('#txtDireccion'), $('#ErrorestxtDireccion'), 'Debe completar Direccion.');	
	
	if( ($('#txtcuil1').val() == '') || ($('#txtcuil2').val() == '') || ($('#txtcuil3').val() == '') ){		
		errorescount += MostrarError('', '', $('#Errorestxtcuil1'), 'Debe completar Cuil.');	
	}else{
		var cuit = $('#txtcuil1').val() + $('#txtcuil2').val() + $('#txtcuil3').val();
		if(!ValidarCuit(cuit)) {
			errorescount += MostrarError('', '', $('#Errorestxtcuil1'), 'Cuil invalido.');	
		}
	}
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
//-------------------------------------------------------
function FuncionProcesarItems(valor){
	$("#listadoApellido").click(function (){ ItemSeleccionadoSelect(valor); });
	
	$("#listadoApellido").focus();		
	$("#listadoApellido").blur(function(){ PerdioelFoco(valor) });	
		
	$("#listadoApellido").keypress( function(event){ 
									if ( event.which == 13 ){ 
										event.preventDefault(); 
										ItemSeleccionadoSelect(valor); } } );	
}

function PerdioelFoco(valor){
	if( document.getElementById('listaitems').style.display != 'none'	) 
	{
		document.getElementById('listaitems').style.display = 'none';			
		var itemseleccion = ComboValorTexto("listadoApellido");		
		if(itemseleccion != '')	
			ItemSeleccionadoSelect(valor);
		else{
			document.getElementById("txtcuil1").value = "";
			document.getElementById("txtcuil2").value = "";
			document.getElementById("txtcuil3").value = "";
		}
	}
}

function ItemSeleccionado(){	
	var itemseleccion = ComboValorTexto("listadoApellido");
	
	if(itemseleccion == ''){
		document.getElementById("txtcuil1").value = "";
		document.getElementById("txtcuil2").value = "";
		document.getElementById("txtcuil3").value = "";
	}else{	
		$('#txtcuil1').val(  itemseleccion.substring(0, 2)  );
		$('#txtcuil2').val(  itemseleccion.substring(2, 10)  );
		$('#txtcuil3').val(  itemseleccion.substring(10, 11)  );
	}
	
	document.getElementById('listaitems').style.display = 'none';	
	
	document.getElementById("txtApellido").value = "";
	document.getElementById("txtNombre").value = "";
	document.getElementById("txtDireccion").value = "";
	document.getElementById("txtEMail").value = "";
	document.getElementById("txtTelefono").value = "";
	
	SelecOptionCombo("cmbDesignacion", '');
	
	if($("#txtcuil3").val().trim() != '') {  
		SetIdPerito( ComboValorSeleccionado("listadoApellido") );					
		var IDdivresult = 'listaitems';	
		
		ObtenerPeritosListado(GetPaginafunciones(), GetParametrosfuncion(), IDdivresult, "" , "listaitems", idperito, tipoperito);			
		SetearEstadoAccion( document.getElementById("txtcuil3").value == "" );					
		return true;
	}
}
//-------------------------------------------------------
function iniDialogMensaje(tipoperito, Resp_idperito){
	
	var btnSiguiente = {id: "btnAceptarMsj", 
						text: "", 
						click: function() { $( this ).dialog( "close" ); 
											RedirectPage(tipoperito, Resp_idperito, 'ACEPTAR'); 
											return true; } };
							
	var btnCancelar = {	id: "btnCancelarMsj", 
						text: "", 
						click: function() { $( this ).dialog( "close" ); 
											 RedirectPage(tipoperito, Resp_idperito, 'CANCEL'); 
											 return false; } };	
											
	botones = [btnSiguiente];
		
	$( "#dialogMensaje" ).dialog({			
			position:{my: "center top",  at: "center top",  of: "#divContent"},
			autoOpen:false,
			modal: true,
			// show:"scale",			
			buttons:botones	
	});
	
	if( document.getElementById('btnAceptarMsj'))	JQAsignaClaseCSS("#btnAceptarMsj", "btnAceptar");		
	if( document.getElementById('btnCancelarMsj'))	JQAsignaClaseCSS("#btnCancelarMsj", "btnCancelarEJ");	
			
}

//-------------------------------------------------------------
function EstaCuilCompleto(){
	var textcompletar = '';

	if ($('#txtcuil1').val() == '')
		textcompletar += ' primera parte del Cuil. ';		

	if ($('#txtcuil2').val() == '') 
		textcompletar += ' segunda parte del Cuil. ';		
		
	if ($('#txtcuil3').val() == '')
		textcompletar += ' digito verificador del Cuil.';		
		
	if ( textcompletar != '')
		textcompletar = 'Debe Completar ' + textcompletar;		

	MostrarError('', '', $('#Errorestxtcuil1'), textcompletar);
		
}