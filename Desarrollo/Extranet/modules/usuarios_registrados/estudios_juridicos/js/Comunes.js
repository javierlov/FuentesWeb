  function CompletarSecretariaSubmit(idcomboload, funcionstart, valorJuzgado, valorSecretaria){  		
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/CargaComboDatos.php";
	pagefunciones = pagefunciones + "?FUNCION=CargarSecretaria";
	pagefunciones = pagefunciones + "&Juzgado="+valorJuzgado;
	pagefunciones = pagefunciones + "&Secretaria="+valorSecretaria;	

	var x=$(idcomboload);
	x.html("<option selected='selected'>buscando...</option>");
	x.ajaxStart(funcionstart);
	x.load(pagefunciones);

	return true;    	
  }  
  
  function CompletarJuzgadoSubmit(idcomboload, funcionstart, valorJurisdiccion, valorFuero, valorJuzgado){	
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/CargaComboDatos.php";
	pagefunciones = pagefunciones + "?FUNCION=CargarJuzgado";
	pagefunciones = pagefunciones + "&jurisdiccion="+valorJurisdiccion;
	pagefunciones = pagefunciones + "&Fuero="+valorFuero;
	pagefunciones = pagefunciones + "&Juzgado="+valorJuzgado;
	
	var x=$(idcomboload);
	x.html("<option selected='selected'>buscando...</option>");
	x.ajaxStart(funcionstart);
	x.load(pagefunciones);

	return true;  	
  }
  
  function CompletarFueroSubmit(idcomboload, funcionstart, funcionstop, valorJuridiccion, valorFuero) { 
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/CargaComboDatos.php";
	pagefunciones = pagefunciones + "?FUNCION=CargarFuero";
	pagefunciones = pagefunciones + "&Juridiccion="+valorJuridiccion;
	pagefunciones = pagefunciones + "&Fuero="+valorFuero;

	var x=$(idcomboload);			
	x.html("<option selected='selected'>buscando...</option>");
	x.ajaxStart(funcionstart);						
	x.ajaxStop(funcionstop);		
	x.load(pagefunciones); 

	return true;
  }
  
  function CompletarComboEstados(idcomboload, funcionstart, textofiltro) { 
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/CargaComboDatos.php";
	pagefunciones = pagefunciones + "?FUNCION=CargarRazonSocial";
	pagefunciones = pagefunciones + "&texto="+textofiltro;		

	var x=$(idcomboload);			
	x.html("<option selected='selected'>buscando...</option>");
	x.ajaxStart(funcionstart);		
	x.load(pagefunciones); 
	return true;
  }
  
  function CompletarComboEstadosJuridicos(idcomboload, funcionstart, seleccionado){  		
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/CargaComboDatos.php";
	pagefunciones = pagefunciones + "?FUNCION=CargarEstadosJuicio";
	pagefunciones = pagefunciones + "&seleccionado="+seleccionado;		

	var x=$(idcomboload);
	x.html("<option selected='selected'>buscando...</option>");
	x.ajaxStart(funcionstart);
	x.load(pagefunciones);

	return true;    	
  }

  function ObtenerInstanciaSeleccionada(idInstancia, jurisdiccion, fuero, juzgado, CampoID){  				
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php";
	pagefunciones = pagefunciones + "?FUNCION=ObtenerInstanciaSeleccionada";
	
	pagefunciones = pagefunciones + "&jurisdiccion="+jurisdiccion;		
	pagefunciones = pagefunciones + "&fuero="+fuero;		
	pagefunciones = pagefunciones + "&juzgado="+juzgado;		
	//jurisdiccion, fuero, juzgado		
	if(CampoID > '') 
		pagefunciones = pagefunciones + "&CampoID";		
	
	var x=$(idInstancia);						
	x.load(pagefunciones);	
	
	return true;    	
  }  
  
  function ValidarExpedienteNroYearSecretaria(iderrorctrl, nroInstancia, NroExpediente, AnioExpediente, Secretaria){  				
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php";
	pagefunciones = pagefunciones + "?FUNCION=ValidarExpedienteNroYearSecretaria";
	
	pagefunciones += "&nroInstancia="+nroInstancia;		
	pagefunciones += "&NroExpediente="+NroExpediente;		
	pagefunciones += "&AnioExpediente="+AnioExpediente;		
	pagefunciones += "&Secretaria="+Secretaria;		
	//jurisdiccion, fuero, juzgado		
	var x=$(iderrorctrl);						
	x.load(pagefunciones, aviso);		
	//si es valido retorna true
	if(x.val() == ''){return true;}	
	x.val("Expediente ya existente");
	return false;		
  }
	
  function aviso(){
	//console.log("Campos validados");
  }
  
/*****************************************************************************/
  function inicioEnvio(){  var x=$("#idtxtInstancia");  x.val('..cargando');}
  function llegadaDatos(datos){  var x=$("#idtxtInstancia"); x.text(datos);}
  function problemas(){  var x=$("#idtxtInstancia"); x.text('Problemas en el servidor.');}  
/**********************************************************/
  function BuscarMotivoJuzgado(iddiv, MotivoID){  				
		var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php";
		pagefunciones = pagefunciones + "?FUNCION=BuscarMotivoJuzgado";		
		pagefunciones = pagefunciones + "&MotivoID="+MotivoID;				
		var x=$(iddiv);						
		x.load(pagefunciones);					
		return iddiv.text();    	
  }  
 
  function ObtenerAnioValidoExpediente(iddiv, AnioExpediente){  				
		var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php";
		pagefunciones = pagefunciones + "?FUNCION=ObtenerAnioValidoExpediente";		
		pagefunciones = pagefunciones + "&anioExpediente="+AnioExpediente;				
		var x=$(iddiv);						
		x.load(pagefunciones);					
		return iddiv.text();    	
  }   
/**********************************************************/  
  function ObtenerValidacionFechaAcuerdo(iddiv, fechavenc, nroorden){  				
		var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosConcursosQuiebras.php";
		pagefunciones = pagefunciones + "?FUNCION=ObtenerValidacionFechaAcuerdo";
		pagefunciones = pagefunciones + "&fechavenc="+fechavenc;		
		pagefunciones = pagefunciones + "&nroorden="+nroorden;						

		var x=$(iddiv);						
		x.load(pagefunciones);	
  }
  
/**********************************************************/  
function ValidarYearMesExpediente(iderrorctrl, nroInstancia, NroExpediente, AnioExpediente, Secretaria){  				
	//valida en forma sincrona año y mes de expediente si ya existe ingresado
	var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php";
	
	pageparametros = "FUNCION=ValidarExpedienteNroYearSecretaria";	
	pageparametros += "&nroInstancia="+nroInstancia;		
	pageparametros += "&NroExpediente="+NroExpediente;		
	pageparametros += "&AnioExpediente="+AnioExpediente;		
	pageparametros += "&Secretaria="+Secretaria;		
		
//---------------SYNCRO-------------------
	var nombre='';
	var idDiv=iderrorctrl;
	var div=$(idDiv);
	var respuesta = "Expediente ya existente";
	
	$.ajax({
	  async:false,    
	  cache:false,   
	  dataType:"html",
	  type: 'POST',   
	  url: pagefunciones,
	  data: pageparametros, 
	  success:  function(respuesta){  		  
		  div.text(respuesta);
	  },
	  beforeSend:function(){},
	  error:function(objXMLHttpRequest){}
	});
//----------------------------------
	if(div.text().trim() == '')
		return true;		
	else 
		return false;	
}

//---------------SYNCRO-------------------
function BuscaAnioValidoExpedienteSINCRO(iddivyear, AnioExpediente){  				
		var pagefunciones = "/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php";
		var pageparametros = "FUNCION=ObtenerAnioValidoExpediente";		
		pageparametros = pageparametros + "&anioExpediente="+AnioExpediente;				
		
//---------------SYNCRO-------------------		
	var div=$(iddivyear);
	//var respuesta = "Expediente ya existente";	
	
	$.ajax({
	  async:false,    
	  cache:false,   
	  dataType:"html",
	  type: 'POST',   
	  url: pagefunciones,
	  data: pageparametros, 
	  success:  function(){div.text(data);},
	  beforeSend:function(){},
	  error:function(objXMLHttpRequest){}
	});
//----------------------------------
	if(div.text().trim() == '')
		return true;		
	else 
		return false;			
		
  }   