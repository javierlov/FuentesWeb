function ObtenerPeritosListadoNombre(pagefunciones, strparametros, IDdivresult){	
	ActivaGif();
	
	var divResultado = document.getElementById(IDdivresult);
	var resultadoDatos = 'FALLO. Intente nuevamente.';
	var resultadoApeNom = '';
	var valor = '';
	divResultado.innerHTML = 'Iniciando... ';		
	
	ajax=objetoAjax();	
	try {
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {			
				if (ajax.status == 200){									
					valor = ajax.responseText;																						
					resultadoDatos = 'OK';				
				}
				else{
					divResultado.innerHTML='Error '+ ajax.statusText; 				
				}
			}
			else{
				divResultado.innerHTML = 'Procesando... ';					
			}		
		}	
		//console.log(pagefunciones);
		//console.log(strparametros);
		ajax.open("GET", pagefunciones+'?'+strparametros,false); 
		ajax.send(null);						
	}
    catch(e) {			
		divResultado.innerHTML = "Error. " + e.message + " ";;					
		DesctivaGif();
		return '';
	}
	
	if( trimString(resultadoDatos) == 'OK'){
		//divResultado.innerHTML = '';												
		DesctivaGif();
		return valor;
	}else{
		divResultado.innerHTML = trimString(resultadoDatos);
		MostrarError('', '', $('#'+IDdivresult), resultadoDatos);		
		DesctivaGif();
		return '';
	}
}

function ObtenerPeritosListado(pagefunciones, strparametros, IDdivresult, FuncionProcesarItems, IDdivLista, idPerito, idTipoPericia){	
/*Esta es la funcion principal que filtrara los datos y crea una  lista seleccionable
	Parametros:
		pagefunciones = url donde esta la funcion de busqueda
		strparametros = nombre de la funcion y parametres para pasar por get en la busqueda
		IDdivresult = div de la pagina origen donde se mostraran los errores y avisos de la busqueda
		FuncionProcesarItems = funcion encargada de procesar los resultados de la lista
		IDdivLista = div donde se mostraran los datos en pantalla
*/
	ActivaGif();
	
	var divResultado = document.getElementById(IDdivresult);
	var resultadoDatos = 'FALLO. Intente nuevamente.';
	divResultado.innerHTML = 'Iniciando... ';		
	
	ajax=objetoAjax();	
	try {
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {			
				if (ajax.status == 200){									
					var valor = ajax.responseText;																						
					resultadoDatos = 'OK';				
					if(idPerito == 0){
						divResultado.innerHTML = RecorrerElementos(valor, IDdivLista);
						FuncionProcesarItems();
					}else {
						return RetornaDatosPerito(valor, IDdivLista, idPerito, idTipoPericia);
					}					
				}
				else{
					divResultado.innerHTML='Error '+ ajax.statusText; 				
				}
			}
			else{
				divResultado.innerHTML = 'Procesando... ';					
			}		
		}	
		//console.log(pagefunciones);
		//console.log(strparametros);
		ajax.open("GET", pagefunciones+'?'+strparametros,false); 
		ajax.send(null);						
	}
    catch(e) {			
		divResultado.innerHTML = "Error. " + e.message + " ";;					
		DesctivaGif();
		return false;
	}
	
	if( trimString(resultadoDatos) == 'OK'){
		divResultado.innerHTML = 'ok';												
		DesctivaGif();
		return true;
	}else{
		divResultado.innerHTML = trimString(resultadoDatos);
		MostrarError('', '', $('#'+IDdivresult), resultadoDatos);		
		DesctivaGif();
		return false;
	}
}
//----------------------------------------------------------------------------
function RecorrerElementos(arrjson, IDdivLista){
/*Esta funcion recorre una estructura con notacion json
 y destibuye los elementos en la lista de items */
	var datos = JSON.parse(arrjson);
	var result  = '';
	//oculta la lista de resultados
	document.getElementById(IDdivLista).style.display = 'block';
	
	for (var i=0; i < datos.length; i++){	
		result  = result + '<option value="'+datos[i].id+'" >';
		result  = result + datos[i].cuitnombre;
		result  = result + '</option>';
	}
	var size = 8; 
	if( i < 7) size = i+1;
		
	if( result != '')
		result  = '<select id="listadoCuit" size="'+size+'"><option value="0"></option>'+result+'</select>';
		
	return result;
}

