
function ObtenerEmpleadosJson(pagefunciones, strparametros, IDdivresult, idProcesando){	
	if(document.getElementById(idProcesando))
		document.getElementById(idProcesando).style.display = 'block';
	
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
	
	
			if( resultadoDatos == 'OK'){		
				if(document.getElementById(idProcesando))
					document.getElementById(idProcesando).style.display = 'none';
				return valor;
			}else{
				divResultado.innerHTML = resultadoDatos;
				MostrarError('', '', $('#'+IDdivresult), resultadoDatos);		
				if(document.getElementById(idProcesando))
					document.getElementById(idProcesando).style.display = 'none';
				return '';
			}
			
	}
    catch(e) {			
		divResultado.innerHTML = "Error. " + e.message + " ";;					
		if(document.getElementById(idProcesando))
			document.getElementById(idProcesando).style.display = 'none';
		return '';
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
		result  = result + '<option value="'+datos[i].value+'" >';
		result  = result + datos[i].label;
		result  = result + '</option>';
	}
	var size = 8; 
	if( i < 7) size = i+1;
		
	if( result != '')
		result  = '<select id="listadoResultados" size="'+size+'"><option value="0"></option>'+result+'</select>';
		
	return result;
}
//-----------------------------------------------------------------------------
function BuscarListadoEmpleados(IDdivresult, funcionlistado) {
	var pagefunciones = "/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php";  	
	var strparametros = "funcion=GetJSArrayEmpleados";
	strparametros += "&ArrayName="+encodeURIComponent('arrayJsonNombre');  
	strparametros += "&EMPRESA="+param_idEmpresa;  
	strparametros += "&NOMBRE="+$('#id_nrNomApe').val();  
	var urljson = window.location.origin + pagefunciones + strparametros;
		
	//var IDdivresult = 'listadoEmpleados';	
	var IDdivLista = '';
	var Nombre = '';
	var idGifProcesando = '';
	
	var resultEmp = ObtenerEmpleadosJson(pagefunciones, strparametros, IDdivresult, 'loadingTrabajador');	
	document.getElementById(IDdivresult).innerHTML = RecorrerElementos(resultEmp, IDdivresult);
	document.getElementById(IDdivresult).style.display = 'block';  	
	
	if(resultEmp != ''){
		FuncionKeyEnter(IDdivresult, funcionlistado);
		
		var controlJQ = "#"+IDdivresult;
		var controlJQ = "#listadoResultados";
		JQElementSetEventdblClick(controlJQ, function(){ funcionlistado(); } ); 		
		$(controlJQ).blur(function(){ PerdioelFocoControl(IDdivresult); });	
	}
    
}

function PerdioelFocoControl(idDivListado){
	document.getElementById(idDivListado).style.display = 'none';		
	JQEliminarElemento(idDivListado);		
}

function FuncionKeyEnter(idControl, funcion) {
		$("#"+idControl).keypress( 
			function(event){ 
				if ( event.which == 13 ){ 
					event.preventDefault(); 
					funcion(); 
				} 
			} );	
}
