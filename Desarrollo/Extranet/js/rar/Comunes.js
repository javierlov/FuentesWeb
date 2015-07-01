//----------------------FUNCIONES--------------------------------------------  
  function ValorElementoID(elementoID){
	/* retorna el valor escrito en un input type text */
	var elemento = '';
	if(document.getElementById(elementoID)){
		elemento = trimString(document.getElementById(elementoID).value);
	}
	return elemento;  
  }
  
  function ComboIDSeleccionado(comboID){
	/* retorna el id del item seleccionado */
	if(!document.getElementById(comboID)) 
		return false;
		
	var combo = document.getElementById(comboID);
	if(combo.selectedIndex < 0)
		return false;
		
	return combo.options[combo.selectedIndex].value;
  }
  
  function BuscaAttrSelectItem(idControl, NomAttr){
	/* Busca el valor de el atributo del elemento seleccionado*/	
		var controlselect = document.getElementById(idControl);
		if(controlselect.selectedIndex < 0){
			return '';
		}
		var elemento = document.getElementById(idControl)[controlselect.selectedIndex];
		var valorAttr = elemento.getAttribute(NomAttr);
		return valorAttr;
  }
  
  function ComboValorTexto(comboID){
	//retorna el valor seleccionado que se muestra en pantalla en el combo
	var combo = document.getElementById(comboID);
	if(combo.selectedIndex < 0)	return '';		
	return combo.options[combo.selectedIndex].text;
  }
  
  function OcultarDiv(id){
	/*oculta el control*/
	if(document.getElementById(id))
		document.getElementById(id).style.display = 'none';
  }
  
  function MostrarDiv(id){
	/*muestra el control*/
	if(document.getElementById(id))
		document.getElementById(id).style.display = 'block';
  }
  
  function DesactivaControl(idcontrol, checked, styleControl){
	//checked = false;
	if(document.getElementById(idcontrol)){
		document.getElementById(idcontrol).readOnly = !checked;
		document.getElementById(idcontrol).disabled = !checked;
		
		if(styleControl != ''){
			var object = document.getElementById(idcontrol);	
			object.style.background = styleControl;
		}
			
			/*Ejemplo cambiar la clase a un control
				object.style.cssText = "color:red; left:10px;";
			*/
	}
  }
	
 function hacerFoco(idControl){
	/*hace foco en el control*/
	document.getElementById(idControl).focus();
 }
 
 function eliminaEventoOnClick(idControl){
	/*elimina el  evento onclick un evento de un cotrol*/
	document.getElementById(idControl).onclick = function(event){ return false; };
 }
 function eliminaEventoOnDblClick(idControl){
	/*elimina el  evento ondblclick un evento de un cotrol*/
	document.getElementById(idControl).ondblclick = function(event){ return false; };
 }
 
 function KeySoloNumeros(){
	/*Valida que solo se ingresen numeros
		Asignar esta funcion al evento onKeypress del control*/
	if (event.keyCode < 45 || event.keyCode > 57) 
		event.returnValue = false;
 }
 
 function SepararArrayaString(arraydatos, separator){
	/*Dado un Array lo separa en separator (', ') y Retorna un string */
	var resultado = '';
	for(var i=0; i < arraydatos.length ;i++){
		if(i==0) resultado += arraydatos[i];
		else resultado += separator+arraydatos[i];
	}
		
	return resultado;

 }
 /*************** fechas ************************/
   function ParsearFecha(fecha){					
	/*No parsea correctamente.	var fechaparse=Date.parse(fecha);		*/
	var fechaparse=ExtractFecha(fecha);	
	return fechaparse;
  }
  
  function ExtractFecha(fecha){
	//parsea la fecha en formato dd/mm/yyyy
	var dia = fecha.substring(0, 2);
	var mes = fecha.substring(3, 5);
	var ann = fecha.substring(6, 10);
	
	var fechanueva = ann + mes + dia;
	return parseInt(fechanueva);
  }
  
  function GetFechaHoy(){
	//retorna la fecha de hoy formateada en dd/mm/yyyy esta funcion anda bien en los diferentes navegadores y versiones
  	var f = new Date();
	
	var dia = f.getDate();
	if(dia < 10) dia = '0'+dia;
	
	var mes = f.getMonth() + 1;
	if(mes < 10) mes = '0'+mes;
	
  	var fecha = dia+"/"+mes+"/"+f.getFullYear() ;
  	
  	return fecha;
  }
  
  function IsValidDate(fecha){
	//Valida si la fecha pasada es una fecha valida
	var fecha = ParsearFecha(fecha);
	var sfecha = fecha.toString();
	if(sfecha.length != 8) return false;
	
	var ValidAnn = sfecha.substring(0, 4);
	ValidAnn = parseInt(ValidAnn);
	
	if( ValidAnn == 'NaN') return false
	if( ValidAnn < 1900) return false;
	if( ValidAnn > 2900) return false;
	
	return true;
  }
  
  function IntegerToString(num){	
	//convierte un entero en un string
	num.toString();
	return num;
  }
  
  function stringToInteger(str){	
	 //convierte un string en un entero
 	 var entero = parseInt(str);
	 return entero;
  }
  
  function stringToArray(str, separador){	
	//convierte un string separado por comas en un array.. con separator = ","
	if(str == undefined || str == '') 
		return '';
		
	var res = str.split(separador);
	return res;
  }
  
  function trim(cadena){
       //elimina los espacios en blanco de una cadena
	   cadena=cadena.replace(/^\s+/,'').replace(/\s+$/,'');
       return cadena;
  }
  
  function makeUppercase(elementoNombre) {
	// convierte el texto de un control a mayuscula...
	document.getElementById(elementoNombre).value = document.getElementById(elementoNombre).value.toUpperCase();
 }
 
 function ArrayindexOf(array, valor){
	// busca un valor en un array si existe retorna true / false si no existe
	for(var i=0; array.length > i;i++){
		if(array[i] == valor){ return i; }
	}
	return -1;
 }


 function ExisteTextoEnString(txtMensaje, txtBuscar){
	 //busca un texto en un string si existe retorna true
	var str = txtMensaje;
	var re = /(chapter \d+(\.\d)*)/i;
	var found = str.match( txtBuscar );
	return (found > '');
}

/* ----------------------------------------------- */
function CortarString(idElementoHtml, intIni, intFin){
	if(document.getElementById(idElementoHtml))
		return document.getElementById(idElementoHtml).value.slice(intIni, intFin);
	else
		return 0;
}
/* ----------------------------------------------- */

  