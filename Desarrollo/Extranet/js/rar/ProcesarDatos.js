function objetoAjax(){
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
 
	try {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	} catch (E) {
		xmlhttp = false;
	}
}
 
if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	  xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
//--------------------------------------------------------------------------------
function AgregarParametroHHMMSS(){
	var f = new Date();
	return "&check="+f.getHours()+f.getMinutes()+f.getSeconds();
}
//--------------------------------------------------------------------------------
function ProcesarDatos(pagefunciones, strparametros, idDivMsgError, idDivLoading){	
	ActivaDivMsg(idDivLoading); //GIF ANIMADO
	
	var divResultado = document.getElementById(idDivMsgError);
	var resultadoDatos = 'FALLO. Intente nuevamente.';
	divResultado.innerHTML = 'Iniciando... ';					
	ajax=objetoAjax();	
	try {
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {			
				if (ajax.status == 200){				
					var resultadoHTML = ajax.responseText;					
					resultadoDatos = 'OK';	
					divResultado.innerHTML = unescape(resultadoHTML);
				}
				else{
					divResultado.innerHTML='Error '+ ajax.statusText; 				
				}
			}
			else{
				divResultado.innerHTML = 'Procesando... ';					
			}		
		}		
		var urljson = pagefunciones+'?'+strparametros;
		ajax.open("POST", urljson,false); 
		ajax.send(null);						
	}
    catch(e) {			
		divResultado.innerHTML = "Error. " + e.message + " ";;					
		DesactivaDivMsg(idDivLoading); //GIF ANIMADO
		return false;
	}
	
	if(resultadoDatos == 'OK'){
		//divResultado.innerHTML = '';												
		DesactivaDivMsg(idDivLoading); //GIF ANIMADO
		return true;
	}else{
		divResultado.innerHTML = resultadoDatos;
		//MostrarError('', '', $('#'+idDivMsgError), resultadoDatos);		
		DesactivaDivMsg(idDivLoading); //GIF ANIMADO
		return false;
	}
}
//--------------------------------------------------------------------------------
function ProcesarDatosResult(pagefunciones, strparametros, idDivMsgError){	
	
	var divResultado = document.getElementById(idDivMsgError);
	var resultadoDatos = 'FALLO. Intente nuevamente.';
	var resultadoHTML = '';
	
	divResultado.innerHTML = 'Iniciando... ';					
	
	ajax=objetoAjax();	
	try {
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {			
				if (ajax.status == 200){				
					resultadoHTML = ajax.responseText;															
					resultadoDatos = 'OK';				
					divResultado.innerHTML = resultadoHTML;												
				}
				else{
					divResultado.innerHTML='Error '+ ajax.statusText; 				
				}
			}
			else{
				divResultado.innerHTML = 'Procesando... ';					
			}		
		}	
		var urljson = pagefunciones+'?'+strparametros;
		ajax.open("GET", urljson, false); 
		ajax.send(null);						
	}
    catch(e) {			
		divResultado.innerHTML = "Error. " + e.message + " ";;							
		return false;
	}
	
	if(resultadoDatos == 'OK'){		
		return resultadoHTML;
	}else{
		divResultado.innerHTML = resultadoDatos;				
		return false;
	}
}
//--------------------------------------------------------------------------------
function ProcesarDatosDB(pagefunciones, strparametros, idDivMsgError){	
	ActivaDivMsg("loading"); //GIF ANIMADO
	
	var divResultado = document.getElementById(idDivMsgError);
	var resultadoDatos = 'FALLO. Intente nuevamente.';
	divResultado.innerHTML = 'Iniciando... ';					
	ajax=objetoAjax();	
	try {
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {			
				if (ajax.status == 200){				
					var resultadoHTML = ajax.responseText;															
									
					resultadoDatos = 'OK';				
					divResultado.innerHTML = resultadoHTML;												
					
				}
				else{
					divResultado.innerHTML='Error '+ ajax.statusText; 				
				}
			}
			else{
				divResultado.innerHTML = 'Procesando... ';					
			}		
		}	
		var urljson = pagefunciones+'?'+strparametros;
		ajax.open("GET", urljson,false); 
		ajax.send(null);						
	}
    catch(e) {			
		divResultado.innerHTML = "Error. " + e.message + " ";;					
		DesactivaDivMsg("loading"); //GIF ANIMADO
		return false;
	}
	
	if(resultadoDatos == 'OK'){		
		DesactivaDivMsg("loading"); //GIF ANIMADO
		resultadoDatos = '';					
		return true;
	}else{
		divResultado.innerHTML = resultadoDatos;		
		DesactivaDivMsg("loading"); //GIF ANIMADO
		return false;
	}
}
//--------------------------------------------------------------------------------
function ActivaDivMsg(idDiv){	
	if(document.getElementById(idDiv))
		document.getElementById(idDiv).style.display = 'block';
}

function DesactivaDivMsg(idDiv){	
	if(document.getElementById(idDiv))
		document.getElementById(idDiv).style.display = 'none';
}

function ProcesarDatosJSON(pagefunciones, strparametros, idDivMsgError){	
	ActivaDivMsg("loading"); //GIF ANIMADO
	
	var divResultado = document.getElementById(idDivMsgError);
	var resultadoDatos = 'FALLO. Intente nuevamente.';
	divResultado.innerHTML = 'Iniciando... ';					
	ajax=objetoAjax();	
	try {
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {			
				if (ajax.status == 200){				
					var resultadoHTML = ajax.responseText;																				
					resultadoDatos = 'OK';				
					divResultado.innerHTML = resultadoHTML;												
					return resultadoHTML;
				}
				else{
					divResultado.innerHTML='Error '+ ajax.statusText; 				
				}
			}
			else{
				divResultado.innerHTML = 'Procesando... ';					
			}		
		}	
		var urljson = pagefunciones+'?'+strparametros;
		ajax.open("POST", urljson,false); 
		ajax.send(null);						
	}
    catch(e) {			
		divResultado.innerHTML = "Error. " + e.message + " ";;					
		DesactivaDivMsg("loading"); //GIF ANIMADO
		return false;
	}
	
	if(resultadoDatos == 'OK'){		
		DesactivaDivMsg("loading"); //GIF ANIMADO
		resultadoDatos = '';					
		divResultado.style.display = 'none';  
		
		return divResultado.innerHTML;
	}else{
		divResultado.innerHTML = resultadoDatos;		
		DesactivaDivMsg("loading"); //GIF ANIMADO
		return false;
	}
}
