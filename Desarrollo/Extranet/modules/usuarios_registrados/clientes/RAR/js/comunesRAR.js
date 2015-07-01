function imprimeListadoExpuestosPDF(idEstablecimiento, annoActual){	
	
	if(idEstablecimiento == undefined){idEstablecimiento = 0;}
	if(idEstablecimiento == ''){idEstablecimiento = 0;}
	
	//var rutaprint = "/modules/usuarios_registrados/clientes/RAR/ListadoPersonalExpuesto.php";
	
	var rutaprint = "/modules/usuarios_registrados/clientes/RAR/redirect.php";
	var parametros = "?pagename=ListadoPersonalExpuesto&imprimir=true";
	parametros += "&idEstablecimiento="+idEstablecimiento;				
	
	if(annoActual)
		parametros += "&ACTUAL=true";			
		
	parametros += "&NominaConfirmada=NO";		
	parametros += "&checksum="+Math.floor((Math.random() * 9234567) + 1234567);				
	
	var tituloventana = 'ListadoNominaPersonalExpuesto_'+Math.floor((Math.random() * 9234567) + 1234567);
	var path = rutaprint + parametros;
	
	newwindow = window.open(path, tituloventana);
		
	return true;
  }
 
function imprimeListadoAnnoAnterior(status, idrelev, annoProcesar, empresaESTABLECI, empresaCUITSINGUION, nominaConfirmada){
	if(idrelev == undefined){idrelev = 0;}
	if(idrelev == ''){idrelev = 0;}
	
	var rutaprint = "/modules/usuarios_registrados/clientes/RAR/redirect.php";
	var parametros = "?pagename=ListadoPersonalExpuesto";	
	parametros += "&idrelev="+idrelev;
	
	if( status == 'KEYBTNPDF'){
		parametros += "&NominaConfirmada=SI";}
	else{
		parametros += "&NominaConfirmada="+nominaConfirmada;}
	
	parametros += "&checksumidrelev="+Math.floor((Math.random() * 9234567) + 1234567);
	parametros += "&annoProcesar="+annoProcesar;
	
	if(empresaESTABLECI != ''  ){		parametros += "&empresaESTABLECI="+empresaESTABLECI;		}
	if(empresaCUITSINGUION != ''  ){		parametros += "&empresaCUITSINGUION="+empresaCUITSINGUION;	}
	
	if(annoProcesar == 'ACTUAL') parametros += "&ACTUAL=true";
	
	var tituloventana = 'ListadoNominaPersonalExpuesto';
	var path = rutaprint + parametros;
	
	newwindow = window.open(path, tituloventana );
	// newwindow = window.open(path, tituloventana);
	// newwindow = window.open(path, '_blanck'); //nueva ventana
		
	return true;
}
  
function iniDialogMensajeValidJQ(idDialogoJQ, funcion, parametro, Optbtns){	
	/*al parametro idDialogoJQ agregar el "#" sharp */
	var botones;
	var botonNameSig = "dbtnSigJQ";
	var botonNameCan = "dbtnCancJQ";
	
	var btnSiguiente = {id: botonNameSig, text: "", click: function() { $( this ).dialog( "close" ); funcion(parametro); return true; } };
	var btnCancelar = {	id: botonNameCan, text: "", click: function() { $( this ).dialog( "close" ); return false; } };
	
	if(funcion != ''){
		if(Optbtns == 1)
			botones = [btnSiguiente]; 
		if(Optbtns == 2)
			botones = [btnSiguiente, btnCancelar]; 
			
	}else{
		botones = [btnCancelar]; 
	}
	
	$(idDialogoJQ).dialog({
		autoOpen: false,
		width: 400,
		modal:true,
		buttons: botones,
		open: function(event, ui) { $(".ui-dialog-titlebar-close", ui.dialog).hide(); }
	});
	
	if(funcion != ''){
		JQAsignaClaseCSS("#"+botonNameSig, "btnSiguiente");  	
	}
	JQAsignaClaseCSS("#"+botonNameCan, "btnCancelar2");  	
	
}  

function GetBotonMsj(botonID, funcion, parametro){
	var boton;
		boton = {id: botonID, 
				text: "",
				click: function() {
					$( this ).dialog( "close" );  						
					if(funcion != undefined && funcion != '') funcion(parametro);
					return true;
				}
			};	
			
	return boton;
}

function SetResponsableDefault(CODIGOEWID, IDDIVSHOW){
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";				
	var strparametros = "funcion=SetResponsableDefault";
	strparametros = strparametros+"&CODIGOEWID="+CODIGOEWID;	
	
	var resultado = ProcesarDatosResult(pagefunciones, encodeURI(strparametros), IDDIVSHOW);	
	
	if(resultado == 'OK'){
		document.getElementById(IDDIVSHOW).style.display = 'none';
	}else{
		document.getElementById(IDDIVSHOW).style.display = 'block';
	}
}