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
	
	function validarEmail(){
	    var hayAlgo = true;
	
	    if(fEnvioCorreo.sender.value==""){
	        hayAlgo = false;
	        alert("La cuenta de correo no puede estar en blanco.");
	        fEnvioCorreo.sender.focus();
	        return false;
	    }
	    //Se valida la cuenta de correo... usando una regular Exp (RegExp)
	    if(fEnvioCorreo.sender.value.search(/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/ig)){
	        hayAlgo = false;
	        alert("La cuenta no es valida, debes escribirla de forma: nombre@servidor.dominio");
	        fEnvioCorreo.sender.select();
	        fEnvioCorreo.sender.focus();
	        return false;
	    }
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
