function callbackError(XMLHttpRequest, textStatus, errorThrown){			
	alert(errorThrown);
}

function recuperarDatosCallback(ajaxResponse, textStatus){	
	var datos = procesarRespuesta(ajaxResponse);	
	if ( datos.trim() == '' ){		
		$("#Validacion").val('');
		return false;
	}
	else{
		$("#Validacion").val(datos);
		return true;
	}	
}

function procesarRespuesta(ajaxResponse){ 	
	var response = '';
	try { 
		response = ajaxResponse; 		
	} 
	catch(ex) { 
		response = ''; 
	}	
	
	return response;
}
//-----------------------------------------------------------------
function ValidarFuncionServerSincro(pagefunciones, pageParams, valorContext, funcionError, funcionRecuperarDatos){  				
	try{		
		var fechavenc = $('#txtFecha').val();
		
		$.ajax({
			type	: "post",	
			url		: pagefunciones,
			data	: pageParams,
			//context	: {valorContext},
			error	: funcionError,	
			success	: funcionRecuperarDatos,	
			async	: false
		});
	}
	catch(ex){
		alert(ex.description);
	}
}
//-----------------------------------------------------------------

