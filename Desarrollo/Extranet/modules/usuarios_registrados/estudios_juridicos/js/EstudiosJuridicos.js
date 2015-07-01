	function goBack(){	
		window.history.go(-2);	
	}					

	function goBackTime(){
		setTimeout('goBack()', 1000);
	}
	
	function Volver(){	
		window.history.go(-1);
	}					
	
	function VolverPagina(message, page){	
		if(message != '')
		alert(message);	
		
		window.location.href = page;
	}					
	
	function RegExpValidarTelefono(ControlID){	    
		var ControlValidar = document.getElementById(ControlID);
		var ErrorResult = '';
		var textcontrol =  ControlValidar.value;
		
	    if(ControlValidar.value==""){	        
	        ErrorResult = "No puede estar en blanco.";
	        ControlValidar.focus();
	        return ErrorResult;
	    }
	    /*Entre 0 y 20 caracteres, alfanumérico mas (-) o ( ) espacio, 
			No puede contener caracteres espaciales
			patron = ^[a-zA-Z0-9 -]{0,30}$ */		
		
		var patron=new RegExp('^[a-zA-Z0-9 -]{0,30}$');
	    if(!patron.test(textcontrol)){				
	        ErrorResult ="No valido, solo caracteres alfanumericos, espacios en blanco o -";
	        ControlValidar.select();
	        ControlValidar.focus();
	        return ErrorResult;
	    }
		//si todo esta bien se envia un str vacio
		return ErrorResult;
	}
	
	function RegExpValidarEmail(ControlID){	    
		var fEnvioCorreo = document.getElementById(ControlID);
		var ErrorResult = '';
		
	    if(fEnvioCorreo.value==""){
	        
	        ErrorResult = "La cuenta de correo no puede estar en blanco.";
	        fEnvioCorreo.focus();
	        return ErrorResult;
	    }
	    //Se valida la cuenta de correo... usando una regular Exp (RegExp)
		var textcontrol =  fEnvioCorreo.value;
	    if(textcontrol.search(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/ig)){
						     // otra opcion /[\w-\.]{3,}@([\w-]{2,}\.)*([\w-]{2,}\.)[\w-]{2,4}/
	        
	        ErrorResult ="La cuenta no es valida, debes escribirla de forma: nombre@servidor.dominio";
	        fEnvioCorreo.select();
	        fEnvioCorreo.focus();
	        return ErrorResult;
	    }
		
		return ErrorResult;
	}
	
	function ResolucionPantallaUsuario(){
		var hz=window.screen.height
		var wz=window.screen.width
		respuesta = "La resolucion de la pantalla es:<br>";
		respuesta += "Ancho: " + wz + "<br>";
		respuesta += "Alto: " + hz + "<br>";
		return respuesta;
	}
	
	
	function limpiarCaracteresEspeciales($string ){
	 $string = htmlentities($string);
	 $string = preg_replace('/\&(.)[^;]*;/', '\\1', $string);
	 return $string;
	}

	function ValidarFechaYMD(ano, mes, dia){	
		var xano = ano;
		var xmes = mes;
		var xdia = dia;
		 
		valor = new Date(xano, xmes, xdia);
		 
		if( !isNaN(valor) ) {
		  return false;
		}
		
		return true;
	}
	
  function ValidarControlFecha(fecha, controlError){	
	//esta funcion requiere el js
	//<script language="JavaScript" src="/js/validation.js"></script>
	// parametro fecha = nombre del control
	// parametro controlError $("#ErrorestxtFechaAsignacion")
	if(!document.getElementById(fecha)){
			TextNotificacionError = 'Control ' + fecha + ' no existe';		
			document.getElementById(controlError).innerHTML =  TextNotificacionError;			
			return 1;	
	}
	var fechaVal = document.getElementById(fecha).value;
	var controlErrorVal = document.getElementById(controlError).innerHTML;
	
	if(controlErrorVal == ''){
		
		document.getElementById(controlError).style.display = 'none';
		
		if(trimString(fechaVal) == ''){
			TextNotificacionError = 'Fecha inválida (dd/mm/aaaa)';		
			document.getElementById(controlError).innerHTML =  TextNotificacionError;			
			document.getElementById(controlError).style.display = 'block';
			return 1;
		}
		
		if (!ValidarFechaFormato(fechaVal)) {
			TextNotificacionError = 'Por favor, ingrese una fecha válida!';		
			document.getElementById(controlError).innerHTML =  TextNotificacionError;	
			document.getElementById(controlError).style.display = 'block';
			return 1;
		}
	}	
	
	return 0;
  }
  
	
	function formateaFecha(fecha){
		 fecha = fecha.val($.format.date(new Date(), 'dd/mm/yyyy'));
		 return fecha;		 
	}
	

  function FechaHoy(){
  	var f = new Date();
	
	dia = f.getDay();
	if(f.getDay() < 10) dia = '0'+f.getDay();
	
	mes = f.getMonth();
	if(f.getMonth() < 10) mes = '0'+f.getMonth();
	
  	var fecha = dia+"/"+mes+"/"+f.getFullYear() ;
  	
  	return fecha;
  }
  
 /*---------------------------------------------------*/
  function MostrarErrorHaceFoco(funcionValidar, controlValidar, controlError, msjError, idControl){			
		var valorreturn = MostrarError(funcionValidar, controlValidar, controlError, msjError, false);
		HacerFoco(idControl);
		
  }
  
  function HacerFoco(idControl){
	    if ( trimString(idControl) != '' && (document.getElementById(idControl)) ){
			document.getElementById(idControl).focus();
		}
  }
  
  function MostrarError(funcionValidar, controlValidar, controlError, msjError, append){			
  //ESTA FUNCION SE USA PARA MOSTRAR MENSAJES EN PANTALLA UNO POR CADA CAMPO OBLIGATORIO
	if(!append) controlError.empty();
	
	if(funcionValidar == '') {
		if(append) controlError.append(msjError);
		else controlError.html(msjError);
		controlError.show("slow");		
		return 1;
	}
	
	if (funcionValidar(controlValidar) ){	  			
		if(append) controlError.append(msjError);
		else controlError.html(msjError);
		
		controlError.show("slow");		
		return 1;
	}
	return 0;
  }
    
  function IsNullLessZero(controlValidar){
	if (controlValidar.val() == null || 
			trimString(controlValidar.val() ) == '' || 
			parseInt(controlValidar.val() ) < 0 ){	  			
		return true;
	}
	return false;
  }
  
  function trimString(valStr){
	return $.trim(valStr);
  }
  
  function IsNullZero(controlValidar){
	var valtxt = controlValidar.val();
	var valControl = parseInt(valtxt);
	
	//if (controlValidar.val() == null || controlValidar.val().trim() == '' || parseInt(controlValidar.val() ) <= 0 ){	  			
	if (controlValidar.val() == null || valControl <= 0 || trimString(controlValidar.val()) == ''  ){	  			
		return true;
	}
	return false;
  }
  
  function IsZeroCien(controlValidar){
	//esta funcion valida porcentaje de 0 a 100
	if (controlValidar.val() == null || 
		trimString(controlValidar.val() ) == '' || 
		parseInt(controlValidar.val() ) <= 0 || 
		parseInt(controlValidar.val() ) > 100 ){	  			
		return true;
	}
	return false;
  }
  
  function IsNotNullAndZero(controlValidar){
	if (controlValidar.val()!=null || 
			trimString(controlValidar.val() ) != ''){	  			
		if(parseInt(controlValidar.val() ) < 0 )
			return true;
	}
	return false;
  }
  
  function GetMesNum(getMonth){
	var mesesNum = new Array ("01","02","03","04","05","06","07","08","09","10","11","12");
	return mesesNum[getMonth];
  }
 
  function GetFechaHoy(){
	var Fecha = new Date();
	var diahoy = Fecha.getDate();
	if(diahoy < 10)	diahoy = "0" + diahoy;
		
	var FechaHoy = diahoy + "/" + GetMesNum(Fecha.getMonth()) + "/" + Fecha.getFullYear();		
	return FechaHoy;
  }

 //--------------------------------------------------------
 function MostrarVentanaResultado(mensaje){
	/* Muestra la ventana de mensaje */	
	if(document.getElementById('idmensajeResultado'))
		document.getElementById('idmensajeResultado').innerHTML = mensaje;
		
	if(document.getElementById('VentanaMensajeResultado'))
		document.getElementById('VentanaMensajeResultado').style.display = 'block';
	
	if(document.getElementById('VentanaFondoResultado'))
		document.getElementById('VentanaFondoResultado').style.display = 'block';
	
	if(document.getElementById('idbtnAceptarVentanaResultado'))
		document.getElementById('idbtnAceptarVentanaResultado').focus();
  }
  
  function OcultarVentanaResultado(){
	/* Oculta la ventana de mensaje */
	document.getElementById('VentanaMensajeResultado').style.display = 'none';
	document.getElementById('VentanaFondoResultado').style.display = 'none';
  }
 //--------------------------------------------------------  
 function MostrarVentanaOKCancel(mensaje){
	/* Muestra la ventana de mensaje */	
	if(document.getElementById('idmensajeOKCancel'))
		document.getElementById('idmensajeOKCancel').innerHTML = mensaje;
		
	if(document.getElementById('VentanaMensajeOKCancel'))
		document.getElementById('VentanaMensajeOKCancel').style.display = 'block';
		
	if(document.getElementById('VentanaFondoOKCancel'))
		document.getElementById('VentanaFondoOKCancel').style.display = 'block';
		
	if(document.getElementById('idbtnAceptarOKCancel'))
		document.getElementById('idbtnAceptarOKCancel').focus();
  }
  
   function MostrarVentanaSoloOK(mensaje){
	/* Muestra la ventana de mensaje */	
	/*'idmensajeOKCancel' , 'VentanaFondoSoloOK'*/
	if(document.getElementById('idmensajeSoloOK'))
		document.getElementById('idmensajeSoloOK').innerHTML = mensaje;
		
	if(document.getElementById('VentanaMensajeSoloOK'))
		document.getElementById('VentanaMensajeSoloOK').style.display = 'block';
		
	if(document.getElementById('VentanaFondoSoloOK'))
		document.getElementById('VentanaFondoSoloOK').style.display = 'block';
		
	if(document.getElementById('idbtnAceptarSoloOK'))
		document.getElementById('idbtnAceptarSoloOK').focus();
  }
  
  function OcultarVentanaOKCancel(){
	/* Oculta la ventana de mensaje */
	document.getElementById('VentanaMensajeOKCancel').style.display = 'none';
	document.getElementById('VentanaFondoOKCancel').style.display = 'none';
  }
 //--------------------------------------------------------
  
  /*
	<?php echo VentanaMensajeOculta(); ?>
	<input type="button" value="abrir" onclick="MostrarVentana();" class="btnVacio" >
*/
  function OcultarVentana(){
	/* Oculta la ventana de mensaje */
	document.getElementById('VentanaMensaje').style.display = 'none';
	document.getElementById('VentanaFondo').style.display = 'none';
  }

  function MostrarVentana(mensaje){
	/* Muestra la ventana de mensaje */
	document.getElementById('idmensaje').innerHTML = mensaje;
	document.getElementById('VentanaMensaje').style.display = 'block';
	document.getElementById('VentanaFondo').style.display = 'block';
	document.getElementById('idbtnAceptarVentana').focus();
  }
  
  function BuscarWGTrue() { BuscarWaitingGif(true); }
  function BuscarWGFalse() { BuscarWaitingGif(false); }
  
  function BuscarWGFalseInterval() { 
	var myVar = setInterval(function(){
							BuscarWaitingGif(false)
							clearInterval(myVar);
							}, 50);	
	}
  
  function BuscarWaitingGif(mostrar) {
	
	if (mostrar) {
		if(document.getElementById('divContentGrid')) document.getElementById('divContentGrid').style.display = 'none';
		if(document.getElementById('divProcesando')) document.getElementById('divProcesando').style.display = 'block';
		//console.log("start witing ......");
	}
	else{
		if(document.getElementById('divContentGrid')) document.getElementById('divContentGrid').style.display = 'block';
		if(document.getElementById('divProcesando')) document.getElementById('divProcesando').style.display = 'none';
		//console.log("stop witing .......");
	}
	
  }
 //------------------------------------------------------------------
  function OcultarDivProcesando(){
	document.getElementById('divProcesando').style.display = 'none';
	document.getElementById('VentanaFondo').style.display = 'none';
	return true;
  }

  function MostrarDivProcesando(){
	document.getElementById('divProcesando').style.display = 'block';
	document.getElementById('VentanaFondo').style.display = 'block';
	//alert("MostrarDivProcesando");
	return true;
  }
  //------------------------------------------------------------------
  function ParsearFecha(fecha){					
	/*No parsea correctamente.
		var fechaparse=Date.parse(fecha);		
	*/
	var fechaparse=ExtractFecha(fecha);
	//console.log(fechaparse);
	return fechaparse;
  }
  
  function ExtractFecha(fecha){
	var dia = fecha.substring(0, 2);
	var mes = fecha.substring(3, 5);
	var ann = fecha.substring(6, 10);
	
	var fechanueva = ann + mes + dia;
	return parseInt(fechanueva);
  }
  //------------------------------------------------------------------
  function ValidarFormatoMoneda(controlName){
	var resultado = false;
	var controlValor = $(ControlName).val();	
	
    var resultado = TieneCaracteresInvalidos(controlValor);
	/*por ahora no valido el formato
	if(resultado){
		var controlValor = $(controlName).val();
		resultado = isCurrency(controlValor);
	}
	*/
	return resultado;
  }
    
  function TieneCaracteresInvalidos(controlValor){
	//Valida cualquier cosa que no sea numero, punto o coma
	var patron = /^-?[0-9\.]+([,][0-9]*)?$/;
	var resultado = patron.test(controlValor);			
	return resultado;
  }
    
  function isCurrency(value){
	//Validar moneda
	var patronmoneda = /^[0-9]+(\,[0-9]{2})?$/;		
	var resultado = patronmoneda.test(value);
	return !resultado;
  }
  
  function isNumber(value){
	//Validar numero,todos los caracteres deben ser numeros
	var patronmoneda = /^([0-9])*$/;		
	var resultado = patronmoneda.test(value);
	return resultado;
  }
  
  function ValidarMoneda(ControlValdida, DivErrorMsj){  
	//valida si el campo es valido como moneda
	if( trimString($(DivErrorMsj).html() ) == '') {		
		var ErrorFormato = "Formato Moneda invalido";				
		var txtValida = $(ControlValdida).val();
		
		if(!TieneCaracteresInvalidos(txtValida)){
			return MostrarError('','', $(DivErrorMsj), ErrorFormato);						
		}			
	}	
	return 0;
  }
  
  function FormatearMoneda(monto){	
	//convierte moneda al formato valido para guardar en la base (00000000.00)
	monto = monto.replace(/\./g,""); 	
    monto = monto.replace(",","."); 
	monto = parseFloat(monto);
	return monto;
  }
  
  function ValFloatRedondeaArriba(txtfloatVal){
	//toma el valor y lo redondea arriba ejemplo 0.2 = 1
	//si el separador es una coma lo convierte a punto	
	txtfloatVal = trimString(txtfloatVal);
	
	if(txtfloatVal == '') txtfloatVal = '0';
	
	var f_floatVal = FormatearMoneda(txtfloatVal);
	var c_floatVal = Math.ceil( f_floatVal );
	return c_floatVal;
	
  }
    
  function ValidarMonto(controlValidar, controlErrorMessage){
	/*Valida si el monto esta en un formato valido para grabar 
		y si es menor a el mayor valor permitido en la base
		retorna 0 si es valido
		si el control para mostrar errores ya esta mostrando un error no valida...
	*/
	if( trimString($(controlErrorMessage).text()) != '') return 0;
	
	var errorescount = ValidarMoneda(controlValidar, controlErrorMessage);
	
	var monto = $(controlValidar).val();		
	monto = FormatearMoneda(monto);
	
	if( trimString($(controlErrorMessage).text()) == ''){
		if( monto >= 1000000000.00 ){
			var txtError = 'Monto invalido, valor mayor al permitido.';
			errorescount = MostrarError('', '', $(controlErrorMessage), txtError);	
		}
	}
	
	return errorescount;
}
  //------------------------------------------------------------------
  function ValidaSoloNumeros(valor){
	//retorna true si valor son solo numeros
	if( isNaN(valor) ) {				
		//console.log( valor );
		return false;
	}
	return true;
  }
  
  //------------------------------------------------------------------
  function SetearControlFecha(idtxtFecha, idbtnFecha){	  
	  /*
	  var fechaactual = document.getElementById(idtxtFecha).value.trim();
	  if(!ValidarFechaFormato(fechaactual)){
		document.getElementById(idtxtFecha).value = '';
	  }
	  */
	  Calendar.setup({
			inputField : idtxtFecha,
			trigger    : idbtnFecha,
			onSelect   : function() { this.hide() },        
			dateFormat : "%d/%m/%Y"
		});  	
  }
//------------------------------------------------------------------
 function BloquearControlesForm(form) {
	//Esta funcion bloquea los controles cuando el juicio esta Terminado....
	//parametro form es el id del form html
	var x = document.getElementById(form);
	for (i=0; i < x.elements.length ; i++) {
		//if (x.elements[j].getAttribute('validar') == 'true') {		}
		
		 if(x.elements[i].type == 'text' || x.elements[i].type == 'textarea' || x.elements[i].type == 'select-one' ){			
			var elem = x.elements[i];
			//bloquea los text
			elem.readOnly = true; 
			//bloquea los controles select
			elem.disabled=true;
			
			elem.style.color = "#808080";
			elem.style.backgroundColor = "#CFCDCE";
			elem.style.padding = "1px 4px 1px 4px";
			elem.style.border = "1px solid #999999";
			elem.style.fontFamily = "Verdana";						
		 }
	}
	
	for (i=0; i < x.elements.length ; i++) {
		 if(x.elements[i].className == 'BotonFechaEstudio'){
			boton = x.elements[i];
			padre = boton.parentNode;
			padre.removeChild(boton);
		 }
	}
}
//----------------------FUNCIONES--------------------------------------------
  function ValorElementoID(elementoID){
	//retorna el valor escrito en un input type text
	var elemento = '';
	if(document.getElementById(elementoID)){
		elemento = trimString(document.getElementById(elementoID).value);
	}
	return elemento;  
  }
  
  function ComboValorSeleccionado(comboID){
	//retorna el id del item seleccionado
	if(!document.getElementById(comboID)) 
		return false;
		
	var combo = document.getElementById(comboID);
	if(combo.selectedIndex < 0)
		return false;
		
	return combo.options[combo.selectedIndex].value;
  }
  
  function BuscaAttrSelectItem(idControl, NomAttr){
/* Busca el valor de el atributo
	del elemento seleccionado
*/	
		var controlselect = document.getElementById(idControl);
		if(controlselect.selectedIndex < 0){
			return '';
		}
		var elemento = document.getElementById(idControl)[controlselect.selectedIndex];
		var valortipoperito = elemento.getAttribute(NomAttr);
		return valortipoperito;
  }
  
    
  function BuscaAttrIdControl(idControl, NomAttr){
	/* Busca el valor de un atributo*/	
	var control = document.getElementById(idControl);			
	var valortipoperito = control.getAttribute(NomAttr);
	return valortipoperito;
  }
  
  function CuentaCaracteres(idTextArea, idControlShow, mostrartexto){
	/*esta funcion cuenta toma el maxlength del textarea pasado y calcula cuanto caracteres quedan para usar
		si el parametro mostrartexto es false se retorna la cantidad de caracteres restantes...*/
		
	var txtDetalleSentencia = document.getElementById(idTextArea).value;			
				//$('#'+idTextArea).html();
				
	var cuenta = txtDetalleSentencia.length;
	var totalChar = BuscaAttrIdControl(idTextArea, 'maxlength');
	var restanChar = totalChar - cuenta;
	
	if(mostrartexto){		
		//$('#'+idControlShow).html("La cantidad máxima de caracteres permitida es  "+totalChar+" (quedan "+restanChar+").");		
		document.getElementById(idControlShow).innerHTML  = "La cantidad máxima de caracteres permitida es  "+totalChar+" (quedan "+restanChar+").";		
		
		console.clear();
		console.log(document.getElementById(idControlShow).innerHTML);
		return true;
	}else
		return restanChar;
  }
  
  function ComboValorTexto(comboID){
	//retorna el valor seleccionado que se muestra en pantalla en el combo
	var combo = document.getElementById(comboID);
	if(combo.selectedIndex < 0)	return '';		
	return combo.options[combo.selectedIndex].text;
  }
    
  function TextAreaText(textareaID){	
	//retorna el texto de un textarea
	var texto = document.getElementById(textareaID).value;
	return texto;
  }
    
  function AsignTextATd(idControl, TituloControl){		
	//Asigna el valor al control div
	var texto = document.getElementById(idControl);
	texto.innerHTML = TituloControl;
  }
  
 function SelecOptionCombo(idcombo, option) {
	/*funcion selecciona un item de una lista
		parametros 
			idcombo = id del combo de datos
			option = propiedad option del item a seleccionar.
	*/
	var combo = document.getElementById(idcombo);
	var cantidad = combo.length;
	
	for (i = 0; i < cantidad; i++) {
		if (combo.options[i].value == option) {
			combo[i].selected = true;
		}   
		else{combo[i].selected = false;}
	}
 }
//------------------------------------------------------------------  
function ValidarFechaFormato(fecha) {

//Version modificada
  if (fecha == '  /  /')
    return true;
	
  if (fecha == '')
    return true;
    
  if (fecha) {
    borrar = fecha;
    if ((fecha.substr(2, 1) == '/') && (fecha.substr(5, 1) == '/')) {
      for (i = 0; i < 10; i++) {
        if (((fecha.substr(i, 1) < '0') || (fecha.substr(i, 1) > '9')) && (i != 2) && (i != 5)) {
          borrar = '';
          break;
        }
      }
      if (borrar) {
        a = fecha.substr(6, 4);
        m = fecha.substr(3, 2);
        d = fecha.substr(0, 2);
        if((a < 1900) || (a > 2050) || (m < 1) || (m > 12) || (d < 1) || (d > 31))
          borrar = '';
        else {
          if((a%4 != 0) && (m == 2) && (d > 28))
            borrar = ''; // Año no biciesto y es febrero y el dia es mayor a 28
          else {
            if ((((m == 4) || (m == 6) || (m == 9) || (m==11)) && (d>30)) || ((m==2) && (d>29)))
              borrar = '';
          }
        }
      }
    }
    else
      borrar = '';

    return (borrar != '');
  }
  return false;
}
//------------------------------------------------------------------  
 function bindEvent(el, eventName, eventHandler) {
	//funcion de compatibilidad con IE antiguos
  if (el.addEventListener){
    el.addEventListener(eventName, eventHandler, false); 
  } else if (el.attachEvent){
    el.attachEvent('on'+eventName, eventHandler);
  }
}
  